@php
    use App\Models\Course;

    $level = request()->get('level');
    $semester = request()->get('semester');

    $courses = Course::getCourses($level, $semester);

    $getcourse = false;
    $course_id = request()->get('course_id');
    $mobileClose = '';
    if ($course_id) {
        $getcourse = Course::find($course_id);
        $mobileClose = 'hidden lg:block';
    }
    $semester_data = $semester ? "'$semester'" : 'null';
    $level_data = $level ? "'$level'" : 'null';
    $active_id = $course_id ? "'$course_id'" : 'null';

@endphp
<x-template nav="courses" name="dashboard"
    data="editData: null, active_course: null, active_id: {!! $active_id !!}, level: {!! $level_data !!}, semester: {!! $semester_data !!}, courses: null, file_url: null,files: [], dragging: false, editStudent: false">



    <div ng-controller="CourseController" ng-init="init()"
        class="lg:flex justify-between items-stretch max-h-full overflow-hidden">
        <div ng-class="{'hidden lg:block':active_course}" class="left-column lg:w-[60%] ">
            <div class="scroller">
                <div class="lg:p-8">

                    <form class="grid grid-cols-3 place-items-center gap-3 w-full flex-wrap mb-4">
                        <div class="select mr-2">
                            <select name="level" id="level" ng-model="level" title="level" class="rounded" ng-model="level"
                                ng-change="loadCourseOnChange()" tips="Choose Level">
                                <option value="">Level</option>
                                <option value="100">100L</option>
                                <option value="200">200L</option>
                                <option value="300">300L</option>
                                <option value="400">400L</option>
                                <option value="500">500L</option>
                            </select>
                        </div>

                        <div class="select">
                            <select ng-disabled="!level" name="semester" id="semester" title="semester"
                                ng-model="semester" class="rounded" ng-change="loadCourseOnChange()" tips="Choose Semester">
                                <option value="">Semester</option>
                                <option value="harmattan">Harmattan</option>
                                <option value="rain">Rain</option>
                            </select>
                        </div>

                    </form>

                    <div ng-show="courses.length == 0" class="grid grid-cols-1 gap-3">
                        @for ($i = 0; $i < 4; $i++)
                            <div
                                class="loading-skeleton flex card border rounded-md overflow-clip cursor-pointer dark:border-gray-700 group-hover:bg-slate-500">
                                <div class="w-24 skeleton"></div>
                                <div class="p-2 flex flex-col gap-2 justify-center flex-1">
                                    <p class="text-transparent font-bold skeleton min-w-[100%]"> .</p>
                                    <span class=" skeleton w-[70%]">.
                                    </span>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div ng-class="{'hiddenx lg:grid': active_id && semester && level}"
                        ng-show="courses.length > 0" class="grid-cols-1">

                        <div ng-repeat="course in courses track by course.id">
                            <div ng-click="loadCourse($event)" data-id="{% course.id %}"
                                ng-class="{'active':course.id==active_id}"
                                class="group eachcourse lg:flex card border rounded-lg overflow-clip cursor-pointer dark:border-gray-700 group-hover:bg-slate-500 relative">
                                <img src="{{ asset('svg/course_image_default.svg') }}"
                                    class="w-full h-full lg:w-24 lg:h-24 object-cover" alt="course-image">
                                <div
                                    class="p-2 flex flex-col justify-center flex-1 absolute bottom-0 lg:relative bg-black/70 lg:bg-transparent w-full gap-2.5">
                                    <p class=" dark:text-white text-black font-bold" ng-bind="course.name"></p>
                                    <p class="flex items-center gap-1">
                                        <span
                                            class="text-white/65 lg:text-black/45 dark:lg:text-gray-300 weight-400 text-sm pr-2 border-r border-r-slate-[var(--body-300)] "
                                            ng-bind="course.code"></span>
                                        <span class="divider"></span>
                                        <span class="text-body-200 weight-400"><span ng-bind="course.units"></span>
                                            Units</span>
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>

        </div>
        <div class="lg:flex-1 right-column" ng-class="{'hidden lg:block':!active_course}">
            <div class="md:flex flex-col justify-between min-h-full dark:bg-zinc-800">
                <div class="scroller">
                    <div class="p-4">


                        <x-navigate>
                            <a href="{{ route('admin.add_course') }}" class="btn btn-secondary" tip="Click Here to add new Courses"
                                ng-click="showCourseForm=true">Add Course</a>
                        </x-navigate>




                        <div ng-cloak ng-show="active_course">

                            <div class="flex-1">
                                <div
                                    class="h-32 grid grid-cols-3 gap-2 border-slate-300 shrink-0 overflow-clip rounded-md mx-2.5 bg-white dark:bg-zinc-950">
                                    <img class="col-span-1 h-full object-cover"
                                        src="{{ asset('svg/course_image_default.svg') }}" alt="default_course_img">

                                    <div class="col-span-2 flex flex-col justify-center">
                                        <p class="text-lg font-semibold text-body-800 select-none whitespace-nowrap text-ellipsis overflow-hidden"
                                            ng-bind="active_course.name">
                                        </p>
                                        <p class="flex items-center select-none">
                                            <span
                                                class="text-sm text-body-400 pr-2 border-r border-r-slate-[var(--body-300)]"
                                                ng-bind="active_course.code">
                                            </span>
                                            <span
                                                class="text-sm text-body-300 pl-2 border-l border-l-slate-[var(--body-300)]">
                                                <span ng-bind="active_course.units"></span> units
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="h-32 p-2 flex flex-col gap-2 shrink-0">
                                    <p class="text-sm font-semibold text-body-300">
                                        Marks distribution
                                    </p>
                                    <!-- If the course has practical -->
                                    <div ng-cloak ng-show="active_course.practical > 0" class="flex flex-col gap-3">
                                        <div class="grid grid-cols-5">
                                            <span
                                                class="col-span-1 p-3 flex center font-bold bg-orange-200 dark:bg-orange-900">
                                                20%
                                            </span>
                                            <span class="col-span-1 p-3 flex center font-bold bg-secondary-200">
                                                20%
                                            </span>
                                            <span
                                                class="col-span-3 p-3 flex center font-bold bg-primary-200 dark:bg-green-800 rounded rounded-r-full">
                                                60%
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                                                <div class="w-3 h-3 bg-accent-200 rounded-full"></div>
                                                test
                                            </div>
                                            <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                                                <div class="w-3 h-3 bg-secondary-200 rounded-full"></div>
                                                practical
                                            </div>
                                            <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                                                <div class="w-3 h-3 bg-primary-200 rounded-full"></div>
                                                exam
                                            </div>
                                        </div>
                                    </div>
                                    <!-- If the course has practical -->

                                    <!-- If the course does not have practical -->
                                    <div ng-cloak ng-show="active_course.practical == 0" class="flex-col gap-3">
                                        <div class="grid grid-cols-10">
                                            <span
                                                class="col-span-3 p-3 flex center font-bold bg-orange-200 dark:bg-orange-900">30%</span>
                                            <span
                                                class="col-span-7 p-3 flex center font-bold bg-primary-200 dark:bg-green-800 rounded-r-full">70%</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                                                <div class="w-3 h-3 bg-accent-200 rounded-full"></div>
                                                test
                                            </div>
                                            <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                                                <div class="w-3 h-3 bg-primary-200 rounded-full"></div>
                                                exam
                                            </div>
                                        </div>
                                    </div>
                                    <!-- If the course does not have practical -->
                                </div>

                                <div style="height: calc(100dvh-22.5rem);" class="p-2 flex flex-col gap-2 shrink-0">
                                    <p class="text-sm font-semibold text-body-300">Course Description</p>
                                    <div class=" rounded overflow-y-auto p-1 text-sm text-body-500"
                                        ng-bind="active_course.outline">

                                    </div>
                                </div>

                            </div>
                            <div>
                                <a class="btn-primary"
                                    href="/admin/course/edit?course_id={% active_course.id %}&semester={% semeseter %}&session={% active_course.session %}&level={% active_course.level %}">Edit
                                    Course Details</a>
                            </div>
                        </div>

                        <div ng-show="!active_course" class="grid place-items-center">
                            <div class="flex-1 loading-skeleton w-full">


                                <div class="flex items-center gap-5 pr-3 shrink-0 rounded-md mx-2.5">
                                    <div class="h-32 w-[12rem] skeleton"></div>

                                    <div class="flex-1 flex flex-col gap-3 justify-center">
                                        <p class="skeleton w-[70%]">.
                                        </p>
                                        <p class="flex items-center skeleton w-[30%]">.

                                        </p>
                                    </div>
                                </div>

                                <div class="h-32 p-2 pt-5 flex flex-col gap-4 shrink-0">
                                    <p class="skeleton w-40">
                                        .
                                    </p>

                                    <div class="flex flex-col gap-3">
                                        <div class="">

                                            <div
                                                class="col-span-3 p-3 flex center font-bold skeleton rounded !rounded-r-full">
                                                60%
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                                                <div class="w-3 h-3  skeleton !rounded-full"></div>
                                                <span class="skeleton">test</span>
                                            </div>
                                            <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                                                <div class="w-3 h-3 skeleton !rounded-full"></div>
                                                <span class="skeleton">practical</span>
                                            </div>
                                            <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                                                <div class="w-3 h-3 skeleton !rounded-full"></div>
                                                <span class="skeleton">exam</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-2 pt-5">
                                    <span class="text-sm font-semibold text-body-300 skeleton">Course
                                        Description</span>
                                    <div
                                        class=" rounded overflow-y-auto pt-2 text-sm text-body-500 flex flex-col gap-5">
                                        <div class="skeleton">.</div>
                                        <div class="skeleton w-[70%]">.</div>
                                        <div class="skeleton w-[90%]">.</div>
                                        <div class="skeleton w-[50%]">.</div>



                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        #main-slot {
            padding: 0px;
        }
        html:not(.dark) #main-slot {
            background: #fff;
        }

        html:not(.dark) .left-column {
            background: rgb(250, 250, 250);
        }
        html:not(.dark) .right-column {
            background: rgba(228, 228, 231, 0.6);
        }
        
    </style>
    <script src="{{ asset('scripts/upload.js') }}"></script>

</x-template>
