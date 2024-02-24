<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DBExportController;
use App\Models\{Student,Course, Result, AcademicRecord, AcademicSet, Admin, Enrollment};
use App\Http\Controllers\ {
    AuthController,
    ModeratorController,
    CourseController,
    ResultController,
    AdminController,
    AdvisorController,
    AnnouncementController,
    ResultsController,
    StudentController,
    TodoController,
    UserController
};
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/info', function() {
    echo phpinfo();
});
Route::get('/generate', function() {
    $enrollments = Enrollment::with('course')->get();

    $records = [];

    foreach($enrollments as $enrollment) {

        $score = mt_rand(34, 100);
        $exam = mt_rand(20, $score);
        $remain = $score - $exam;

        $lab = ceil($remain / 3);
        if ($lab == 0) {
            $lab = ceil($remain / 2); 
        }
        $test = $remain - $lab;


        $course_id = $enrollment->course_id;
        $reg_no = $enrollment->reg_no;
        $level = $enrollment->level;
        $session = $enrollment->session;
        $semester = $enrollment->semester;

        $records[] =  compact('score', 'course_id','reg_no',  'level', 'lab', 'exam', 'test', 'session', 'semester');


    }

    echo json_encode($records);exit;
   /*
 
    id: 1,
    course_id: 1,
    reg_no: "20181121585",     
    grouping_id: 101,
    semester: "harmattan",     
    session: "2018/2019",      
    level: 100,
    created_at: "2024-02-20 14:39:31",
    updated_at: "2024-02-20 14:39:31",
    course: App\Models\Course {#6217
      id: 1,
      name: "Elementary Mathematics I",
      code: "MTH 101",
      outline: ,
      mandatory: 1,
      grouping_id: 101,        
      semester: "harmattan",   
      level: "100",
      exam: 3,
      test: 1,
      practical: 0,
      units: 4,
      prerequisite: 0,
      image: null,
      created_at: "2024-02-20 14:39:31",
      updated_at: "2024-02-20 14:39:31",
    },
    student: App\Models\Student {#6198
      id: 3,
      set_id: 1,
      created_by: null,        
      reg_no: "20181121585",   
      phone: null,
      birthdate: null,
      address: null,
      gender: "male",
      level: 500,
      image: null,
      blood_group: null,       
      genotype: null,
      religion: null,
      lga: null,
      state: null,
      country: null,
      created_at: "2024-02-20 14:39:31",
      updated_at: "2024-02-20 14:39:31",
      gpa: null,
      cgpa: null,
    },
  }

   */
    

    echo json_encode($record);exit;

    $readData = function($records, $items) {
        $data = [];

        foreach($records as $n=>$record) {
            $inData = [];
            foreach($items as $item) {
                if (isset($record->$item)) {
                    $data[$n][$item] = $record->$item;
                }
            }
        }
        return $data;

    };
    $courses = Enrollment::get();
    $data = [];

    $record = $readData($courses, ['course_id','reg_no','semester', 'session', 'level', 'semester', 'level', 'unit', 'exam', 'practical', 'test', 'prerequisite', 'grouping_id']);


    foreach($record as $course) {
        $record = Course::where('id', $course['course_id'])->get()->first();
       $data[] = array_merge($course, ['grouping_id' => $record->grouping_id]);
       //$record->update();
       
    }
    echo json_encode($data);
    exit;
    // foreach($courses as $course) {
    //         dd($course);
    //         $data[] = [
    //             "name" => $course->name,
    //             "code" => $course->code,
    //             "outline" => $course->outline,
    //             "mandatory" => $course->mandatory,
    //             "semester" => $course->semester,
    //             "level" => $course->level,
    //             "unit" => $course->unit,
    //             "exam" => $course->exam,
    //             "practical" => $course->
    //             "test" => 1
    //             "prerequisite" => 0
    //             "created_at" => "2024-02-11 13:41:53"
    //             "updated_at" => "2024-02-11 13:41:53"
    //             "grouping_id
    //         ]
    //         $data[] = [
    //             "course_id" => $course->course_id,
    //             "reg_no" => $course->reg_no,
    //             "semester" => $course->semester,
    //             "level" => $course->level,
    //             "session" => $course->session
    //         ];
    //     }
    //     echo json_encode($data);exit;

    // $results = Result::get();
   
    $records = [];

    foreach($results as $result) {
        $courses = Enrollment::where('id', $result->course_id)->get();
    $data = [];

        foreach($courses as $course) {
            $data[] = [
                "course_id" => $course->course_id,
                "reg_no" => $course->reg_no,
                "semester" => $course->semester,
                "level" => $course->level,
                "session" => $course->session
            ];
        }
        echo json_encode($data);exit;
        
        $records[] = [
    "score" => $result->score,
    "course_id" => $result->course_id,
    "reg_no" => $result->reg_no,
    "level" => $course->level,
    "semester" => $course->semester
        ];
        // $level = $course->level;
        // $semster = $course->semester;
        // $user_id = $user->id;
        // $records[] = [
        //     'user_id' => $user_id,
        //     'course_id' => $course_id,
        //     'reg_no' => $reg_no,
        //     'semester' => $semster,
        //     'level' => $level
        // ];
    }
    echo(json_encode($records));
    exit;
    // foreach($users as $user) {


       

    

    // exit;
    $reg_no = 2018111780;

    $courses = json_decode(file_get_contents(__DIR__ . '/../public/js/courses.json'), true);
    $courses = Course::get();
    
   
    $results = [];
    foreach($users as $user) {
        $result = [];
        $reg_no = $user->reg_no;
        foreach($courses as $course) {
            $course_id = $course->id;
            $score = mt_rand(39, 100);
            
            $options = [true, false, true, true, true, false];
            $optional = $options[mt_rand(0, count($options)-1)];
            if ($course['mandatory'] || $optional) {
                $result[] = compact('score', 'course_id', 'reg_no');
            }
        }
    
        $results[] = $result;
    }

    echo json_encode($results);
        




    
});

