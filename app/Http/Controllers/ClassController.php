<?php

namespace App\Http\Controllers;

use App\Models\AcademicSet;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    
    public function destroy(){}
    public function index(){}
    public function update(){}

    public function api_index(Request $request) {
        return AcademicSet::all();
    }

    public function add() {
        return view('pages.admin.add-class');
    }

    public function api_fetchClass(Request $request) {
        
        $class = AcademicSet::where('name', '=', $request->get('class_name'))
            ->orWhere('id', '=', $request->get('class_id'));
        return $class->get()->first();
    }



    public function show() {
        return  view('pages.advisor.class');
    }

    public function show_to_admin() {
        return view('pages.admin.classes');
    }


    public function classlist() {
        return view('advisor.classlist');
    }


    public function store(Request $request)
    {
        $formFields = $request->validate([
            'start_year' => 'required|regex:/^[0-9]+$/',
            'end_year' => 'required|regex:/^[0-9]+$/',
            'department' => 'required',
            'advisor_id' => 'sometimes|regex:/^[0-9]+$/'
        ], [
            'start_year.regex' => 'Invalid start year',
            'end_year.regex' => 'Invalid end year',
            'advisor_id.regex' => 'Invalid Advisor Id'
        ]);

        $formFields['name'] = "{$formFields['department']} {$formFields['end_year']}";

        $set = AcademicSet::create($formFields);

        return redirect()->route('view.academic_set', compact('set'));
    }
}
