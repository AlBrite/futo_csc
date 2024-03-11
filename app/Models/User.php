<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\{
    Advisor, 
    Student,
    Admin
};
use Exception;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use stdClass;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, CanResetPassword, HasApiTokens, HasFactory, Notifiable;

  
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone',
        'department_id',
        'role',
        'unlockDuration',
        'logAttempts'
    ];

    private $namesLoaded = false;

    protected static $accounts = [
        'advisor'  => Advisor::class,
        'student'  => Student::class,
        'admin'    => Admin::class,
        'lecturer' => Lecturer::class,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        //'password' => 'hashed',
    ];




    /**
     * Get the phone number for SMS notifications.
     *
     * @return string
     */
    public function routeNotificationForNexmo()
    {
        return $this->phone;
    }



    /**
     * Get the email address for email notifications.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }



    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function getHashedPassword() {
        if (request()->has('password')) {
            $request = request();
            $password = $request->get('password');
            $old_password = $request->get('old_password');

            // check if the user is the owner, if not unset password field
            // because only the owner can change it
            $auth_id = auth()->id();
            $user_id = $this->id;

            if ($auth_id != $user_id) {
                return null;
            }
            else if (!$request->has('old_password')) {
                throw new Exception('Current password is required');
            }
            if (!Hash::compare($password, $this->password)) {
                throw new Exception('Passwords do not match');
            }
            return Hash::make($password);
        }

        return null;
    }

    public static function getFullnameFromRequest()
    {
        $request = request();
        $firstname = $request->get('firstname', '');
        $lastname = $request->get('lastname', '');
        $middlename = $request->get('middlename', '');

        if (!$firstname && !$lastname) {
            return null;
        }


        $fullname = [$firstname, $lastname, $middlename];

        return implode(' ', $fullname);
    }

    public function getFullnameAttribute($value) {
        $nameParts = explode(' ', $this->name);

        $obj = new stdClass;

        $obj->firstname = $nameParts[0];
        $obj->lastname = count($nameParts) > 1 ? $nameParts[1] : '';
        $obj->middlename = count($nameParts) > 2 ? $nameParts[2] : '';
        return $obj;
    }

   





    public function generateId($role, $prefix = null)
    {
        $prefix ??= strtoupper(substr($role, 0, 3));
        do {
            $randomNumber = mt_rand(10000, 99999);
            $uniqueId = "$prefix-$randomNumber";
        } while (User::where('unique_id', $uniqueId)->exists());

        return $uniqueId;
    }






    public static function store_user(array $data)
    {
        

        // Default rolr
        $data['role'] ??= 'student';
 
        if (!array_key_exists($data['role'], self::$accounts)) {
            return null;
        }

        $role = $data['role'];

        $account = self::$accounts[$role];
        
        // Create user account for authentification
        $authUser = User::_create($data);
        
        
        $data['id'] = $authUser->id;
        
        // Add User to role table
        $account::_create($data);
        
        return $authUser;
    }

    public static function _create($data) {
        $obj = new User();
        $data = Arr::only($data, $obj->fillable);
        return User::create($data);
    }


    public static function active() {
        return auth()->user();
    }





    public static function saveUser(array $userData = [])
    {
        $user = new User();

        if (count($userData) === 0) {
            $data = request()->only($user->fillable);
        } else {
            $data = Arr::only($userData, $user->fillable);
        }


        $user = self::create($data);

        self::register($user, $data);

        return $user;
    }


    private static function register(User $user, array $data = [])
    {

        $instance = match ($user->role) {
            'admin' => new Admin(),
            'advisor' => new Advisor(),
            default => new Student(),
        };

        if ($instance) {
            if ($fillables = $instance->getFillables()) {
                foreach ($fillables as $field) {
                    if (request()->has($field)) {
                        $instance->{$field} = request()->input($field);
                    }
                }
            }

            $instance->id = $user->id;
            $instance->save();
        }
    }

    public static function redirectToDashboardx()
    {
        $dashboard = 'login';
        if (auth()->check()) {
            $role = auth()->user()->role;

            $dashboard = $role . '.dashboard';
        }


        return redirect()->route($dashboard);
    }

   


    public function profile() {
            
        return match($this->role){
            'student' => $this->hasOne(Student::class, 'id'),
            'admin' => $this->hasOne(Admin::class, 'id'),
            'advisor' => $this->hasOne(Advisor::class, 'id', 'id'),
            'lecturer' => $this->hasOne(Lecturer::class, 'id', 'id'),
            default => null,
        };
    }
    


    public function advisor()
    {
        return $this->hasOne(Advisor::class, 'id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'id', 'id');
    }

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class, 'id', 'id');
    }
    

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id');
    }


    public static function isStudent()
    {
        return auth()->user()->role === 'student';
    }
    public static function isAdmin()
    {
        return auth()->user()->role === 'admin';
    }
    public static function isAdvisor()
    {
        return auth()->user()->role === 'advisor';
    }



   
    


    

    public function courses() {
        return $this->hasMany(AcademicSet::class)->with('_course');
    }








    public function todos() {
        return $this->hasMany(Todo::class)->orderBy('created_at', 'DESC');
    }






    public function picture() {
        $image = $this->profile->image;
        if ($image) {
            return asset($image);
        }
        
        return asset(match($this->profile->gender) {
            'female' => 'images/avatar-f.png',
            'male' => 'images/avatar-m.png',
            default => 'images/avatar-u.png',
        });
    }


    public function incrementLogAttempts() {
        $this->logAttempts++;

        $this->update([
            'logAttempts' => $this->logAttempts
        ]);
    }
    
    public function lockAccount(int $unlockDuration) {
        $logAttempts = 4;
        $this->update(compact('logAttempts', 'unlockDuration'));   
    }

    public function unlockAccount() {
        $unlockDuration = null;
        $logAttempts = 0;
        $this->update(compact('logAttempts', 'unlockDuration'));
    }


    public function isLocked() {
        return $this->unlockDuration && $this->unlockDuration > time();
    }



    
}
