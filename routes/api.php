<?php

use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Student;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ResultsController;

/*
HTTP_OK (200): The request has succeeded.
HTTP_CREATED (201): The request has been fulfilled and resulted in a new resource being created.
HTTP_ACCEPTED (202): The request has been accepted for processing, but the processing has not been completed.
HTTP_NO_CONTENT (204): The server successfully processed the request and is not returning any content.
HTTP_BAD_REQUEST (400): The server could not understand the request due to invalid syntax.
HTTP_UNAUTHORIZED (401): The client must authenticate itself to get the requested response.
HTTP_FORBIDDEN (403): The client does not have access rights to the content.
HTTP_NOT_FOUND (404): The server can not find the requested resource.
HTTP_METHOD_NOT_ALLOWED (405): T


*/
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

/**PROTECTED API */
Route::group(['middleware'=>['auth:sanctum']], function() {
    Route::get('/testapi', function(Request $request){
        return $_SERVER;
    });

    
});
Route::post('/fetchawaiting-results', [ResultsController::class, 'api_getAwaitingResults']);

Route::middleware('auth:api')->group(function () {
    Route::get('/test', fn(Request $request) => $request->user());
});

Route::post('/student', function (Request $request) {
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
Route::post('/search/courses', [CourseController::class, 'api_scan']);
Route::post('/course', [CourseController::class, 'getCourseById']);
Route::post('/course/create', [CourseController::class, 'api_createCourse']);


Route::get('/student_course_details_home', [CourseController::class, 'student_course_details_home'])->middleware('auth');

Route::get('/todo/complete', function(Request $request) {
    return [$request->user];
});

// show students
Route::post('/student', [StudentController::class, 'getStudent']);
Route::post('/advisor', [AdvisorController::class, 'getAdvisor']);

Route::post('/search/students', [StudentController::class, 'search_students']);



Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/class', [ClassController::class, 'api_fetchClass']);

    Route::post('/save-results', [ResultsController::class, 'save_results']);

    Route::post('/classes', [ClassController::class, 'api_index']);

});
// Class Controllers
Route::post('/result/add', [ResultsController::class, 'addResult']);


Route::post('/enrolledCourses', [CourseController::class, 'api_getEnrolledCourses']);



Route::post('/lecturer', [LecturerController::class, 'getLecturer']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});