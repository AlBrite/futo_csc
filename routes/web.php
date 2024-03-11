<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DBExportController;
use App\Models\{Student,Course, Result, AcademicRecord, AcademicSet, Admin, Device, Enrollment, User};
use App\Http\Controllers\ {
    AuthController,
    ModeratorController,
    CourseController,
    ResultController,
    AdminController,
    AdvisorController,
    AnnouncementController,
    ClassController,
    ResultsController,
    StudentController,
    TodoController,
    UserController,
    LecturerController,
    MailController,
    MaterialController,
    TestController
};
use Illuminate\Http\Request;
use PhpParser\Builder\ClassConst;

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


// HOME
Route::get('/', fn() => redirect(auth()->check()?'/home':'/login'));

/********TESTING ROUTES**********/
    Route::get('/generate', [TestController::class, 'generate']);
    Route::get('/calender', fn() => view('components.calendar'));
    Route::get('/export', [DBExportController::class, 'exportToJson']);
    Route::get('/test', function() {
        //return redirect('/home')->with('success', 'Tested');
        return view('pages.test');
    });
    Route::get('/info', function(){
        echo phpinfo();
    });


/*********GUEST ROUTES***********/
    Route::middleware('guest')->group(function(){

        Route::get('/login', [AuthController::class, 'login'])->name('login');
        Route::get('/lost-password', fn() => view('pages.auth.lost-password'))->middleware('guest');
        Route::get('/otp',function(){
        
            return view('pages.auth.otp');
    });
        
        Route::post('/dologin', [AuthController::class, 'doLogin']);
        Route::post('/otp', [AuthController::class, 'verifyOTP']);
        

        Route::get('/lockscreen', function(Request $request){
                if ($profile = AuthController::locked_user()) {
                    return view('pages.auth.lockscreen', compact('profile'));
                }
                return redirect('/login');
        });
    });





