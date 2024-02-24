<?php

use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Student;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ResultsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/csrf-end-point', fn() => ['token' => csrf_token()]);


Route::get('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
 
    return ['token' => $token->plainTextToken];
});



Route::get('/login', [AuthController::class, 'apiLogin']);

Route::get('/register', 'AuthController@register');

Route::group(['middleware'=>['auth:sanctum']], function() {
    Route::get('/testapi', function(Request $request){
        return $_SERVER;
    });
});

Route::middleware('auth:api')->group(function () {
    Route::get('/test', fn(Request $request) => $request->user());
});

Route::get('/student', function (Request $request) {
    $student = Student::where('reg_no', $request->id)->with('user');

    if (!$student->exists()) {
        return null;
    }
    return $student->first();
});

Route::get('/findStudent', function(Request $request) {
    $student = Student::where('id', $request->query)->orWhere('name', $request->query)->with('user');
});


Route::post('/courses', [CourseController::class, 'api_getCourses']);
Route::post('/course', [CourseController::class, 'getCourseById']);
Route::post('/course/create', [CourseController::class, 'api_createCourse']);


Route::get('/student_course_details_home', [CourseController::class, 'student_course_details_home'])->middleware('auth');

Route::get('/todo/complete', function(Request $request) {
    return [$request->user];
});

// show students
Route::post('/student', [StudentController::class, 'getStudent']);
Route::post('/advisor', [AdvisorController::class, 'getAdvisor']);

Route::get('/search/students', [StudentController::class, 'search_students']);



Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/class', [ClassController::class, 'api_fetchClass']);

    Route::post('/save-results', [ResultsController::class, 'save_results']);

});
// Class Controllers
Route::get('/classes', [ClassController::class, 'api_index']);


Route::get('/enrolledCourses', [CourseController::class, 'api_getEnrolledCourses']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});