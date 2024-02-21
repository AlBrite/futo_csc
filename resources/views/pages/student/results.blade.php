<x-user-layout nav="results" title="Results">
    <x-page-header>
        Results
    </x-page-header>
         
        <h1 class="text-secondary-800 font-semibold">View Results</h1>

        <div class="print:visible">
            <div class="flex flex-col center">
                <img src="http://127.0.0.1:8000/images/futo-log.png" alt="futo-logo" width="35">
                <h1 class="text-sm font-semibold text-body-400 md:text-base xl:text-lg print:text-black">
                    FEDERAL UNIVERSITY OF TECHNOLOGY, OWERRI
                </h1>
                <p class="text-xs text-body-400 font-semibold md:text-sm xl:text-base print:text-black">DEPARTMENT OF COMPUTER SCIENCE (SICT)</p>
            </div>
        </div>
        
        <form class="flex items-center gap-2 w-full flex-wrap" x-data="{ session: '{{$session??''}}', semester: '{{$semester??''}}' }">
            <div class="select">
                <select name="session" id="session" title="session" class="rounded" @change="session=$event.target.value">
                    <option value="0">Select Session</option>
                    @foreach($sessions as $_session)
                        <option value="{{$_session->session}}" {{$_session->session==$session?'selected':''}}>{{$_session->session}}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="select">
                <select :disabled="!session" name="semester" id="semester" title="semester" class="rounded" @change="semester=$event.target.value">
                    <option value="">Select Semester</option>
                    <option value="harmattan" {{$semester=='harmattan'?'selected':''}}>Harmattan</option>
                    <option value="rain" {{$semester=='rain'?'selected':''}}>Rain</option>
                </select>
            </div>
            
            <div>
                
                <button
                :disabled="!semester" 
                type="submit"
                class="btn text-white bg-[var(--primary)] rounded hover:bg-[var(--primary-700)] text-sm p-[.3rem]">
                    Search
                </button>
            </div>
        </form>
    <div id="view-results-container" class="flex flex-col">
        @if(!$records) 
            <div>Select Section and Semester above to view results</div>
        @else   
            <div id="student-table-container" class="overflow-x-auto max-w-full min-w-full">
                <table class="responsive-table visible-on-print">
                    <thead>
                        <th class="w-20">Course Code</th>
                        <th>Course Title</th>
                        <th class="w-20">Units</th>
                        <th class="w-20">Test</th>
                        <th class="w-20">Lab</th>
                        <th class="w-20">Exam</th>
                        <th class="w-20">Total</th>
                        <th class="w-20">Grade</th>
                        <th class="w-20">Remark</th>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                            @php 
                            $result =  \App\Models\Enrollment::result($record->reg_no, $record->course_id, $record->semester, $record->session); 
                            $grading = $result->getGrading();
                            //g $reg_no, int $course_id, string $semester, string $session
                            @endphp
                            <tr>
                                <td class="uppercase">{{$record->course->code}}</td>
                                <td>{{$record->course->name}}</td>
                                <td align="center">{{$result->course->units}}</td>
                                <td align="center">{{$result->test}}</td>
                                <td align="center">{{$result->lab}}</td>
                                <td align="center">{{$result->exam}}</td>
                                <td>{{$result->score}}</td>
                                <td class="uppercase">{{$grading['alphaGrade']}}</td>
                                <td class="uppercase">{{$result->score < 40 ? 'Failed' : 'Passed'}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2 rounded bg-primary-50 text-sm p-2 print:visble">
                    <p class="text-body-300">GPA: 
                        <span class="text-black-400 font-semibold">{{$GPA['GPA']}}</span>
                    </p>
                    <p class="text-body-300">CGPA:
                        <span class="text-black-400 font-semibold">{{$student->calculateCGPA('GPA')}}</span>
                    </p>
                </div>

                <!-- Display this button only if there are results to show -->
                <button type="button"
                    @click="handlePrint"
                    class="btn bg-[var(--primary)] text-white hover:bg-[var(--primary-700)] rounded text-sm">Print Result
                </button>
                <!-- Display this button only if there are results to show -->
            </div>
        @endif
    </div>
</x-user-layout>