/***AUTHENTICATED USERS ROUTES***/

    Route::middleware('auth')->group(function(){
        Route::get('/home', [UserController::class, 'dashboard'])->name('home');

        /**MODERATORS ROUTES**/

            Route::get('/classlist', [ClassController::class, 'classlist'])->name('view.class_list');

        
            Route::get('/courses', [CourseController::class, 'index'])
                ->name('view.courses')
                ->middleware('role:mod');
            
            Route::post('/announcement/add', [AnnouncementController::class, 'add'])->middleware('role:mod');


            Route::get('/announcement', [AnnouncementController::class, 'insert'])->middleware('role:mod');

            Route::get('/display_results', [ResultController::class, 'index'])->middleware('role:mod');
            
            // Display For uploading of results
            Route::get('/upload-results', fn()=> view('pages.advisor.upload-results'))->middleware('role:mod');

            Route::get('/cgpa_summary', fn() => view('pages.advisor.cgpa-summary-result'))->name('advisor.cgpa_summary')->middleware('role:advisor');

            

            Route::post('/update/lecturer', [LecturerController::class, 'update'])
                ->name('update.lecturer');

            Route::get('/material/insert', [MaterialController::class, 'insert'])
                ->name('insert.material')
                ->middleware('role:lecturer');

            Route::get('/materials', [MaterialController::class, 'index'])
                ->name('index.materials');

            Route::post('/material/upload', [MaterialController::class, 'store'])
                ->name('upload.material')
                ->middleware('role:lecturer');

            Route::get('/material/{material}', [MaterialController::class, 'show'])
                ->name('show.material');

        /**ADMIN ROUTES**/

            Route::get('/admin/courses',  [CourseController::class, 'admin_view_courses'])
            ->name('admin.view-courses')
            ->middleware('role:admin');

            Route::get('/admin/classes', [ClassController::class, 'show_to_admin'])
                ->name('admin.classes')
                ->middleware('role:admin');

            Route::get('/admin/class/add', [ClassController::class, 'add'])
                ->name('admin.add-class')
                ->middleware('role:admin');

            Route::get('/admin/class/edit', [ClassController::class, 'show_to_admin'])
                ->name('admin.edit-class')
                ->middleware('role:admin');

            Route::post('/admin/class/store', [ClassController::class, 'store'])
                ->name('admin.store-new-class')
                ->middleware('role:admin');


            Route::get('/admin/course/add', [CourseController::class, 'add'])
            ->name('admin.add_course')
            ->middleware('role:admin');

            Route::get('/admin/advisor/edit', [AdvisorController::class, 'update_admin'])
            ->name('admin.edit_course')
            ->middleware('role:admin');

            Route::post('/admin/advisor/store', [AdvisorController::class, 'store'])
            ->name('create_advisor')
            ->middleware('role:admin');

            Route::get('/admin/student/add', [StudentController::class, 'add'])
            ->middleware('role:admin');

            Route::post('/admin/student/add', [StudentController::class, 'store'])
            ->name('admin_create_student')
            ->middleware('role:admin');

            Route::get('/admin/result/pending', [ResultsController::class, 'awaitingResults'])
                ->name('awaiting-results')
                ->middleware('role:admin');
            Route::post('/admin/approve/pending', [ResultsController::class, 'approveResults'])
                ->name('approveAwaitingResults')
                ->middleware('role:admin');


            Route::get('/mail',[MailController::class, 'sendMail']);










            Route::get('/admin/course/edit', [CourseController::class, 'edit']);
           

            Route::get('/admin/advisors', [AdvisorController::class, 'index_admin']);
           
            Route::get('/admin/advisor/add', [AdvisorController::class, 'add']);
           
            

            Route::get('/admin/lecturer/add', [LecturerController::class, 'add'])
            ->name('add.lecturer')
            ->middleware('role:admin');



            Route::get('/timetable', fn() => view('pages.admin.timetable'));

            Route::post('/update', [UserController::class, 'update']);
           

            
            

            

            
            


            Route::post('/lecturer/store', [LecturerController::class, 'store'])
                ->name('store.lecturer')
                ->middleware('role:admin');
            
           
           
                Route::get('/courses/add', [CourseController::class, 'addCourseForm'])
                ->name('add.course')->middleware('role:admin');

                Route::post('/courses/add', [CourseController::class, 'store'])
                ->name('store.course')->middleware('role:admin');

                

            Route::get('/admin/course/{course}', [CourseController::class, 'showForAdmin'])
                ->name('admin-view.course')->middleware('role:admin');
            
            Route::post('/admin/advisor/update', [AdminController::class, 'update_advisor'])
                ->name('advisor.admin_update')
                ->middleware('role:admin');
        
           
        
            // Admin: update course 
            Route::post('/courses/update', [CourseController::class, 'updateCourse'])
            ->middleware('role:admin')
            ->name('update.course');
        

           


            
            Route::get('/admin/{page}', [AdminController::class, 'route'])
                ->middleware('role:admin');

        /**ADVISORS ROUTES**/
            Route::get('/advisor/transcript', [ResultController::class, 'view_transcripts'])
                ->name('nav.transcript')
                ->middleware('role:advisor');

            Route::post('/generate_transcripts', [ModeratorController::class, 'loadTranscript'])
                ->name('generate.transcript')
                ->middleware('role:advisor');
                

            Route::get('/advisor/class', [ClassController::class, 'show'])
                ->name('nav.class')
                ->middleware('role:advisor');


            Route::get('/advisor/results', [ResultsController::class, 'index'])
                ->name('advisor.nav-result')
                ->middleware('role:advisor');

        /**LECTURERS ROUTES**/
            Route::get('/moderator/add-results', [ResultController::class, 'insert_lecturers'])
                ->name('results.insert_lecturers')
                ->middleware('role:lecturer');
            
            Route::post('/import-results', [ResultsController::class, 'uploadExcel'])
                ->name('import.results')
                ->middleware('role:lecturer');

            Route::get('/upload-form', [ResultsController::class, 'insert'])
                ->name('results.upload_form')         
                ->middleware('role:lecturer');
            
            Route::get('/results/spreadsheet', [ResultsController::class, 'spreadsheet'])
            ->name('upload.ogr')
            ->middleware('role:lecturer');

            Route::get('/lecturer/uploadManually', [ResultsController::class, 'lecturer_add_result'])
                ->name('lecturer.add-result')
                ->middleware('role:lecturer');


        /**STUDENT ROUTES**/
            Route::get('/student/register-courses', [CourseController::class, 'registerCourse'])
                ->middleware('role:student')
                ->name('register.course');

            Route::get('/courses', [CourseController::class, 'index_student'])->middleware('role:student');
            Route::get('/course-registration', fn()=>view('pages.student.course-registration'))->middleware('role:student');
            
            Route::post('/course_registration', fn()=>view('pages.student.course_registration-post'))->middleware('role:student');
            
            Route::get('/course-registration-details', [CourseController::class, 'viewEnrollments'])->name('view.enrollment')->middleware('role:student');
            
            Route::get('/course-registration-borrow-courses', [CourseController::class, 'course_form'])
                ->name('show.course_registration')
                ->middleware('role:student');
        
            // Student: Course Registeraton: Add courses to database
            Route::post('/courses/insert', [CourseController::class, 'doRegister'])
                ->middleware('role:student')
                ->name('insert.courses');

            Route::get('/results', [ResultsController::class, 'show_results'])->middleware('role:student');

        /**OTHERS ROUTES**/
            Route::post('/todo/add', [TodoController::class, 'store']);
        
            Route::get('/search/students', [StudentController::class, 'search_students'])->name('search.students');
            
            Route::get('/course/{course}', [CourseController::class, 'show'])
                ->name('view.course');

            Route::get('/settings', fn() => view('pages.general.settings'))
                ->name('settings');

            Route::get('/change_password', fn() => view('pages.general.change-password'))
                ->name('change_password');
            Route::post('/change_password', [UserController::class, 'changePassword']);

            Route::get('/change_profile_pic', fn() => view('pages.general.change-profile-pic'))
                ->name('change_profile_pic');
                
            Route::get('/logout', [AuthController::class, 'doLogout'])->name('logout');

            Route::get('/profilepic/{user}', [UserController::class, 'display_picture'])->name('profilepic');

    });