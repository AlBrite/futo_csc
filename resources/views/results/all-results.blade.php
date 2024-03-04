@php 
    use \App\Models\Result;
    $session = request()->get('session');
    $semester = request()->get('semester');



    $courses = \App\Models\Enrollment::where('enrollments.semester', $semester)
    ->join('courses', 'courses.id', '=', 'enrollments.course_id')
    ->where('enrollments.session', $session)
    ->where('enrollments.semester', $semester)
    ->groupBy('courses.code')
    ->orderBy('courses.id')
    ->get([
        'courses.code',
        'courses.units',
        'courses.exam',
        'courses.practical',
        'courses.test',
        'courses.id'    
    ]);

    $students = \App\Models\Enrollment::where('enrollments.semester', $semester)
    ->where('enrollments.session', $session)
    ->join('students', 'students.reg_no', '=', 'enrollments.reg_no')
    ->join('users', 'users.id', '=', 'students.id')
    ->groupBy('enrollments.reg_no')
    ->get([
        'enrollments.course_id',
        'enrollments.reg_no',
        'students.cgpa'
    ]);




    $score_array = [];

    foreach($courses as $course) {
        $score_array[$course->id] = '';
    }

    $student_results = [];

    foreach($students as $student) {
        $records = [
            'score' => $score_array,
            'reg_no' => $student->reg_no,
            'cgpa' => $student->cgpa
        ];

        $results = \App\Models\Enrollment::where('enrollments.semester', $semester)
            ->join('results', function($join) {
                $join->on('enrollments.course_id','=','results.course_id')
                    ->on('enrollments.reg_no', '=','results.reg_no')
                    ->on('enrollments.semester', '=','results.semester')
                    ->on('enrollments.session', '=','results.session');
            })
            ->where('enrollments.session', $session)
            ->where('enrollments.reg_no', $student->reg_no)
            ->orderBy('enrollments.course_id')
            ->get([
                'results.reg_no',
                'results.score',
                'results.lab',
                'results.exam',
                'results.test',
                'results.course_id'
                ]);
            
        
        foreach($results as $result) {
            $records['score'][$result->course_id] = $result->score;

        }
        $student_results[] = $records;
    }
    
    
   
    

    $results = $class->students()
        ->join('results', 'results.reg_no', '=', 'students.reg_no')
        ->join('courses', 'courses.id', '=', 'results.course_id')
        ->join('enrollments', 'enrollments.course_id', '=', 'results.course_id')
        ->where('enrollments.session', '=', $session)
        ->where('enrollments.semester', '=', $semester)
        ->orderBy('courses.id')
        ->get([
            'courses.code',
            'results.course_id',
            'results.score',
            'results.lab',
            'results.exam',
            'results.test',
            'students.reg_no'    
        ]);

        
@endphp


    
    <div id="advisor-results-container-cgpa-summary" class="p-4 lg:p-8">
        <!-- Display this table if session = "all sessions" -->
        <table id="all-sessions" class="overflow-auto w-full relative text-xs">
            <thead style="text-align: center;" class="bg-green-900 text-white">
                <th class="w-5">S/N</th>
                <th>Reg. No.</th>
                <!-- Courses: Use the span element to loop through the courses -->
                <span>
                    @foreach($courses as $course) 
                        <th>{{$course->code}}</th>
                    @endforeach
                    
                </span>
                
                <th colspan="3">Current</th>
                <th colspan="3">Previous</th>
                <th colspan="3">Cummulative</th>
                <th>Remark</th>
            </thead>
            <thead>
                <th></th>
                <th></th>
                <!-- Course Units -->
                <span>
                    @foreach($courses as $course) 
                        <th>{{$course->units}}</th>
                    @endforeach
                    
                </span>
                <th>TGP</th>
                <th>TNU</th>
                <th>GPA</th>

                <th>TGP</th>
                <th>TNU</th>
                <th>GPA</th>

                <th>TGP</th>
                <th>TNU</th>
                <th>GPA</th>

                <th></th>
            </thead>
            <tbody style="text-align: center;">
                @foreach($student_results as $n => $student)
                   
                    <tr>
                        <td style="text-align: left;">{{$n+1}}</td>
                        <td>{{$student['reg_no']}}</td>
                        <span>
                            @foreach($student['score'] as $score) 
                                <td>{{$score}}</td>
                            @endforeach
                        </span>
                        @php 
                            $gpa = Result::studentGPA($student['reg_no'], $semester, $session);
                            $previewsGPA = Result::studentPreviousSemesterGPA($student['reg_no'], $semester, $session);
                           
                            $cgpa = $student['cgpa'];


                        @endphp
                         
                        <td class="border-l-4 border-green-900">{{$gpa['TGP']}}</td>
                        <td>{{$gpa['TNU']}}</td>
                        <td>{{$gpa['GPA']}}</td>

                        <td class="border-l-4 border-green-900">{{$previewsGPA['TGP']}}</td>
                        <td>{{$previewsGPA['TNU']}}</td>
                        <td>{{$previewsGPA['GPA']}}</td>

                        <td class="border-l-4 border-green-900"></td>
                        <td></td>
                        <td>{{$cgpa}}</td>
        
                        <td class="border-l-4 border-green-900">PASS</td>
                    </tr>
                @endforeach
    
    
            </tbody>
        </table>
        <!--  -->
    </div>



