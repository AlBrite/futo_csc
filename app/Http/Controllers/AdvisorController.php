<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Advisor;
use App\Models\AcademicSet;
use Illuminate\Http\Request;

class AdvisorController extends Controller
{
    

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

    public function classlist() {
        return view('advisor.classlist');
    }

   
    public function profile(Request $request, string $username)
    {
        $advisor = User::where('username', $username)?->first();


        return view('advisor.profile', compact('advisor'));
    }
}
