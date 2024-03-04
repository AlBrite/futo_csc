@php 
$advisor = \App\Models\Advisor::active();
$authUser = auth()->user();
$classes = $authUser->profile->classes()->with('students')->get();
$class = $classes->first();
$students = $class->students();
$number_of_students_in_class = $students->count();
$allStudents = $students->cursorPaginate(5);
$number_of_semester_courses = 5;
$results = \App\Models\Result::with('course')->orderBy('level', 'desc');
$totalResultsUploaded = $results->count();

$results = $results->cursorPaginate(15);





@endphp
<x-template nav="home" title="Advisor Dashboard">


    <div class="scroller">

        <x-page-header>Dashbaord</x-page-header>

        <div class="courses mt-2" id="dashboard-cards">
            <!-- DASHBOARD CARD -->
            <div class="overflow-hidden bg-green-300 dark:bg-green-800 rounded h-40 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-2 text-black-300 dark:text-white/50">
                    <span class="material-symbols-rounded">
                        groups
                    </span>
                    <p class="text-lg">Students</p>
                </div>
                <div class="flex justify-end text-primary-300">
                    <p class="text-[2.5rem] font-semiboold">{{$number_of_students_in_class}}</p>
                </div>
            </div>

            <div class="overflow-hidden bg-orange-300 dark:bg-orange-800 rounded h-40 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-2 text-black-300 dark:text-white/50">
                    <span class="material-symbols-rounded">
                        auto_stories
                    </span>
                    <p class="text-lg">Semester Courses</p>
                </div>
                <div class="flex justify-end text-secondary-300">
                    <p class="text-[2.5rem] font-semiboold">71</p>
                </div>
            </div>

            <div class="overflow-hidden bg-red-300 dark:bg-red-800 rounded h-40 p-4 flex flex-col justify-between">
                <div class="flex items-center gap-2 text-black-300 dark:text-white/50">
                    <span class="material-symbols-rounded">
                        bar_chart
                    </span>
                    <p class="text-lg">Results Uploaded</p>
                </div>
                <div class="flex justify-end text-danger-300">
                    <p class="text-[2.5rem] font-semiboold">{{$totalResultsUploaded}}</p>
                </div>
            </div>
        </div>
        
        <x-todo/>

        

        
        <h1 class="text-lg text-body-300 font-semibold mt-8 flex gap-1">
            Top Five
            <span class="material-symbols-rounded">star</span>
        </h1>

        <div class="mt-2 overflow-x-auto max-w-full min-w-full">
            <table class="responsive-table min-w-full whitespace-nowrap">
                <thead>
                    <th class="min-w-16"></th>
                    <th>Student Name</th>
                    <th>Reg. Number</th>
                    <th class="w-20">Level</th>
                    <th class="w-20">CGPA</th>
                </thead>
                <tbody>
                    @foreach($allStudents as $student)
                        <tr>
                            <td align="center">
                            <x-profile-pic :user="$student" alt="student_pic" class="w-10 h-10 rounded-full object-cover"/>
                            </td>
                            <td>{{$student->user->name}}</td>
                            <td>{{$student->reg_no}}</td>
                            <td>{{$student->level}}</td>
                            <td>{{$student->calculateCGPA()}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!--  -->

        <h1 class="text-lg text-body-300 font-semibold mt-8">Results Uploaded</h1>
        
        <div class="mt-2 overflow-x-auto max-w-full min-w-full">
            <table class="responsive-table min-w-full whitespace-nowrap">
                <thead>
                    <th class="w-20">Course Code</th>
                    <th>Course Title</th>
                    <th class="w-20">Units</th>
                    <th class="w-20"></th>
                </thead>
                <tbody>
                    @foreach($results as $result)
                    
                        <tr>
                            <td class="uppercase">{{$result->course->code}}</td>
                            <td>{{$result->course->name}}</td>
                            <td>{{$result->course->unit}}</td>
                            <td>
                                <a class="btn-primary" href="/display_results?session={{$result->session}}&semester={{$result->semester}}&course=all">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>

</x-template>