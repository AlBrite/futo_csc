@php
    $students = \App\Models\Student::all();
    $results = \App\Models\Result::paginate(6);
    $student = $students[0];
    $student_id = request()->student_id;
    if (!is_numeric($student_id)) {
        $student_id = 'null';
    }

@endphp

<x-template nav="students">
    <div ng-init="init()" ng-controller="StudentController"
        class="lg:flex gap-5 px-0 justify-between items-stretch max-h-full min-h-full overflow-hidden">
        <div ng-class="{'lg-visible': student}"
            class="lg:w-[380px] lg:bg-zinc-50 lg:border-r lg:border-zinc-200 dark:border-none dark:bg-zinc-950/50">
            <div class="scroller" ng-controller="SearchController">
                <form class="flex items-center justify-between gap-2 w-full flex-wrap p-5">

                    <div class="flex-1">
                        <input type="search" ng-model="query" class="input w-full" ng-keyup="search()"
                            placeholder="Enter Student Name or Reg No" />
                    </div>

                </form>

                <div class="student-list" ng-show="results.length == 0">
                    @foreach ($students as $student)
                        <div student_id="{{ $student->id }}" ng-click="show($event)" class="student">
                            <x-profile-pic :user="$student" alt="student_pic"
                                class="w-16 h-16 rounded-md object-cover" />
                            <div class="flex-1">
                                <div class="font-2xl font-bold">{{ $student->user->name }}</div>
                                <div class="text-sm">{{ $student->reg_no }}</div>
                                <div class=" text-xs">

                                    <span class="pr-2 border-r border-slate-500/50">{{ $student->level }}
                                        Level</span><span class="pl-2">{{ $student->calculateCGPA() }} CGPA</span>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="student-list" ng-show="results.length > 0">
                    <div ng-repeat="student in results track by student.id">

                        <div student_id="{% student.id %}" ng-click="show($event)" class="student">
                            <img ng-src="/profilepic/{% student.id %}" alt="student_pic"
                                class="w-16 h-16 rounded-md object-cover" />
                            <div class="flex-1">
                                <div class="font-2xl font-bold">{% student.name %}</div>
                                <div class="text-sm">{% student.reg_no %}</div>
                                <div class=" text-xs">

                                    <span class="pr-2 border-r border-slate-500/50">{% student.level %}
                                        Level</span><span class="pl-2">{% student.cgpa %} CGPA</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="lg:flex-1 ">
            <div class="scroller">
                <div class="flex justify-between items-center">
                    <div class="lg:invisible flex items-center cursor-pointer" ng-click="back()">
                        <span class="material-symbols-rounded">arrow_back</span>
                        <span>Back</span>
                    </div>

                    <div>
                        <a href="/admin/student/store" class="btn-white" ng-click="openForm()">Add Student</a>
                    </div>


                </div>

                <div ng-hide="student" profile-skeleton></div>
                
                <div ng-show="student">

                    <div class="">
                        <div class="flex flex-col lg:m-5  lg:p-8">
                            <div
                                class=" flex flex-col lg:flex-row text-center justify-center gap-5 items-center lg:text-left lg:justify-start p-4">
                                <img src="{% student.image %}" class="w-28 h-28 object-cover rounded-full" />
                                <div>
                                    <p class="text-2xl lg:text-3xl font-bold mb-3" ng-bind="student.user.name"></p>
                                    <p class="font-bold" ng-bind="student.reg_no"></p>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div>
                                    <div class="p-4 my-2">
                                        <div class="font-bold mb-4">Basic Information</div>
                                        <div class="flex flex-col lg:flex-row justify-between flex-wrap gap-3">
                                            <div class="flex lg:flex-col">
                                                <span>Phone</span>
                                                <span class="font-semibold" ng-bind="student.user.phone"></span>
                                            </div>


                                            <div class="flex lg:flex-col gap-2">
                                                <span>Email</span>
                                                <span class="font-semibold" ng-bind="student.user.email"></span>
                                            </div>


                                            <div class="flex lg:flex-col gap-2">
                                                <span>Level</span>
                                                <span class="font-semibold" ng-bind="student.level"></span>
                                            </div>


                                            <div class="flex lg:flex-col gap-2">
                                                <span>CGPA</span>
                                                <span class="font-semibold" ng-bind="student.cgpa"></span>
                                            </div>



                                            <div class="flex lg:flex-col gap-2">
                                                <span>Address</span>
                                                <span class="font-semibold" ng-bind="student.address"></span>
                                            </div>



                                        </div>
                                    </div>


                                    <div class="p-4 my-2">
                                        <div class="font-bold mb-4">Progress</div>
                                        <div class="mt-2 lg:grid grid-cols-3 lg:gap-5">
                                            <!-- DASHBOARD CARD -->
                                            <div
                                                class="overflow-hidden grid-span-1 card-blue rounded-md p-4 flex flex-col justify-between ">
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-rounded">
                                                        groups
                                                    </span>
                                                    <p class="text-lg">Students</p>
                                                </div>
                                                <div class="flex justify-end">
                                                    <p class="text-[2.5rem] font-semiboold">20</p>
                                                </div>
                                            </div>

                                            <div
                                                class="overflow-hidden grid-span-1 card-green rounded p-4 flex flex-col justify-between">
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-rounded">
                                                        auto_stories
                                                    </span>
                                                    <p class="text-lg">Semester Courses</p>
                                                </div>
                                                <div class="flex justify-end">
                                                    <p class="text-[2.5rem] font-semiboold">71</p>
                                                </div>
                                            </div>


                                            <div
                                                class="overflow-hidden grid-span-1 card-red rounded p-4 flex flex-col justify-between">
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-rounded">
                                                        bar_chart
                                                    </span>
                                                    <p class="text-lg">Results Uploaded</p>
                                                </div>
                                                <div class="flex justify-end ">
                                                    <p class="text-[2.5rem] font-semiboold">49</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>

                                <div class="flex justify-end px-4 pb-3">
                                    <a href="/admin/student/store" class="btn-primary">
                                        Edit Student Details
                                    </a>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

</x-template>
