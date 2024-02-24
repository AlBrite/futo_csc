@php 
    $advisor = \App\Models\Advisor::active();
    $authUser = auth()->user();

    $course_id = request()->get('course');

    //id  | course_id | reg_no      | grouping_id | semester  | session   | level | created_at          | updated_at        
    
    $students = $class->students;

        $records = $class->students()
            ->leftJoin('users', 'users.id', '=', 'students.id')
            ->leftJoin('enrollments', 'enrollments.reg_no', '=', 'students.reg_no')
            ->leftJoin('courses', function($join) use ($semester, $session, $course_id) {
                $join->on('courses.id', '=', 'enrollments.course_id')
                    ->where('enrollments.semester',$semester)
                    //->where('enrollments.course_id', $course_id)
                    ->where('enrollments.session',$session);
            })
            
            ->leftJoin('results', function($join) use ($semester, $session, $course_id) {
                $join->on('results.reg_no', '=', 'students.reg_no')
                    ->where('enrollments.semester',$semester)
                    ->where('enrollments.course_id', $course_id)
                    ->where('enrollments.session',$session);
            })

            ->where('enrollments.semester',$semester)
            ->where('enrollments.session', $session)
            ->where('enrollments.course_id', $course_id)
        
           ;


//dd($records->get());
@endphp
   
    
    <div id="advisor-results-container-single" class="pb-4 grid place-content-center grid-cols-1">
        @if (count($records) > 0)
        <div class="scroller">
            <table class="visible-on-print print:text-black responsive-table whitespace-nowrap w-full lg:!w-[300px]">
                <thead class="print:bg-white print:text-black">
                    <tr>
                        <th class="w-10">S/N</th>
                        <th>Name</th>
                        <th>Reg. No.</th>
                        <th class="w-10">Program</th>
                        <th class="w-10">Test</th>
                        <th class="w-10">Lab</th>
                        <th class="w-10">Exam</th>
                        <th class="w-10">Total</th>
                        <th class="w-10">Grade</th>
                        <th class="w-10">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="w-10"></th>
                        <th></th>
                        <th></th>
                        <th class="w-10"></th>
                        <th class="w-10">20</th>
                        <th class="w-10">20</th>
                        <th class="w-10">60</th>
                        <th class="w-10">100</th>
                        <th class="w-10"></th>
                        <th class="w-10"></th>
                    </tr>

                    @php $n=1;
                        
                    @endphp 
                    @foreach($records as $record)
                        @php 

                        $result = \App\Models\Enrollment::result(
                            $record->reg_no, 
                            $record->id,
                            $semester,
                            $session
                        );
                        $gradings = $result->getGrading();
                        @endphp
                    
                    <tr>
                        
                        <td>{{$n}}</td>
                        <td>{{$record->name}}</td>
                        <td>{{$record->reg_no}}</td>
                        <td align="center">{{explode(' ',$record->code)[0]}}</td>
                        <td align="center">{{$result['test']}}</td>
                        <td align="center">{{$result['lab']}}</td>
                        <td align="center">{{$result['exam']}}</td>
                        <td align="center">{{$result['score']}}</td>
                        <td align="center">{{$gradings['alphaGrade']}}</td>
                        <td align="center">{{$gradings['remark']}}</td>
                    </tr>
                    @php $n++; @endphp 
                    @endforeach
                    
                </tbody>
            </table>
        </div>
        @else 

        <img src="{{ asset('images/no-student.png') }}" class="w-[200px] justify-self-center"/>

        @endif
    </div>
    
    




            