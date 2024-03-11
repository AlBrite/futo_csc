@php
    $students = \App\Models\Student::all();
    $results = \App\Models\Result::paginate(6);
    $courses = \App\Models\Course::active();
    $totalActiveCourses = $courses->get()->count();
    $inActiveCourses = \App\Models\Course::inActive();
    $totalInactiveCourses = $inActiveCourses->count();
    $tototalStudents = \App\Models\Student::get()->count();

    //dd($results);

@endphp

<x-template title="Admin Dashboard" nav="home">
    <x-wrapper>
      

        <div class="dashboard-cards col-span-1">
            <div class="box card-purple">
                <div class="card-box">
                    <span class="material-symbols-rounded">
                        groups
                    </span>
                </div>
                <div class="box-body rounded-lg flex flex-col w-full text-right justify-end">
                    <div class="card-session">Students</div>
                    <div class="card-counter">24</div>
                </div>


            </div>

            <div class="box card-orange">
                <div class="card-box">
                    <span class="material-symbols-rounded">
                        book
                    </span>
                </div>
                <div class="box-body rounded-lg flex flex-col w-full text-right justify-end">
                    <div class="card-session">Courses</div>
                    <div class="card-counter">{{ $totalActiveCourses }}</div>
                </div>

                @if($totalInactiveCourses > 0) 
                  <div class="box-footer">
                    {{ $totalInactiveCourses }} inactive {{str_plural('course', $totalInactiveCourses)}}
                  </div>

                @endif



            </div>

            <div class="box card-blue">
                <div class="card-box">
                    <span class="material-symbols-rounded">
                        bar_chart
                    </span>
                </div>
                <div class="box-body rounded-lg flex flex-col w-full text-right justify-end">
                    <div class="card-session">Results</div>
                    <div class="card-counter">23</div>
                </div>


            </div>

            <div class="box card-green">
                <div class="card-box">
                    <span class="material-symbols-rounded">
                        people
                    </span>
                </div>
                <div class="box-body rounded-lg flex flex-col w-full text-right justify-end">
                    <div class="card-session">Students</div>
                    <div class="card-counter">{{$tototalStudents}}</div>
                </div>
                <div class="box-footer">
                    5 New Students
                </div>


            </div>

        </div>





        <div class="flex flex-col lg:flex-row gap-1 lg:gap-8">
            <div class="flex-1">
                <div class="box border-t-[6px] border-zinc-400 dark:border-zinc-700">
                    <div class="box-header">
                        <h2>Student Survey</h2>
                    </div>
                    <div class="box-body min-h-[365px]">
                        <canvas data-label="Student Survey" class="flex-1 object-fit" id="barChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>

            <div class="flex-1">
                <div class="box border-t-[6px] border-zinc-400 dark:border-zinc-700">
                    <div class="box-header">
                        <h2>Student Performance Chart</h2>
                    </div>
                    <div class="box-body min-h-[365px]">
                      
                      <canvas class="flex-1 object-fit" id="pieChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-5">
            <div class="flex-1">
                <div class="box border-t-[6px] border-zinc-400 dark:border-zinc-700">
                    <div class="box-header">
                        <h2>Exam Schedule</h2>
                    </div>
                    <div class="box-body flex flex-col gap-2">
                        <div class="bg-gray-500/20 px-2 py-1.5 rounded-lg flex justify-between">
                            <span class="pr-2">CSC 502</span>
                            <span>23-10-2024 @ 10:30am-02:30pm</span>
                        </div>
                        <div class="bg-gray-500/20 px-2 py-1.5 rounded-lg flex justify-between">
                            <span class="pr-2">CSC 401</span>
                            <span>23-10-2024 @ 02:30pm-05:30pm</span>
                        </div>
                        <div class="bg-gray-500/20 px-2 py-1.5 rounded-lg flex justify-between">
                            <span class="pr-2">CSC 502</span>
                            <span>23-10-2024 @ 10:00am-12:20pm</span>
                        </div>
                        <div class="bg-gray-500/20 px-2 py-1.5 rounded-lg flex justify-between">
                            <span class="pr-2">CSC 502</span>
                            <span>23-10-2024 @ 10:30am-01:30pm</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1">
                <div class="box  border-t-[6px] border-zinc-400 dark:border-zinc-700">
                    <div class="box-header opacity-60 !pb-0 font-bold">Update Semester Info</div>
                    <form class="box-body flex flex-col gap-3">
                        <div>
                            <x-input type="text" name="session" id="session" placeholder="Session eg 2018/2019" />
                        </div>
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <x-input type="date" name="session" id="session"
                                    placeholder="Semester Start Date" />
                            </div>
                            <div class="flex-1">
                                <x-input type="date" name="session" id="session"
                                    placeholder="Semester Start Date" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Update</button>
                    </form>
                </div>
            </div>
        </div>
    </x-wrapper>
    <script src="{{asset('js/chart.js')}}"></script>

  <script src="{{asset('js/jchart.js')}}"></script>
  <script>

    chart('#barChart', {A: 4, B: 3, C: 10, D:20, E:5, F:2}, 'bar')
    chart('#pieChart', {A: 4, B: 3, C: 10, D:20, E:5, F:2}, 'pie')

    
    //chart.pieChart('#gradeChart');
  </script>
</x-template>