<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ResultsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Result;
use App\Http\Controllers\Controller;
use App\Models\AcademicSet;
use App\Models\Admin;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Arr;

class ResultsController extends Controller
{

    public function uploadExcel(Request $request)
    {
        $matcher = [
            'Reg No.' => 'reg_no',
            'LAB' => 'lab',
            'TEST' => 'test',
            'EXAM' => 'exam',
            'TOTAL' => 'score'
        ];

        $request->validate([
            "level" => "required",
            "semester" => "required",
            "course" => "required",
            "session" => "required"
        ]);
        $level = $request->level;
        $semester = $request->semester;
        $course = $request->course;
        $session = $request->session;


        $file = $request->file('result');
        
        
        $data = Excel::toArray([], $file);

        
        // Store the data in the database
        // For example:
        $n = 0;
        

        $results = [];

        $foundRow = false;
        $retrieveColumns = [];
        
        foreach ($data[0] as $rowNumber => $row) {


            foreach($row as $col) {
                if (array_key_exists($col, $matcher)) {
                    foreach($row as $n => $column) {
                        if (!array_key_exists($column, $matcher)) {
                            continue;
                        }
                        $retrieveColumns[$n] = $matcher[$column];
                    }
                    $foundRow = $rowNumber;
                    break;
                }
            }
        
        }

        if ($foundRow === false) {
            return redirect()->back()->with('error', 'Failed to scan results');
        }
        
        
        $results = array_splice($data[0], $foundRow + 2);

        
        $newResult = [];

        
        foreach($results as $result) {
            $ResultDB = new Result();
            foreach($retrieveColumns as $index => $retrieved) {
                $newResult[$retrieved] = $result[$index];
                $ResultDB->{$retrieved} = $result[$index];
                $ResultDB->level = $level;
                $ResultDB->semester = $semester;
                $ResultDB->course_id = $course;
                $ResultDB->session = $session; 
            }
            // Store into the database table Results 
            $ResultDB->save();
        }


            
        return redirect()->back()->with('success', count($results).' results uploaded and processed successfully');
    }

    /**
     * This method routes the page 
     * for lecturer to add results manually
     */

    public function lecturer_add_result(Request $request) {
        return view('results.add-results');
    }


    public function approveResults(Request $request) {
        dd($request);
    }


    public function spreadsheet(Request $request) {
        return view('pages.lecturer.upload-result');
    }


    

    /**
     * SHow's form to insert results into the database table
     */

