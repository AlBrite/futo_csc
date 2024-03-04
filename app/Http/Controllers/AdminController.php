<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Advisor;
use App\Models\AcademicSet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\LoginController;
use App\Models\Admin;
use App\Models\Course;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Student;
use Illuminate\Support\Arr;

class AdminController extends Controller
{
    public function dashboard()
    {
        

        return view('admin.dashboard');
    }





     /**
     * Admin updates students information
     */

     public function update_advisor(Request $request, bool $is_api = false) {

        $formFields = $request->validate([
            'advisor_id' => 'required',
            'firstname' => 'sometimes',
            'lastname' => 'sometimes',
            'middlename' => 'sometimes',
            'email' => 'sometimes|email',
            'phone' => 'sometimes',
            'birthdate' => 'sometimes',
            'entryMode' => 'sometimes',
            'set_id' => 'sometimes',
            'gender' => 'sometimes',
            'image' => 'sometimes',

            'staff_id' => 'sometimes'
        ]);


        if ($name = User::getFullnameFromRequest()){
            $formFields['name'] = $name;
        }

        $currentAccount = Advisor::where('id', $request->advisor_id)->with('user')->get()->first();

        if (!$currentAccount) {
            return redirect()->back()->with('error', 'Advisor Account does not exist');
        }
        
        // If email is among the fields to be updated but it's the same as the current email
        // Unset the field
        if (array_key_exists('email', $formFields) && $formFields['email'] == $currentAccount->user->email) {
            unset($formFields['email']);
        }
        if ($image = UploaderController::uploadFile('image')) {
            $formFields['image'] = $image;
        }
        

        $currentAccount->user->update($formFields);
        $currentAccount->update($formFields);

        return redirect()->back()->with('success', 'Student account updated successfully');

    }
    

    /**
     * Admin updates students information
     */

    public function update_student(Request $request, bool $is_api = false) {
        
        $formFields = $request->validate([
            'firstname' => 'sometimes',
            'lastname' => 'sometimes',
            'middlename' => 'sometimes',
            'email' => 'sometimes|email',
            'phone' => 'sometimes',
            'birthdate' => 'sometimes',
            'entryMode' => 'sometimes',
            'set_id' => 'sometimes',
            'session' => 'sometimes',
            'level' => 'sometimes',
            'gender' => 'sometimes',
            'image' => 'sometimes',
            'reg_no' => 'required'
        ]);

        if ($name = User::getFullnameFromRequest()){
            $formFields['name'] = $name;
        }

        $currentAccount = Student::where('reg_no', $request->reg_no)->with('user')->get()->first();

        if (!$currentAccount) {
            return redirect()->back()->with('error', 'Student Account does not exist');
        }


        // If email is among the fields to be updated but it's the same as the current email
        // Unset the field
        if (array_key_exists('email', $formFields) && $formFields['email'] == $currentAccount->user->email) {
            unset($formFields['email']);
        }
        if ($image = UploaderController::uploadFile('image')) {
            $formFields['image'] = $image;
        }
        

        $currentAccount->user->update($formFields);
        $currentAccount->update($formFields);

        return redirect()->back()->with('success', 'Student account updated successfully');

    }

    /**
     * Saves Admin Account information into the database
     */
    public function store(Request $request)
    {
        $role = 'admin';


        $firstname = $request->get('firstname', '');
        $lastname = $request->get('lastname', '');
        $middlename = $request->get('middlename', '');

   
        // Validate user inputs against list of rules
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'phone' => 'required',
            'password' => 'sometimes|confirmed',
            'set_id' => 'required',
            'session' => 'sometimes'
        ]);

        
        // instantiate User object
        $user = new User();
        
        
        // concatenate the firstname, lastname and middlename to for fullname
        $formFields['name'] = Arr::join([$firstname, $lastname, $middlename], ' ');
    
        // Assigned the id of the account that created the user
        if (auth()->check() && auth()->user()->role !== 'student') {
            $formFields['created_by'] = auth()->id();
        }

        // Make phone number the password if no password is provided
        if (!$request->has('password')) {
            $formFields['password'] = $request->input('phone');
        }
        $formFields['role'] = $role;

        $formFields['password'] = bcrypt($formFields['password']);

        // Add the new account to User model for authe
        $user = User::create($formFields);


        // Upload image if image is selected
        if ($uploadedFile = UploaderController::uploadFile('image', 'pic')) {
            $formFields['image'] = $uploadedFile;
        }


        $formFields['id']= $user->id;
        Admin::create($formFields);

        return redirect()->back()->with('message', strtoupper($role) . ' account added');
        
    }


    public function route(string $page) {
        return view("pages.admin.$page");
    }
    
}
