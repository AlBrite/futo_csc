<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function verifyOTP(Request $request) {
        $otp = [];
        $session = session();
        $otp_user_id = $session->get('otp_user_id');
        $storedOTP = session()->get('otp');

        if (!$storedOTP || !$otp_user_id) {
            return redirect()->back()->with('error', 'OTP has expired. Please try again');
        }

       
      for($i = 1; $i <= 6; $i++) {
        $value = $request->get('otp'.$i);
        if (!is_numeric($value)) {
            return redirect()->back()->with('error', 'Invalid OTP value');
        }
        $otp[] = $value;;
      }
      $otp = (int) implode('', $otp);

      if ($otp !== $storedOTP) {
            return redirect()->back()->with('error', 'Invalid OTP value');
      }
      
      $user = User::find($otp_user_id);

      if (Auth::loginUsingId($otp_user_id)) {
        Session::forget('otp_user_id');
        Session::forget('otp');
        Device::store();
      }

      return redirect('/');

    }
 


   

    public function apiLogin(Request $request) {
        
        
        return $this->attemptLogin($request, function($user){
            $token = $user->createToken('myApp')->plainTextToken;
            return compact('token');
        });
       
    }



    public function attemptLogin(Request $request, Closure $callback, )
    {
        $retryLimit = 5;


        $field = $this->credential();

        $fields = $request->validate([
            'usermail' => 'required',
            $field => 'required',
            'password' => 'required'
        ]);
        

        $user  = User::where($field, $fields[$field])->first();
       
        // Check if user exists
        if (!$user) {
            return response([
                'error' => 'Account not found'
            ]);
        }

            
        $unlockDuration = $user->unlockDuration;

        // check if account is locked
        if ($unlockDuration && strtotime($unlockDuration) > time()) {
            return response([
                'message' => 'Account is locked',
            ], 401);
        }

        
        // Check if password is not correct
        else if (!Hash::check($fields['password'], $user->password)) {

            $attempts = $user->logAttempts + 1;

            
            
            if ($attempts >= $retryLimit) {
                // Lock user account after reach retry limit
                $user->update([
                    'unlockDuration' => date('Y-m-d H:i:s',time() + 15 * 60) // unlock after 10 minutes
                ]);

                return response([
                    'message' => 'Account has been locked',
                ], 401);
            }

            // Keep track of failed attempts
            $user->update([
                'logAttempts' => $attempts
            ]);

            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // if this section is reached, it means unlockDuration has expired or
        // User credentials are valid

        // reset logAttempts to 0
        $user->update([
            'logAttempts' => 0
        ]);

        auth()->login($user);
        $token = $user->createToken('myApp')->plainTextToken;

        return response([
            'user' => $user,
            'access_token' => $token
        ]);
        
    }


    public function credential()
    {
        $login = request()->input('usermail');
        $field = ctype_digit($login) ? 'phone' : 'email';
        request()->merge([$field => $login]);
        return $field;
    }




    public function login()
    {
        if (session('otp_user_id')) {
            return redirect('/otp');
        }
        return view('pages.auth.login');
    }

    public function register(Request $request)
    {
        $invitation = AcademicSet::getSetFromURL();
        if ($request->has('invite') && !$invitation) {
            abort(403, 'Registeration link has expired or revoked');
        }

        $jointoken = null;
        $title = 'Registration Form';

        if ($invitation) {
            $title =  "Joining {$invitation->name}";
            $jointoken = $request->input('invite');
        }
        return view('auth.register', compact('jointoken', 'invitation', 'title'));
    }


    
    public function doLogout(Request $request) {

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out');
    }



    public function doRegister(Request $request)
    {

        $formFields = $request->validate([
            'name' => 'required|regex:/^\s*([a-zA-Z]+)\s+([a-zA-Z]+)\s*([a-zA-Z]+)?\s*$/',
            'gender' => 'in:female,male',
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed'],
            'phone' => 'sometimes|regex:/^\s*\d+\s*$/',
            'checkpolicy' => 'required',
            'role' => 'sometimes|in:admin,advisor,admin',
            'regno' => 'sometimes|regex:/^\s*\d+\s*$/'
        ], [
            'name.regex' => 'Requires only alphabet characters',
            'checkpolicy.required' => 'You must accept terms and conditions to proceed',
            'phone.regex' => 'Enter a valid phone number',
            'role.in' => 'You selected an invalid role',
            'regno.regex' => 'Reg Number of be a number'
        ]);

        // if invitation token exists add student to the set
        if ($request->has('jtoken')) {
            $set = AcademicSet::where('token', $request->input('jtoken'));
            if ($set) {
                $formFields['set_id'] = $set->first()->id;
            }
        }

        list($firstname, $lastname) = preg_split('/\s+/', $formFields['name']);

        $formFields['username'] = $this->generateUsername($firstname, $lastname);

        // Remove white spaces 
        $formFields = array_map(fn ($value) => trim($value), $formFields);

        $formFields['password'] = bcrypt($formFields['password']);
        request()->merge($formFields);

        $user = User::saveUser($formFields);

        auth()->login($user);

        return redirect('/')->with('message', 'Account Created');
    }

    public static function store(string $role = 'student')
    {
        if (!in_array($role, ['student', 'advisor', 'admin'])) {
            throw new \Exception('Invalid Role');
        }

        $request = request();

        $formFields = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'], // Rule::unique('users')],
            'phone' => 'required',
            'password' => 'sometimes|confirmed',
            'set_id' => 'sometimes'
        ]);

        $user = new User();
        $userData = $request->only($user->getFillables());

        $class = LoginController::class;
        $loginController = new $class();



        if (auth()->check() && auth()->user()->role !== 'student') {
            $userData['created_by'] = auth()->id();
        }

        if (!$request->has('password')) {
            $formFields['password'] = $request->input('phone');
        }
        $userData['role'] = $role;

        $userData['password'] = bcrypt($formFields['password']);
        list($firstname, $lastname) = preg_split('/\s+/', $formFields['name']);

        $username = $loginController->generateUsername($firstname, $lastname);

        $userData['username'] = $username;


        $user = User::create(array_map(fn ($value) => trim($value), $userData));

        $instance = match ($role) {
            'admin' => new Admin(),
            'advisor' => new Advisor(),
            'student' => new Student(),
            default => null,
        };





        if ($instance && $user) {

            if ($fillables = $instance?->getFillables()) {
                foreach ($fillables as $field) {
                    if ($request->has($field)) {
                        $instance->{$field} = $request->input($field);
                    }
                }
            }

            if ($request->hasFile('image')) {
                $instance->image = $request->file('image')->store('pic', 'public');
            }




            $instance->id = $user->id;
            $instance->save();
            $back = 'profile.' . $role;

            return redirect()->route($back, compact('username'))->with('message', strtoupper($role) . ' account added');
        }
    }

    public function doLogin(Request $request)
    {
        $username = $this->credential();
        $callbackUrl = $request->callbackUrl;
        
        
        
        $request->validate([
            'usermail' => 'required',
            $username => 'required',
            'password' => 'required',
        ]);

        $user = User::where($username, $request->input($username))?->first();
        $logAttempts = 0;

        $exists = $user?->exists();


        if ($exists) {
            $logAttempts = $user->logAttempts;
            $logAttempts++;
        }

        $credentials = $request->only($username, 'password');
        $limit = 5;

        if (($logAttempts + 1) === $limit) {
            //session()->set()
        }

        if ($logAttempts >= 5) {
            return back()->withErrors(['login_info' => 'Account has been locked']);
        } elseif (auth()->attempt($credentials, $request->filled('remember'))) {
            $auth = Auth::user();

            // Check if the user is trying to login with a new device
            // if it's a new device, log him/out out the temporary save his session
            // take the user to otp page
            if (!Device::check()) {

                $otp = 111111;

                Session()->put('otp', $otp);
                Session()->put('otp_user_id', auth()->id());
                auth()->logout();
                return redirect('/otp');
            }
            
            $token = $auth->createToken($auth->role)->plainTextToken;

            Session()->put('tokenkey', $token);
            $request->session()->regenerate();


            $user->update(['logAttempts' => 0]);
            

            if ($callbackUrl) {
                return redirect($callbackUrl);
            }

            return redirect('home')->with('success', 'You have been logged in successfully.');
        } else if ($exists) {
            $user->update(compact('logAttempts'));
        }


        return back()->withErrors(['login_info' => 'Invalid credentials']);
    }

}
