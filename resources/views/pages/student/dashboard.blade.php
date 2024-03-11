@php

    $user = \App\Models\User::active();
    $student = $user->student;
    $records = $student->courses;
    $set = $student->academicSet;
    $enrolledCourses = $student->courses;
    $carryoverCourses = $student->carryoverCourses();
    $results = $student->results->count();
    $cgpa = $student->calculateCGPA();
    $materials = $student->getMaterials();
    
    //  $cgpa = $student->cgpa;
    /*
        size
        uploader
        course_code
        url
        type
        name
    */

    //    dd($materials);
@endphp
<x-template nav="home" title="Student Dashboard" minimize>
    <script src="{{ asset('js/apexchart.js') }}"></script>
    @if ($records && $records->count() === 0)
        <div id="no-courses" class="flex h-full p-2 overflow-y-scroll relative flex-col gap-5 items-center">
            <img class="w-72" src="{{ asset('svg/no_courses.svg') }}" alt="no_courses_icon" />
            <div class="flex flex-col items-center gap-5 text-center">
                <p class="text-white-800">
                    Oops! It looks like you haven't registered for any courses yet. <br>
                    Register your courses before the deadline to ensure you can view them when they become available.
                </p>

                <a href="/course-registration">
                    <button type="button"
                        class="btn bg-[var(--primary)] rounded text-white hover:bg-[var(--primary-700)] transition">
                        Register Courses
                    </button>
                </a>
            </div>
        </div>
    @else
        <div class="flex lg:ml-6 gap-5">
            <div class="flex-1">
                <div class="scroller">
                    <div class="flex flex-col gap-5 py-6">
                        

                        <div class="flex gap-5 h-full justify-between items-stretch">
                            <div class="flex-1 flex flex-col gap-6">
                                @include('charts.student-statistics')


                                <div>
                                    <div class="dashboard-cards !grid-cols-2 col-span-1">
        
        
                                        <div class="box card-orange">
                                            <div class="card-box">
                                                <span class="material-symbols-rounded">
                                                    book
                                                </span>
                                            </div>
                                            <div class="box-body rounded-lg flex flex-col w-full text-right justify-end">
                                                <div class="card-session">Courses Registered</div>
                                                <div class="card-counter">{{ $enrolledCourses->count() }}</div>
                                            </div>
        
        
        
        
                                        </div>
        
                                        <div class="box card-blue">
                                            <div class="card-box">
                                                <span class="material-symbols-rounded">
                                                    bar_chart
                                                </span>
                                            </div>
                                            <div class="box-body rounded-lg flex flex-col w-full text-right justify-end">
                                                <div class="card-session">Results</div>
                                                <div class="card-counter">{{$results }}</div>
                                            </div>
                                            @if ($count = count($carryoverCourses))
                                                <div class="box-footer">
                                                    {{ $count }} {{ str_plural('carryover', $count) }}
                                                </div>
                                            @endif
        
        
                                        </div>
        
        
        
                                    </div>
                                </div>
                            </div>
                            <div class="w-[36%]">
                                <div class="flex flex-col gap-5">
                                    <div class="shadow-lg text-white rounded-lg h-full p-8"
                                        style="background: radial-gradient(rgb(22, 163, 74), #19532e);">
                                        <div class=" text-4xl font-extrabold">GPA</div>
                                        <div class="font-semibold">Grading Point Average</div>
                                        <div class="text-7xl font-extrabold mt-5">
                                            {{ $cgpa }}
                                        </div>
                                    </div>

                                    <x-todo/>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shrink-0 lg:w-[280px] bg-green-50 dark:bg-zinc-800">
                
                <div class="scroller !overflow-y-scroll">
                    <div class="lg:py-5">
                        <x-calendar />

                        <div class="box">
                            <div class="box-header !pb-0 !text-xs">Materials</div>
                            <div class="box-body">

                                @foreach ($materials as $material)
                                
                                    <div class="flex justify-between gap-2 !text-xs w-full border-b border-zinc-300 dark:border-zinc-800 last:border-none py-2.5 last:pb-0">
                                        <div class="w-[calc(100%-3rem)] flex gap-2">
                                            <img src="{{asset('svg/icons/'.$material->extension.'.png')}}" class="w-5 h-5"/>
                                            <div class="flex-1">
                                                <p
                                                    class="font-semibold whitespace-nowrap text-ellipsis overflow-hidden w-[130px]">
                                                    {{ $material->name }}</p>
                                                <span class="font-extralight text-xs">.{{ $material->extension }}, {{ formatFileSize($material->size) }}</span>
                                                <p class="italic text-xs opacity-60">Shared {{timeago($material->created_at)}}</p>
                                            </div>
                                        </div>
                                        <div class="shrink-0 w-2.6rem">
                                            <x-tooltip label="Delete">
                                            <i class="material-symbols-rounded !text-sm !w-3.5 !h-3.5">delete</i>
                                            </x-tooltip>
                                            
                                            <x-tooltip label="Save">
                                            <a target="blank" rel="download" href="{{asset('storage/'.$material->url)}}" class="material-symbols-rounded !text-sm !w-3.5 !h-3.5">download</a>
                                            </x-tooltip>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <div class="cd-f">
                                {{$materials->links()}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
    @endif




    <style>
        #main-slot {
            padding: 0px;
            margin: 0px;
        }
    </style>

</x-template>
