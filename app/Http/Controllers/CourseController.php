<?php
/*
    create
    index
    show
    delete
    update




*/
namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Course;
use App\Models\Result;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\AcademicRecord;
use App\Models\Enrollment;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function __constructor() {
        $this->middleware('role:admin,advisor');
    }
    protected $fillable = [
        'name',
        'code',
        'semester',
        'units',
        'exam',
        'test',
        'practical',
        'prerequisites',
        'level',
        'grouping_id',
        'mandatory',
        'image',
        'outline'
    ];
    public function index()
    {
        $courses = Course::where('id', '>', 1)->paginate(15);
        return view('courses.index', compact('courses'));
    }

    



    public function registerCourse() {
        return view("student.register-courses");
    }

    

    public function registerForm()
    {
        $department = Department::myDepartment()->first();
        $levels = Department::levels();
        return view('courses.register', compact('department', 'levels'));
    }

    public function listRegisteredCourses()
    {
        $student = Student::auth();
        $user = $student->user;
        $courses = $student->courses;
        return view('courses.list-of-registered-courses', compact('courses', 'student', 'user'));
    }

    public function showForAdmin(Course $course)
    {
        
        return view('pages.admin.show-course', compact('course'));
    }

    public function show(Course $course)
    {
        
        $result = Result::where('course_id', $course->id)->where('reg_no', auth()->user()->student->reg_no)->first();
        return view('pages.student.view-course', compact('course', 'result'));
    }

    public function displayCourses(Request $request)
    {
        $request->validate([
            'level' => 'required|regex:/[1-7]([0]+){2,2}/',
            'semester' => 'required|in:rain,harmattan'
        ], [
            'level.regex' => 'Invalid Level',
            'semester.in' => 'Invalid Semester'
        ]);

        $level = $request->input('level');
        $semester = $request->input('semester');

        $title = "List of {$level} level courses";
        $courses = Department::myCoursesForLevel($level, $semester);
        return view('courses.display_available_courses', compact('courses', 'title'));
    }

    /***
     * Handles Student Course Registration
     */

    public function doRegister(Request $request)
    {
        $request->validate([
            'course' => 'required',
            'level' => 'required',
            'semester'=> 'required|in:harmattan,rain'
            
        ]);
        
       

        $requestedCourses = $request->input('course');
        $session = $request->input('session');
        $semester = $request->input('semester');
        $level = $request->input('level');

        // get the instances of all the to courses to be enrolled in
        $courses = Course::whereIn('id', $requestedCourses)->get();

        $courses_ = [];
        $user = auth()->user();

        // Student Reg Number
        $reg_no = $user->student->reg_no;
        
        $grouping_ids = [
            'harmattan' => 1,
            'rain' => 2
        ];

        foreach ($courses as $course) {
            

            $courses_[] = [
                'course_id' => $course->id,
                'reg_no' => $reg_no,
                'level' => $level,
                'semester' => $semester,
                'grouping_id' => $level + $grouping_ids[$semester],
                'session' => $session
            ];
            
        }


       
        foreach ($courses_ as $course) {
            Enrollment::create(Enrollment::getFillables($course));
        }

        return redirect("/course-registration-details?semester=$semester&level=$level")->with('success', "You have successfully registered " . count($courses_) . " courses for $session $semester Semester");
    }

    public function addCourseForm()
    {
        
        return view('pages.courses.insert');
    }


    public function createCourse(Request $request)
    {
        
        $formData =  $request->validate([
            'name' => 'required',
            'code' => ['required', Rule::unique('courses'), 'regex:/([a-zA-Z]+){3,3}\s*([0-9]+){3,3}/'],
            'semester' => 'required|in:rain,harmattan',
            'level' => 'required|in:100,200,300,400,500,600',
            'practical' => 'required|regex:/^(\d+)$/',
            'exam' => 'required|regex:/^(\d+)$/',
            'test' => 'required|regex:/^(\d+)$/',
            'prerequisites' => 'sometimes',
            'check' => 'required',
            'mandatory' => 'required|in:1,0'
        ], [
            'name.required' => 'Course Title is required',
            'code.required' => 'Couse Code is required',
            'code.unique' => 'Course Code alread exists',
            'code.regex' => 'Invalid course code',
            'semester.in' => 'Semester must be either rain or harmattan',
            'level.in' => 'Invalid level',
            'check.required' => 'You need to confirm that the data you provided are valid',
            'mandatory.in' => 'Select option',
            'image' => 'sometimes|image|max:2048', // Ensure 'image' is present and is an image file (up to 2MB)
        ]);
        
        
            $image_path = null;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $uploadedImage = $request->file('image');
                $filename = Str::random(10) . '.' . $uploadedImage->getClientOriginalExtension();
                $image_path = "public/images/$filename";
                $uploadedImage->storeAs('public/images', $filename);
                
            } else {
                // The image data is in base64 format
                $base64Image = $request->input('image');
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                $filename = Str::random(10) . '.png'; // You can adjust the file extension according to your image format
                $image_path = storage_path('app/public/images/' . $filename);
                file_put_contents($image_path, $imageData);
            }
            if ($image_path) {
                $formData['image'] = $image_path;
            }



        
        
        $data = Arr::only($formData, $this->fillable);
        $data['units'] = $request->practical + $request->exam + $request->test;
        $data['code'] = trim(strtoupper($data['code']));
        $data['name'] = ucfirst(trim($data['name']));
        dd($data);
        $data['grouping_id'] = $request->level + ($request->semester === 'rain' ? 2 : 1);
        $course = Course::create($data);


        
        return $course;
       
    }


    public function updateCourse(Request $request)
    {
        
        $formData =  $request->validate([
            'id' => 'required',
            'name' => 'required',
            'code' => ['required', 'regex:/([a-zA-Z]+){3,3}\s*([0-9]+){3,3}/'],
            'semester' => 'required|in:rain,harmattan',
            'level' => 'required|in:100,200,300,400,500,600',
            'practical' => 'required|regex:/^(\d+)$/',
            'exam' => 'required|regex:/^(\d+)$/',
            'test' => 'required|regex:/^(\d+)$/',
            'prerequisites' => 'sometimes',
            'check' => 'required',
            'mandatory' => 'required|in:1,0',
            'outline' => 'required'
        ], [
            'name.required' => 'Course Title is required',
            'code.required' => 'Couse Code is required',
            'code.unique' => 'Course Code alread exists',
            'code.regex' => 'Invalid course code',
            'semester.in' => 'Semester must be either rain or harmattan',
            'level.in' => 'Invalid level',
            'check.required' => 'You need to confirm that the data you provided are valid',
            'mandatory.in' => 'Select option',
            'image' => 'sometimes|image|max:2048', // Ensure 'image' is present and is an image file (up to 2MB)
        ]);
        
        $course = Course::find($request->id);
        
        $image_path = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $uploadedImage = $request->file('image');
            $filename = Str::random(10) . '.' . $uploadedImage->getClientOriginalExtension();
            $image_path = "public/images/$filename";
            $uploadedImage->storeAs('public/images', $filename);
            
        } 
        elseif ($request->has('image')) {
            // The image data is in base64 format
                $base64Image = $request->input('image');
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                $filename = Str::random(10) . '.png'; // You can adjust the file extension according to your image format
                $image_path = storage_path('app/public/images/' . $filename);
                file_put_contents($image_path, $imageData);
            }
            if ($image_path) {
                $formData['image'] = $image_path;
            }


        
        $data = Arr::only($formData, $this->fillable);
        $data['units'] = $request->practical + $request->exam + $request->test;
        $data['code'] = trim(strtoupper($data['code']));
        $data['name'] = ucfirst(trim($data['name']));
        $data['grouping_id'] = $request->level + ($request->semester === 'rain' ? 2 : 1);
        
        $course->update($data);


        
        if ($course) {
            return redirect("/admin/courses?level={$request->level}&semester={$request->semester}&course_id={$course->id}")->with('success', "Course has been updated successfully");
        } else {
            return redirect()->back()->with('error', 'Failed to update course');
        }
       
    }

    /**
     * Handle Creation of Course
     * Only Admin and Course advisors are allowed to Create Course
     */
    public function store(Request $request)
    {
        
        
        $course = $this->createCourse($request);

        if ($course) {
            return redirect("/admin/courses?level={$request->level}&semester={$request->semester}&course_id={$course->id}")->with('success', "Course {$request->code} has been added");
        } else {
            return redirect()->back()->with('error', 'Failed to add course');
        }

       
    }

    public function api_createCourse(Request $request){
        $course = $this->createCourse($request);
        if ($course) {
            return response()->json($course);
        } 
        return response()->json(['error' => 'Failed to Create Course'])->status(401);
    }


    public function api_getCourses(Request $request) {
        
            $request->validate([
                'level' => 'required|regex:/[1-7]([0]+){2,2}/',
                'semester' => 'required|in:rain,harmattan'
            ], [
                'level.regex' => 'Invalid Level',
                'semester.in' => 'Invalid Semester'
            ]);
        
            $level = $request->level;
            $semester = $request->semester;
        
            return Course::getCourses($level, $semester);
    }

    public function api_getEnrolledCourses(Request $request) {
        $session = $request->session;
        $semester = $request->semester;
        $request->validate([
            'session' => 'required',
            'semester' => 'required|in:rain,harmattan'
        ]);

        return Enrollment::where('session', $session)
            ->where('semester', '=', $semester)->with('course')->get()->unique('course_id');
    }

    public function getCourseById(Request $request) {
        $request->validate([
            'course_id' => 'required'
        ]);
        $course_id = $request->get('course_id');

        return Course::find($course_id);

    }




    public function student_course_details_home(Request $request) 
    {
        $course_id = $request->course_id;
        return ['course_id' => $course_id];
        
        $course = Course::where('id', $course_id)->get()->first();
        
        $code = $course->code;
        $exam = $course->exam;
        $unit = $course->unit;
        $practical = $course->practical;
        $test = $course->test;
        $prerequisite = $course->prerequisite;
        $title = $course->name;
        $mandtory = $course->mandatory;
        $semester = $course->semester;

        //distributions
        $practical_distribution = ($practical / $unit) * 100;
        $exam_distribution = ($exam / $unit) * 100;
        $test_distribution = ($test / $unit) * 100;

        $data = "
    <div class='flex center'>
        <span @click='courseOpen = false'
            class='material-symbols-rounded select-none cursor-pointer font-semibold text-3xl text-red-500'>
            close
        </span>
    </div>
    
    <div class='h-32 grid grid-cols-3 gap-2 border border-slate-300 shrink-0'>
        <img class='col-span-1 h-full object-cover' src='".asset("svg/course_image_default.svg")."'
            alt='default_course_img'>
    
        <div class='col-span-2 flex flex-col justify-center'>
            <p
                class='text-lg font-semibold text-body-800 select-none whitespace-nowrap text-ellipsis overflow-hidden'>
                $title
            </p>
            <p class='flex items-center select-none'>
                <span class='text-sm text-body-400 pr-2 border-r border-r-slate-[var(--body-300)]'>
                    $code
                </span>
                <span class='text-sm text-body-300 pl-2 border-l border-l-slate-[var(--body-300)]'>
                    $unit unit
                </span>
            </p>
        </div>
    </div>
    
    <div class='h-32 p-2 flex flex-col gap-2 shrink-0'>
        <p class='text-sm font-semibold text-body-300'>
            Marks distribution
        </p>";
        if ($practical) {
        $data .="
        <div class='flex flex-col gap-3'>
            <div class='grid grid-cols-5'>
                <span class='col-span-1 p-3 flex center font-bold bg-accent-200'>
                    $test_distribution
                </span>
                <span class='col-span-1 p-3 flex center font-bold bg-secondary-200'>
                    $practical_distribution
                </span>
                <span class='col-span-3 p-3 flex center font-bold bg-primary-200 rounded-r-full'>
                    $exam_distribution
                </span>
            </div>
            <div class='flex items-center gap-4'>
                <div class='flex items-center gap-1 font-semibold text-body-500 text-sm'>
                    <div class='w-3 h-3 bg-accent-200 rounded-full'></div>
                    test
                </div>
                <div class='flex items-center gap-1 font-semibold text-body-500 text-sm'>
                    <div class='w-3 h-3 bg-secondary-200 rounded-full'></div>
                    practical
                </div>
                <div class='flex items-center gap-1 font-semibold text-body-500 text-sm'>
                    <div class='w-3 h-3 bg-primary-200 rounded-full'></div>
                    exam
                </div>
            </div>
        </div>";
        } else {
       
            $data = "
        <div class='flex-col gap-3 hidden'>
            <div class='grid grid-cols-10'>
                <span class='col-span-3 p-3 flex center font-bold bg-accent-200'>$test_distribution</span>
                <span
                    class='col-span-7 p-3 flex center font-bold bg-primary-200 rounded-r-full'>$exam_distribution</span>
            </div>
            <div class='flex items-center gap-4'>
                <div class='flex items-center gap-1 font-semibold text-body-500 text-sm'>
                    <div class='w-3 h-3 bg-accent-200 rounded-full'></div>
                    test
                </div>
                <div class='flex items-center gap-1 font-semibold text-body-500 text-sm'>
                    <div class='w-3 h-3 bg-primary-200 rounded-full'></div>
                    exam
                </div>
            </div>
        </div>";
        }
        $data .= "
    </div>
    
    <div style='height: calc(100dvh - 22.5rem);' class='p-2 flex flex-col gap-2 shrink-0'>
        <p class='text-sm font-semibold text-body-300'>Course Description</p>
        <div class='border border-slate-300 rounded overflow-y-auto p-1 text-sm text-body-500'>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium sint vitae ut earum
            aliquid dolor dicta,
            in saepe nesciunt assumenda. Debitis doloribus enim magni, expedita error tempora repellat
            eveniet officia!
            Ducimus eveniet, commodi veniam possimus beatae natus repudiandae perferendis aliquid quae
            unde veritatis
            recusandae sit doloribus repellendus, illum cumque ex. Laudantium, atque sint nisi
            cupiditate id porro
            voluptatibus eveniet et?
            Hic perferendis reiciendis deleniti architecto unde cum nam earum repudiandae ipsam harum
            autem ab quaerat
            optio dolores alias ea excepturi dolor, fuga a laborum? Natus iure enim tenetur culpa
            maiores.
            Amet perferendis reiciendis labore consequatur. Ullam, aspernatur tempora. Totam maxime
            officia saepe
            laboriosam optio velit recusandae, mollitia quibusdam odit labore repellendus nobis id
            beatae provident
            rerum quod deserunt cum animi.
        </div>
    </div>
    
    <div class='px-3 grid center absolute bottom-4 left-[50%] -translate-x-[50%]'>
        <button @click='courseOpen = false' type='button' class='flex flex-col center text-secondary-800 font-semibold'>
            <span class='material-symbols-rounded overflow-hidden'>expand_less</span>
            <p class='text-sm -mt-2'>close</p>
        </button>
    </div>";
    return ['test'=>'expand_more'];
    return ['html'=>$data];
    }












    // New

    public function viewEnrollments(Request $request) {
        
        $semester = $request->semester;
        $level = $request->level;
        $user = auth()->user();
        $student = $user->student;


        if (!$level || !$semester) {
            return redirect('/courses');
        }
        
        $courses = Course::getCourses($level, $semester);

        if (!$courses) {
            abort(403);
        }
    
        $first = $courses[0]->enrollments;
        if (!$first) {
            return redirect('/courses')->with('error', 'Enrollment history doesnt exist');
        }
        $session = $first->session;
          
    
        
        return view('pages.student.course-registration-details', compact('semester','level', 'student', 'user', 'session', 'courses'));
    }

}