// Routes for all users

Route::get('/', function(){
    if (auth()->check()) {
        return redirect('/home');
    }
    else {
       return redirect()->route('login');
    }
});


Route::get('/@{username}', [AuthController::class, 'profile'])->name('profile');





// Routes for unauthenticated users
Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/dologin', [AuthController::class, 'doLogin']);


});





// Routes for authenticated users
Route::middleware('auth')->group(function(){
   Route::get('/home', [AuthController::class, 'dashboard'])
        ->name('home');

   Route::get('/logout', [AuthController::class, 'doLogout'])
        ->name('logout');

   Route::get('/classlist', [AdvisorController::class, 'classlist'])
        ->name('view.class_list');
   
  
   

   
   Route::post('/add_student', [ModeratorController::class,'addStudent'])
        ->name('add.student');
   
   Route::get('/courses', [CourseController::class, 'index'])
        ->name('view.courses')
        ->middleware('role:mod');



  

    

    Route::get('/course/{course}', [CourseController::class, 'show'])
        ->name('view.course');

   Route::get('/results', [ResultController::class,'index'])->name('view.results');

   Route::get('/student/register-courses', [CourseController::class, 'registerCourse'])
        ->middleware('role:student')
        ->name('register.course');

    



    Route::post('/admin/addAdmin', [AdvisorController::class, 'addAdmin'])
        ->name('add.admin')
        ->middleware('role:admin');

    Route::post('/admin/addAdvisor', [AdminController::class, 'addAdvisor'])
        ->name('add.advisor')
        ->middleware('role:admin');

    Route::get('/admin/addAdvisor', [AdminController::class, 'addAdvisorForm'])
        ->name('add.advisorForm')
        ->middleware('role:admin');

    Route::post('/setExamCommencementDate', [ModeratorController::class, 'setExamCommencementDate'])
        ->name('set.examDate')
        ->middleware('role:mod');



    Route::post('/result/import', [ResultController::class, 'import'])
        ->name('result.import')
        ->middleware('role:mod');

    Route::get('/result/import', [ResultController::class, 'importForm'])
        ->name('result.form')
        ->middleware('role:mod');
        
    Route::post('/academicSet/add', [AdminController::class, 'addAcademicSet'])
        ->name('add.academic_set')
        ->middleware('role:admin');

    Route::get('/display_results', function(){
        $request = request();
        $course = $request->get('course');
        $session = $request->get('session');
        $semester = $request->get('semester');
        $class_id = $request->get('class_id');

        $active_user = auth()->user();

        $path = 'course-result';
        if (!$session || !$semester || !$course) {
            abort(403);
        }
        if ($active_user->role === 'admin') {
            if (!$class_id) {
                return redirect()->back()->with('message', 'Class is required');
            }
            $class = AcademicSet::find($class_id);
            $classes = Admin::academicSets();
        }
        else {
            $class = $active_user->academicSet;
            $classes = [];
        }


        if ($course === 'all') {
            $path = 'all-semester-courses-result';
        } 
        
        return view("pages.advisor.$path", compact('course', 'semester', 'session', 'class', 'classes'));
        

    })->middleware('role:mod');

   

    Route::get('/academicSet/{set}', [AcademicSetController::class, 'show'])
        ->name('view.academic_set')
        ->middleware('role:admin');




    ////////////////////////////////////////////////////////////////
    //New ROUTES

    Route::get('/courses', fn()=>view('pages.student.courses'))->middleware('role:student');
    Route::get('/course-registration', fn()=>view('pages.student.course-registration'))->middleware('role:student');

    Route::get('/course-registration-details', [CourseController::class, 'viewEnrollments'])->name('view.enrollment')->middleware('role:student');
    
    Route::get('/course-registration-borrow-courses', fn()=> view('pages.student.course-registration-borrow-courses'))->middleware('role:student');
    
    Route::get('/results', [StudentController::class, 'show_results'])->middleware('role:student');
    Route::get('/settings', fn()=>view('pages.student.settings'));
    Route::get('/profile', fn()=>view('pages.'.auth()->user()->role.'.profile'));

    Route::get('/class', fn()=>view('pages.advisor.class'))->middleware('role:advisor');

    Route::get('/advisor/{page}', fn($page) => view('pages.advisor.'.$page))->middleware('role:advisor');

    Route::get('/calender', fn() => view('components.calendar'));


    Route::post('/updateprofile', [UserController::class, 'updateProfile']);

    Route::get('/moderator/results', function(Request $request) {
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


    })->middleware('role:mod');

    Route::get('/admin/{page}', fn($page) => view("pages.admin.$page"))->middleware('role:admin');

    Route::get('/courses/add', [CourseController::class, 'addCourseForm'])
    ->name('add.course')->middleware('role:admin');

    Route::get('/moderator/add-results', fn() => view('results.add-results'))->middleware('role:admin');

        
    Route::get('/admin/course/{course}', [CourseController::class, 'showForAdmin'])
        ->name('admin-view.course')->middleware('role:admin');

    Route::get('/transcripts', [ModeratorController::class, 'view_transcripts'])
        ->name('list.transcript')
        ->middleware('role:advisor');
   
   Route::post('/generate_transcripts', [ModeratorController::class, 'loadTranscript'])
        ->name('generate.transcript');
   
   Route::get('/generate_transcripts', fn()=>redirect()
        ->route('list.transcript'));


    Route::get('/test', fn()=>view('pages.test'));

    
    Route::post('/todo/add', [TodoController::class, 'store']);

    Route::post('/import-results', [ResultsController::class, 'uploadExcel'])->name('import.results');
    Route::get('/upload-form', fn()=>view('pages.advisor.upload-form'));

    Route::post('/announcement/add', [AnnouncementController::class, 'add'])->middleware('role:mod');

    
    Route::get('/announcement', fn() => view('pages.announcement'))->middleware('role:mod');

    // Student: Course Registeraton: Add courses to database
    Route::post('/courses/insert', [CourseController::class, 'doRegister'])
        ->middleware('role:student')
        ->name('insert.courses');

    // Admin: add new course to database
    Route::post('/courses/store', [CourseController::class, 'store'])
    ->middleware('role:admin')
    ->name('store.course');

    // Admin: update course 
    Route::post('/courses/update', [CourseController::class, 'updateCourse'])
    ->middleware('role:admin')
    ->name('update.course');

    // Export Database for Development
    Route::get('/export', [DBExportController::class, 'exportToJson']);

    // Display For uploading of results
    Route::get('/upload-results', fn()=> view('pages.advisor.upload-results'))->middleware('role:mod');

    // Add new student account
    Route::post('/admin/student/add', [AdminController::class, 'addStudent'])
        ->middleware('role:mod');
    
    // Add new advisor account
    Route::post('/admin/advisor/add', [AdminController::class, 'addAdvisor'])
        ->middleware('role:admin');

    // Update account in admin mode
    Route::post('/admin/student/update', [AdminController::class, 'updateStudent'])
        ->middleware('role:admin');

    Route::get('/popups/{page}', function($page){
        $page = str_replace('/', '.', $page);


        return view('popups.'.$page);
    });

});