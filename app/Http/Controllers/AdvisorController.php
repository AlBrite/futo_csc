<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Advisor;
use App\Models\AcademicSet;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AdvisorController extends Controller
{

    public function index_admin() {
        return view('pages.admin.advisors');
    }

    public function add() {
        return view('pages.admin.add-advisor');
    }

    public function update_admin(Request $request) {
        $request->validate([
            'advisor_id' => 'required'
        ]);
        $advisor_id = $request->advisor_id;
        $advisor = Advisor::find($advisor_id)?->get()?->first();
        if(!$advisor) {
            return redirect()->back()->with('error', 'Advisor not found');
        }
        return view('pages.admin.edit-advisor', compact('advisor'));
    }

    public function insert() {
        return view('admin.addadvisor');
    }

    
    

    public function getAdvisor(Request $request) {
        $advisor_id = $request->advisor_id;
        

        if (!$advisor_id) {
            return response()->json(['error' => 'Student Id is required'])->status(403);
        }

        $advisor = Advisor::where('id', '=', $advisor_id)->get();
        
        if (!$advisor) {
            return response()->json(['error' => 'Advisor not found'])->status(404);
        }
        $advisor = $advisor->first();
        $class = $advisor->class;
        $advisor->studentsCount = $class->students()->count();
        $students = $class->students()->with('user')->paginate(3);

        $allStudents = [];

        foreach($students as $student) {
            $currentStudent = $student;
            $currentStudent->picture = $student->picture();
            $allStudents[] = $currentStudent;
        }
        $advisor->students = $allStudents;
        $advisor->image = $advisor->picture();
        $advisor->user->fullname;

        return $advisor;
    }

    public function home() {
        $var = [
            'number_of_students_in_class' => 500,
        ];
        $sets = Advisor::find(auth()->id())->first()->class()->latest()->simplePaginate(6);
        dd($sets);

        return view('advisor.home', $var);
    }

    public function makeCourseRep(Request $request)
    {
        $request->validate([
            'set_id' => 'required',
            'course_rep' => 'required'
        ]);
        $set = AcademicSet::whereNotNull('course_rep');
        $set->update(['course_rep' => null]);
        AcademicSet::where(['id' => $request->input('set_id')])
            ->update(['course_rep' => $request->input('course_rep')]);
        return back()->with('message', 'Changed course rep');
    }

    

   
    public function profile(Request $request, string $username)
    {
        $advisor = User::where('username', $username)?->first();


        return view('advisor.profile', compact('advisor'));
    }



    public function store(Request $request)
    {
        // Validate user inputs against list of rules
        $formFields = $request->validate([
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'sometimes|confirmed',
            'birthdate' => 'required',
            'set_id' => 'required'
        ]);

        $role = 'advisor';
        $set_id = $request->get('set_id');

        $class = AcademicSet::find($set_id);


      

        $firstname = $request->get('firstname', '');
        $lastname = $request->get('lastname', '');
        $middlename = $request->get('middlename', '');

   
        

        
        // instantiate User object
        $user = new User();
        
        // concatenate the firstname, lastname and middlename to for fullname
        $formFields['name'] = Arr::join([$firstname, $lastname, $middlename], ' ');
    
        // Assigned the id of the account that created the user
        $formFields['created_by'] = auth()->id();
        

        // Make phone number the password if no password is provided
        if (!$request->has('password')) {
            $formFields['password'] = $request->input('phone');
        }

        $formFields['role'] = $role;

        $formFields['password'] = bcrypt($formFields['password']);


        // Add the new account to User model for authe
        $user = User::create($formFields);

           

        if ($uploadedFile = UploaderController::uploadFile('image', 'pic')) {
            $formFields['image'] = $uploadedFile;
        }


        $formFields['id'] = $user->id;
        Advisor::create($formFields);

        $class->advisor_id = $user->id;
        $class->save();


        return redirect()->back()->with('message', "Class Advisor's account added successfully");
    }


    public function route(string $page) {
        return view('pages.advisor.'.$page);
    }
}
