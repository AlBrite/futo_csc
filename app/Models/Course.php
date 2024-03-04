<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Enrollment;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'level',
        'units',
        'mandatory',
        'practical',
        'prerequisite',
        'grouping_id',
        'semester',
        'exam',
        'test',
        'image',
        'outline'
    ];


    public function allCourses()
    {
    }

    public function getLevelCourses(int $level)
    {
    }
    public function getLevelMandatoryCourses(int $level)
    {
    }

    public function getLevelElectiveCourses(int $level)
    {
    }

    public function getAllCoursesForDepartment(int $department)
    {
        return Course::whereNull('department_id')->orWhere('department_id', $department);
    }


    public function getAllCoursesForLevel(int $level, int $department)
    {
        return Course::whereNull('department_id')->orWhere('department_id', $department)->addWhere('level', $level);
    }


    public function result() {
        return $this->hasOne(Result::class, 'course_id', 'id');
    }

    



    public static function getAllCourses()
    {
        return Course::where('status', 'active')->get();
    }

    public static function achiveCourse(int $course_id) {
        $course = Course::find($course_id)->get();
    }

    public function prerequisites() {
        return $this->hasOne(Course::class, 'prerequisite', 'id');
    }

    public function enrollments() {
        return $this->hasOne(Enrollment::class, 'course_id');
    }

    public static function getCourses($level, $semester)
    {
        return Course::where('level', $level)->where('semester', $semester)->with('enrollments')->orderBy('mandatory', 'desc')->get();
    }

    public static function listCoursesForRegistrations($level, $semester, ?array $borrowed) {
      ;
        $borrowed ??= [];
        $borrowed = array_filter($borrowed, fn($item) => is_numeric($item));

        return Course::where('level', $level)
        ->where('semester', $semester)
        ->orWhereIn('id', $borrowed)
        ->with('enrollments')
        ->orderBy('mandatory', 'desc')->get();


        Course::getCoursesForRegistration(request()->level, request()->semester, request()->courses);
    }









    public static function generateSessions(?int $from = null, ?int $to = null, $seperator='/') {
        $sessions = [];
        $to ??= date('Y');

        
        // Generate Session for ten years ago from the current year
        if (!$from) {
            $to = date('Y');
            $from = $to - 10;
        }

        $from = min($from, $to);
        $to = max($from, $to);

        if ($to == $from) {
            $to += 1;
        }
        
        
        $diff = $to - $from;
        for($i = 0; $i < $diff; $i++) {
            $startSemester = $from + $i;
            $endSemester = $from + $i + 1;

            $sessions[] = $startSemester.$seperator.$endSemester;
        }


        return $sessions;

        
    }

    
}
