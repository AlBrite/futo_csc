@php
    $advisor = \App\Models\Advisor::active();
    $authUser = auth()->user();

    $class = $advisor->class;
    $students = $class->students;
    //$results = $class->results;
    //$students = $class->students->results;

    $records = $class
        ->students()
        ->join('users', 'users.id', '=', 'students.id')
        ->join('enrollments', 'enrollments.reg_no', '=', 'students.reg_no')
        ->join('courses', 'courses.id', '=', 'enrollments.course_id')

        ->leftJoin('results', function ($join) use ($semester, $session) {
            $join
                ->on('results.reg_no', '=', 'students.reg_no')
                ->where('enrollments.semester', $semester) //->where('enrollments.code', $course)
                ->where('enrollments.session', $session);
        })

        ->where('enrollments.semester', $semester)
        ->where('enrollments.session', $session)
        //->where('enrollments.code', $course)
        ->get(['students.*', 'results.score', 'courses.code', 'users.name', 'students.reg_no'])
        ->unique('id');

@endphp
<x-template title="Advisor - Results" nav="results">
    <div class="p-5 lg:p-10">
        <x-page-header>
            View Results
        </x-page-header>


        <div class="w-full">
            <form action="/display_results" method="post"
                class="!flex items-center gap-2 w-full relative input rounded-md p-0 pl-3">
                @csrf
                <label for="student-search" class="text-body-200 grid place-items-center">
                    <span class="material-symbols-rounded">search</span>
                </label>
                <div class="flex-1">
                    <input type="search" name="studentSearch" id="student-search" placeholder="Search..."
                        class=" w-full">
                </div>

                <button type="submit" class="btn-primary m-0 rounded-none rounded-r-md">
                    Submit
                </button>
            </form>

            <form action="/display_results" method="POST" class="flex items-end flex-wrap gap-x-2"
                id="result-options-form">
                @csrf
                <div class=" grow">
                    <label for="session">Session</label>

                    <select x-on:change="session=$event.target.value" name="session" id="session"
                        class="input w-full">
                        <option value="" class>Select session</option>
                        @php
                            $start_year = 2018;
                            $this_year = date('Y');
                            $diff = $this_year - $start_year;

                            $year = $start_year;

                            for ($n = $start_year; $n < $this_year; $n++) {
                                $end = $year + 1;
                                $session = "{$year}/{$end}";
                                echo "<option value='$session'>$session</option>";
                                $year++;
                            }
                        @endphp
                    </select>
                </div>

                <div class=" grow">
                    <label for="semester">Semester</label>
                    <select x-on:change="semester=$event.target.value" :disabled="!session" name="semester"
                        id="semester" class="input w-full" disabled>
                        <option value="">Select semester</option>
                        <option value="harmattan">Harmattan</option>
                        <option value="rain">Rain</option>
                    </select>
                </div>

                <div class=" grow">
                    <label for="course">Course</label>
                    <select x-on:change="course=$event.target.value" :disabled="!semester" name="course"
                        id="course" class="input w-full" disabled>
                        <option value="">Select course</option>
                        <option value="all">All courses</option>
                        <option value="1">MTH 101</option>
                    </select>
                </div>

                <button :disabled="!course" type="submit" disabled
                    class="btn-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition rounded h-8">
                    View Result
                </button>
            </form>
            <div class="flex justify-end">
                <button type="button" class="btn-primary click-print">
                    Print
                </button>
            </div>
        </div>

        <div id="advisor-results-container-single" class="pb-4">
            <div class="scroller">
                <table class="visible-on-print print:text-black responsive-table whitespace-nowrap w-full">
                    <thead class="print:bg-white print:text-black">
                        <th class="w-10">S/N</th>
                        <th class="text-left">Name</th>
                        <th>Reg. No.</th>
                        <th class="w-10">Program</th>
                        <th class="w-10">Test</th>
                        <th class="w-10">Lab</th>
                        <th class="w-10">Exam</th>
                        <th class="w-10">Total</th>
                        <th class="w-10">Grade</th>
                        <th class="w-10">Remark</th>
                    </thead>
                    <tbody>

                        @php $n=1; @endphp
                        @foreach ($records as $record)
                            <tr>
                                <td>{{ $n }}</td>
                                <td class="text-left">{{ $record->name }}</td>
                                <td>{{ $record->reg_no }}</td>
                                <td>{{ $record->code }}</td>
                                <td>20</td>
                                <td>20</td>
                                <td>60</td>
                                <td>100</td>
                                <td>A</td>
                                <td>{{ $record->score }}</td>
                            </tr>
                            @php $n++; @endphp
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>



</x-template>