    public function insert() {
        return view('pages.advisor.upload-form');
        //fn()=> view('pages.advisor.upload-results')
    }

    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ResultsImport, $request->file('file'));

        return redirect()->back()->with('success','Results imported successfully.');
    }

    public function save_results(Request $request) {
        $request->validate([
            'results' => 'required',
            'course_id' => 'required',
        ]);
        try {

            $results = $request->results;
            $course_id = $request->course_id;
            $extracts = ['exam', 'score', 'lab', 'reg_no'];

            foreach($results as $n => $result) {
                $queue = new Result();
                foreach($extracts as $extract) {
                    $queue->$extract = $result[$extract];
                }
                $queue->course_id = $course_id;
                $queue->uploaded_by = auth()->id();
                $queue->save();
            }
            
            return response([
                'message' => 'Results uploaded successfully'
            ]); 
        } catch(\Exception $e) {
            return response([
                'message' => $e->getMessage().'Failed to upload results'
            ], 401);
        }
    }

    public function addResult(Request $request) {

        $formField = $request->validate([
            // required fields
            'course_id' => 'required',
            'reg_no' => 'required',
            'semester' => 'required',
            'session' => 'required',
            'level' => 'required',

            // for now these fields are optional         
            'grade' => 'sometimes',
            'exam' => 'sometimes',
            'test' => 'sometimes',
            'grade' => 'sometimes',
            'lab' => 'sometimes',
            'remark' => 'sometimes',
            'score' => 'sometimes',
        ]);
        $semester = $request->semester;
        $level = $request->level;
        $session = $request->session;
        $reg_no = $request->reg_no;
        $course_id = $request->course_id;


        $course = Enrollment::where('course_id', $course_id)
            ->where('semester', $semester)
            ->where('session', $session)
            ->where('reg_no', $reg_no)
            ->where('level', $level)
            ->get()
            ->first();

        $result = Result::where('course_id', $course_id)
            ->where('semester', $semester)
            ->where('session', $session)
            ->where('reg_no', $reg_no)
            ->get()
            ->first();

        
        if (!auth()->check()) {

            return response([
                'message' => 'You need to login to be able to access this page.'
            ], 403);

        }


        if (auth()->user()->role !== 'admin' && $result && $result->status !== 'APPROVED') {

            return response([
                'message' => 'Result cannot be updated, contact admin for help'
            ], 403);

        }

        if (!$course) {

            return response([
                'message' => 'Course not found'
            ], 404);

        }        

        
        // If result alread exist, update
        if ($result) {
            $save = $result->update($formField);
        }
        else {
            $formField['uploaded_by'] = auth()->id();
            $save = Result::create($formField);
        }

        $result->updateCGPA();

        return compact('save');
        

        if ($save) {
            return response([
                'message' => 'Result saved successfully',
                'data' => $formField
            ]);;
        }
        
        return response([
            'message' => 'Failed to update result',
        ], 500);
    }

    public function index(Request $request){
        $course = $request->get('course');
        $session = $request->get('session');
        $semester = $request->get('semester');
        $class_id = $request->get('class_id');

        $active_user = auth()->user();
        $advisor = $active_user->advisor;
        if (!$advisor) {
            return redirect('/home')->with('error', 'You do not have permission to access this page');
        }
        $classes = $advisor->classes;

        $route = 'course-result';


        if ($course === 'all') {
            $route = 'all-semester-courses-result';
        } 
        
        return view("pages.advisor.results", compact('course', 'semester', 'session', 'classes', 'route', 'advisor'));
        

    }

    public function api_getAwaitingResults(Request $request) {
        $request->validate([
            'level' => 'required'
        ]);
        $level = $request->get('level');

        return Result::getLevelAwaitingResults($level);
    }

    




    public function awaitingResults() {
        $awaitingResults = Result::awaitingResults()->get();
       

        
        

        $results = [];

        if ($awaitingResults && count($awaitingResults) > 0) {
            foreach($awaitingResults as $n =>$result) {
                
                $level = $result['level'];
                $code = $result['code'];

                $results[$level] ??= [];
                $results[$level][$code] ??= [];

              //  dd($result);
                // $results = Arr::prepend($results, "$level.$code", $result);

        
               // $results[$level][$code][count($results[$level][$code])-1] = $result;
            }
            
        }
        //dd($results);
        return view('pages.admin.awaiting-results', compact('results'));
    }


    public function show_results(Request $request) {

    
        $user = $request->user();
        $student = $user->student;
        $sessions = Enrollment::where('reg_no', $student->reg_no)->groupBy('session')->get();
        $enrollments = $student->enrollments()->groupBy('session');
        
        
        $session = request()->session;
        $semester = request()->semester;
        $unapproved = $approved = null;
        $GPA = 0.0;
        
        if ($session && $semester) {
           

           $results = function($status) use ($student,  $semester, $session) {
                $equality = $status === 'approved' ? '=': '!=';

                $results = Enrollment::join('courses', 'courses.id', '=', 'enrollments.course_id')
                    ->where('enrollments.semester', $semester)
                    ->where('enrollments.reg_no', $student->reg_no)
                    ->leftJoin('results', function($join) use ($student){
                            $join->on('results.reg_no',    '=', 'enrollments.reg_no')
                                 ->on('results.course_id', '=', 'courses.id')
                                 ->on('results.semester',  '=', 'enrollments.semester')
                                 ->on('results.session',   '=', 'enrollments.session')
                                 ->on('results.level',     '=', 'enrollments.level');
                    })
                    ->where('status', $equality, 'COMPLETED')
                    ->where('enrollments.session', $session)
                    ->get();

                return $results;
           };

            
            $approved = $results('approved');
            $unapproved = $results('unapproved');
            
           
            $GPA = Result::calculateGPA($approved, $semester, $session);
          
        }
        

        return view('pages.student.results', compact('sessions', 'semester', 'GPA','student','session', 'approved', 'unapproved'));
    }

    /**
     * Show student results to
     * Admin and course advisor
     */

    public function index_moderators(Request $request) {
        $course = $request->get('course', '');
        $session = $request->get('session', '');
        $semester = $request->get('semester', '');
        $class_id = $request->get('class_id', '');

        $active_user = auth()->user();

        $class = null;
        $students = [];
        if ($active_user->role === 'admin') {
            if ($class_id) {
                $class = AcademicSet::find($class_id);
            }
            $classes = Admin::academicSets();
        }
        else {
            $advisor = $active_user->advisor;
            $class = $advisor->class;
            $students = $class->students;
            $classes = $advisor->classes;
        }

        return view("pages.admin.results", compact('session', 'classes', 'students', 'semester'));

    }


    /**
     * Show form for lecturer to insert student's results
     */

     public function insert_lecturers() {
        return view('results.add-results');
     }
}
