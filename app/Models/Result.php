<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Student;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Result extends Model
{
    use HasFactory;



    protected $fillable = [
        'semester',
        'session',
        'uploaded_by',
        'course_id',
        'reg_no',
        'remark',
        'score',
        'level',
        'grade',
        'exam',
        'test',
        'lab'
    ];



    private $standard_grading = ["A" => 70, "B" => 60, "C" => 50, "E" => 45, "D" => 40, "F" => 0];




    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }





    public function student()
    {
        return $this->hasOne(Student::class, 'reg_no', 'reg_no');
    }




    public function grading()
    {
        return $this->hasOne(Grading::class, 'id', 'grading_id');
    }





    public function getGrading()
    {
        $score = $this->score;

        $grading = $this->standard_grading;

        try {

            if ($grading_system = $this->grading?->grading_system) {
                $grading = json_decode($grading_system, true);
            }
        } catch (\Exception $e) {
        }

        $n = count($grading) - 1;

        foreach ($grading as $grade => $range) {
            if ($score >= $range) {
                return [
                    'alphaGrade' => $grade,
                    'grade' => $n,
                    'score' => $score,
                    'exam' => $this->exam,
                    'test' => $this->test,
                    'lab' => $this->lab,
                    'remark' => $grade == 'F' ? 'Failed' : 'Passed'
                ];
            }
            $n--;
        }

        return $grading;
    }

    public function updateCGPA()
    {

        $this->student->cgpa = $this->student->calculateCGPA();
        $this->student->save();
    }



    public static function calculateGPA($records, $semester, $session)
    {

        $totalCredits = 0;
        $totalQualityPoints = 0;
        foreach ($records as $course) {
            $result = Result::where('semester', '=', $semester)
                ->with('grading', 'course')
                ->where('session', '=', $session)
                ->where('course_id', '=', $course->course_id)
                ->where('reg_no', '=', $course->reg_no)
                ->get()->last();

            $credits = $result->course->units;

            $gradingSystem = $result->getGrading();

            $grade = $gradingSystem['grade'];

            $qualityPoints = $grade * $credits;
            $totalCredits += $credits;
            $totalQualityPoints += $qualityPoints;
        }
        $gpa = 0;
        if ($totalCredits > 0) {
            $gpa = $totalQualityPoints / $totalCredits;
        }
        return [
            'TGP' => $totalQualityPoints,
            'TNU' => $totalCredits,
            'GPA' => round($gpa, 2)
        ];
    }


    public static function studentGPA($reg_no, $semester, $session)
    {
        $enrollments = Enrollment::join('results', function ($join) {
            $join->on('results.reg_no', '=', 'enrollments.reg_no')
                ->where('results.status', '=', 'APPROVED');
        })
            ->where('enrollments.reg_no', $reg_no)
            ->where('enrollments.semester', $semester)
            ->where('enrollments.session', $session)->get();


        return self::calculateGPA($enrollments, $semester, $session);
    }


    public static function studentPreviousSemesterGPA($reg_no, $semester, $session)
    {
        $splitSession = explode('/', $session);
        $mapToInt = array_map(fn ($year) => (int) $year, $splitSession);
        list($start, $end) = $mapToInt;

        if ($semester === 'harmattan') {
            $start--;
            $end--;
            $semester = 'rain';
        } else {
            $semester = 'harmattan';
        }

        $enrollments = Enrollment::join('results', function ($join) {
            $join->on('results.reg_no', '=', 'enrollments.reg_no')
                ->where('results.status', '=', 'APPROVED');
        })
            ->where('enrollments.reg_no', $reg_no)
            ->where('enrollments.semester', $semester)
            ->where('enrollments.session', $session)->get();

        return self::calculateGPA($enrollments, $semester, $session);
    }




    public static function studentPreviousSessionGPA($reg_no, $semester, $session)
    {
        $splitSession = explode('/', $session);
        $mapToInt = array_map(fn ($year) => (int) $year, $splitSession);
        list($start, $end) = $mapToInt;


        if ($semester === 'harmattan') {
            $start--;
            $end--;
        } else {
            $semester = 'harmattan';
        }

        $enrollments = Enrollment::where('reg_no', $reg_no)
            ->where('semester', $semester)
            ->where('session', $session)->get();

        return self::calculateGPA($enrollments, $semester, $session);
    }

    /**
     * Get Awaiting results for a given level
     * @param int $level
     * @return Result
     */



    public static function getLevelAwaitingResults(int $level)
    {

        return self::where('results.level', $level)
            ->where('results.status', 'COMPELETED')
            ->join('courses', 'courses.id', '=', 'results.course_id')
            ->leftJoin('students', 'students.reg_no', '=', 'results.reg_no')
            ->leftJoin('users', 'users.id', '=', 'students.id')
            ->orderBy('courses.code')->get(['results.*', 'courses.code', 'users.name']);
    }



    /**
     * completed results awaiting admin approval
     */
    public static function awaitingResults($semester = null, $session = null)
    {
        $results = self::where('results.status', 'COMPLETED')
            ->join('courses', 'courses.id', '=', 'results.course_id')
            ->groupBy(['session', 'courses.level', 'courses.code']);

        if ($session && $semester) {
            return $results->where('session', $session)->where('semester', $semester);
        }
        return $results;
    }






    public function groupResultsByLevelSemesterSession($reg_no = null)
    {
        $reg_no ??= auth()->user()->student->reg_no;
        // Fetch all results for the given student ID
        $results = Result::where('reg_no', $reg_no)->get();

        // Group the results by level, semester, and session using Laravel collection methods
        $groupedResults = $results->groupBy(['level', 'semester', 'session']);

        // Alternatively, you can also group the results in the database query
        $groupedResults = DB::table('results')
            ->select('level', 'semester', 'session', DB::raw('count(*) as count'))
            ->where('reg_no', $reg_no)
            ->groupBy('level', 'semester', 'session')
            ->get();
        
        
        $records = [];




        foreach ($groupedResults as $group) {
            $level = $group->level;
            $semester = $group->semester;
            $session = $group->session;
        
            Arr::set($records, "$session.$semester", '');
            
            $records[$session][$semester] = $this->calculateGPAForGroup($reg_no, $level, $semester, $session);
        }

        return $records;
    }

    public function calculateGPAForGroup($reg_no, $level, $semester, $session)
    {
        // Fetch results for the student for the specified level and session
        $results = Result::join('courses', 'courses.id', '=', 'results.course_id')
        
            ->where('results.reg_no', $reg_no)
           // ->where('results.level', $level)
            ->where('results.session', $session)
            ->where('results.semester', $semester)
            ->get();

        return $this->calculateGPA($results, $semester, $session);
    }
}
