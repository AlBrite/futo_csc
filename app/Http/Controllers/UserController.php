<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function doLogout(Request $request) {
        dd('Hello');
        dd([$request]);
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out');
    }


    public function update(Request $request) {

        
        $user = User::findOrFail(auth()->id());
        

        $validator = Validator::make($request->all(), [
            'configure' => 'required',
            'oldPassword' => [
                'sometimes',
                
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Your old password is not correct.');
                    }
                }
            ],
            'password' => [
                'sometimes', 'min:6', 'confirmed', 'different:oldPassword'
            ]
        ],[
            'password.confirmed' => 'Passwords do not match',
            'password.min' => 'Password must not be less than :min characters'
        ]);
        
        $configure = $request->configure;
        
        if($validator->fails()) {
            session()->flash($configure, $validator->errors()->first()); // Use 'error' as key and first error message
            return redirect()->back();
          }


        if ($configure === 'password') {
            
            $user->fill([
                'password' => Hash::make($validator['password'])
            ])->save();
    
            session()->flash('passsword', 'Your password has been updated successfully.');
            return redirect()->back();
        }

      

        dd($request);

       
    }



    /**
     * Display user profile picture
     * If user has't uploaded profile picture
     * displays picture based on user gender
     */

    public function display_picture(User $user) {
        $image = public_path(match (true) {
            $user->profile?->gender === 'female' => 'images/avatar-f.png',
            $user->profile?->gender  === 'male' => 'images/avatar-m.png',
            default => 'images/avatar-u.png',
        });
       
        if ($user->profile->image && is_file(storage_path($user->profile->image))) {
            $image = storage_path($user->profile->image);
        }
        if (!file_exists($image)) {
            abort(404);
        }

        $mime = mime_content_type($image);
        $filesize = filesize($image);

        header('Content-Type: '.$mime);
        header('Content-Length: '.$filesize);

        readfile($image);
        exit;
    }


    /***Displays dashboard view to user based on role*/
    public function dashboard() {
        return view("pages." . auth()->user()->role . ".dashboard");
    }


    public function updateProfile(Request $request) {
       
        $formFields = $request->validate([
            'firstname' => 'sometimes|regex:/^([a-zA-Z]+)$/',
            'lastname' => 'sometimes|regex:/^([a-zA-Z]+)$/',
            'middlename' => 'sometimes|regex:/^([a-zA-Z]+)$/',
            'email' => 'sometimes|email', // Rule::unique('users')],
            'phone' => 'sometimes',
            'password' => 'sometimes|confirmed',
            'oldPassword' => [
                'sometimes',
                function($attribute, $value, $fail) {
                  
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Old password didn\'t match');
                    }
                }
            ], // Rule::unique('users')
            'address' => 'sometimes',
            'level' => 'sometimes'
        ]);
        

        $updatable = Arr::except($formFields, ['firstname', 'lastname', 'middlename', 'oldPassword']);
        $name = null;
        if($request->has('firstname')) {
            $name = $request->firstname;
        }
        if($request->has('middlename')) {
            $name .= ' '.$request->middlename;
        }
        if($request->has('lastname')) {
            $name .= ' '.$request->lastname;
        }
        if ($name) {
            $updatable['name'] = $name;
        }

        
       
        
        
        $authUser = auth()->user();
        
        $instance = $authUser->profile;
        
        $fillable = $instance->getFillable();

        if ($request->has('password')) {
            $updatable['password'] = bcrypt($formFields['password']);
        }
        
        if ($request->hasFile('profileImageSelect')) {
            $instance->image = $request->file('profileImageSelect')->store('pic', 'public');
        }
       foreach($updatable as $column => $value) {
        if (in_array($column, $fillable)) {
            $instance->$column = $value;
        }
       }

        
        $instance->update();

        $authUser->update($updatable);


        return back()->with('success','Profile UPdated');

    }


    /**
     * Show setting page to users
     */
    public function show_settings() {
        return view('pages.student.settings');
    }

    /**
     * Shows user profile page
     */

    public function show_profile() {
        return view('pages.'.auth()->user()->role.'.profile');
    }

}
