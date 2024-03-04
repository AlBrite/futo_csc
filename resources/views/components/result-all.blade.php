@php 
    $advisor = \App\Models\Advisor::active();
    dd($advisor);
    $class = $advisor->class;
    $students = $class->students;
    //$results = $class->results;
    $session = request()->get('session');


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
    ->groupBy('enrollments.reg_no')
    ->get([
        'enrollments.course_id',
        'enrollments.reg_no'
    ]);


    $score_array = [];

    foreach($courses as $course) {
        $score_array[$course->id] = '';
    }

    $student_results = [];

    foreach($students as $student) {
        $records = ['score'=>$score_array, 'reg_no' => $student->reg_no];
        $results = \App\Models\Enrollment::where('enrollments.semester', $semester)
        ->join('results', function($join) {
            $join->on('enrollments.course_id','=','results.course_id')
                ->on('enrollments.reg_no', '=','results.reg_no');
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
        ;
        foreach($results as $result) {
            $records['score'][$result->course_id] = $result->score;

        }
        $student_results[] = $records;
    }
    
    
   
    

    $results = $class?->students()
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

        
   
foreach($results as $record) {

    //dd($record);
}
@endphp

<x-template>

    <a href="./results.html" class="flex items-center text-sm font-semibold text-body-200 hover:text-primary">
        <span class="material-symbols-rounded">arrow_left</span>
        View results
    </a>
    
    <div class="w-full mt-2 flex gap-x-2 flex-wrap">
        <form action="" class="flex items-center gap-2 w-full relative md:w-[32rem]">
            <label for="student-search" class="text-body-200 absolute top-3 left-1">
                <span class="material-symbols-rounded">search</span>
            </label>
            <input type="search" name="studentSearch" id="student-search" placeholder="Search..."
                class="border border-[var(--primary)] outline-none h-8  pl-8 text-body-300 w-full">
           
            <button type="submit"
                class="btn-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition rounded h-8">
                Submit
            </button>
        </form>
    
        <div>
            <button type="button"
                class="click-print btn-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition rounded h-8">
                Print
            </button>

            <button type="button"
                class="click-print btn-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition rounded h-8">
                Export
            </button>
        </div>
    </div>
    
    <div id="advisor-results-container-cgpa-summary" class="pb-4">
        <!-- Display this table if session = "all sessions" -->
        <table id="all-sessions" class="table w-full relative text-xs">
            <thead style="text-align: center;">
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
                    @php 
//dd($student);
                    @endphp
                    <tr>
                        <td style="text-align: left;">{{$n+1}}</td>
                        <td>{{$student['reg_no']}}</td>
                        <span>
                            @foreach($student['score'] as $score) 
                                <td>{{$score}}</td>
                            @endforeach
                        </span>
                        
                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
        
                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
        
                        <td>148</td>
                        <td>38</td>
                        <td>4.55</td>
        
                        <td>PASS</td>
                    </tr>
                @endforeach
    
    
            </tbody>
        </table>
        <!--  -->
    </div>
</x-template>


