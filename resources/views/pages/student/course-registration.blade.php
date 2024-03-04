@php
    use App\Models\Course;
    $requestedLevel = request()->get('level');
    $requestedSemester = request()->get('semester');
    $requestedSession = request()->get('session');


    $chosenLevelAndSemester = $requestedLevel && $requestedSemester && $requestedSession;

    $user = \App\Models\User::active();
    $student = $user->student;
    $class = $student->class;

    $courses = Course::getCourses($_GET['level'] ?? null, $_GET['semester'] ?? null);
    $advisor = $student->advisor;
@endphp
<x-template nav='courses'>
    <div class="h-avail" ng-controller="CourseRegistrationController">
        <x-page-header>
            Course Registration
        </x-page-header>
        @if (!$chosenLevelAndSemester)
            <div class="grid place-items-center min-h-full">
                <form class="popup-wrapper -top-[50%] translate-y-[60%] relative" action="?">
                    <div class="popup-header">
                        Course Registeration
                    </div>
                    <div class="popup-body flex flex-col gap-3">
                        <div>
                            <label for="semester" class="font-semibold">Semester</label>
                            <select id="semester" name="semester" class="input">
                                <option value="harmattan" ng-selected="'{{ $requestedSemester }}' == 'harmattan'">
                                    Harmattan</option>
                                <option value="rain" ng-selected="'{{ $requestedSemester }}' == 'rain'">Rain</option>
                            </select>
                        </div>
                        <div>
                            <label for="session" class="font-semibold">Session</label>
                            <select id="session" name="session" class="input">
                                @foreach (Course::generateSessions($class->start_year, $class->end_year + 3) as $session)
                                    <option value="{{ $session }}">{{ $session }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div>
                            <label class="font-semibold">Level</label>
                            <select name="level" class="input">
                                @foreach ([100, 200, 300, 400, 500] as $level)
                                    <option value="{{ $level }}" selected="{{ $level == $requestedLevel }}">
                                        {{ $level }} Level</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="popup-footer">
                        <button type="submit" id="retrieve-courses" class="btn btn-primary">Proceed</button>
                    </div>
                </form>
            </div>
        @else
            <div class="">
                <div id="course-registration-container" class="flex flex-col gap-5 overflow-y-visible">
                    <div class="text-sm text-body-300 flex items-center justify-between">
                        <span :class="{ 'hidden': units == 0 }">Total units selected:

                            <span class="font-semibold" ng-bind="units"
                                ng-class="{'text-red-500':units > maxUnits || units < minUnits, 'text-green-600':units < maxUnits && units > minUnits}"></span>
                            out of
                            <span class="font-semibold" ng-bind="maxUnits"></span>
                            max units
                        </span>
                        <span ng-class="{hidden:units>=0}">
                            Unit Range (
                            min: <span class="font-semibold" ng-bind="minUnits"></span>
                            max: <span class="font-semibold" ng-bind="maxUnits"></span>
                            )

                        </span>

                        <button type="button" class="btn btn-primary" ng-click="startBorrowing()">
                            Add/Borrow Courses
                        </button>
                    </div>
                    @if (request()->get('level') == 100)
                        <div class="text-xm py-5 text-red-500  italic">
                            You are required to choose either IGB or FRN
                        </div>
                    @endif

                    <form method="POST" action="{{ route('insert.courses') }}">
                        @csrf

                        <input type="hidden" name="session" value="{{ $requestedSession }}" />
                        <input type="hidden" name="level" value="{{ $requestedLevel }}" />
                        <input type="hidden" name="semester" value="{{ $requestedSemester }}" />

                        <div>
                            <table class="responsive-table min-w-full whitespace-nowrap">
                                <thead class="print:bg-black print:text-white">
                                    <th class="w-10">Select</th>
                                    <th class="w-20">Course Code</th>
                                    <th>Course Title</th>
                                    <th class="w-20">Units</th>
                                    <th class="w-20">Type</th>
                                </thead>
                                <tbody id="course-registeration-prepend">
                                    @foreach ($courses as $course)
                                        <tr ng-controller="CourseController" data-course-id="{{ $course->id }}"
                                            data-course-units="{{ $course->units }}">
                                            <td><x-checkbox name="course[]" value="{{ $course->id }}"
                                                    class="checkbox" ng-model="course{{ $course->id }}"
                                                    ng-click="push($event)" /></td>
                                            <td class="uppercase">{{ $course->code }}</td>
                                            <td>{{ $course->name }}</td>
                                            <td>{{ $course->units }}</td>
                                            <td class="uppercase">
                                                {{ $option = $course->mandatory == 1 ? 'Compulsory' : 'Elective' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end">
                            <button x-bindx:disabled="!proceed && (units < minUnits || units > maxUnits)" type="submit"
                                class="btn-primary">Register courses</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif







        <div ng-show="borrow_course" class="popup" title="Borrow Courses">
            <div class="popup-wrapper w-[80%] max-w-full">
                <div class="text-lg popup-header font-semibold">Borrow Course
                </div>
                <div class="popup-body">


                    <form class="flex w-full items-center justify-between gap-2 mb-5">
                        <div class="flex-1">
                            <input type="search" name="search" ng-model="borrowQuery" class="input"
                                placeholder="Enter Course Code or Title (eg: CSC 501)" />

                        </div>
                        <div>
                            <button type="button" class="btn btn-primary"
                                ng-click="SearchCourse($event)">Search</button>
                        </div>

                    </form>


                    <div id="course-registration-container" class="flex flex-col gap-5">
                        <div class="text-body-300 flex items-center justify-between text-xs">
                            <p>Total units selected:
                                <span class="font-semibold" ng-bind="borrowingUnits + units"
                                    ng-class="{'text-red-500':units > maxUnits || units < minUnits, 'text-green-600':units < maxUnits && units > minUnits}"></span>
                                out of
                                <span class="font-semibold" ng-bind="maxUnits"></span>
                                max units
                            </p>

                            <a href="./course-registration-borrow-courses.html" class="opacity-0 -z-10">
                                <button type="button"
                                    class="btn bg-[var(--primary)] rounded text-white hover:bg-[var(--primary-700)] transition text-xs">
                                    Add/Borrow Courses
                                </button>
                            </a>
                        </div>



                        <div id="student-table-container">

                            <table class="table w-[100%] lg:w-[80%] whitespace-nowrap">
                                <thead>
                                    <tr>
                                        <th class="w-10">Select</th>
                                        <th class="w-20">Course Code</th>
                                        <th>Course Title</th>
                                        <th class="w-20">Units</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr ng-repeat="borrow_course in borrowedCourses track by borrow_course.id"
                                        data-id="{% borrow_course.id %}" data-code="{% borrow_course.code %}"
                                        data-name="{% borrow_course.name %}" data-units="{% borrow_course.units %}">
                                        <td>
                                            <x-checkbox ng-click="borrow($event)" name="borrow[]"
                                                ng-checked="true" />
                                        </td>
                                        <td ng-bind="borrow_course.code"></td>
                                        <td ng-bind="borrow_course.name"></td>
                                        <td ng-bind="borrow_course.units"></td>
                                    </tr>

                                    <tr ng-repeat="course in borrowingCourses track by course.id"
                                        ng-show="!borrowedCourses[course.id]" data-id="{% course.id %}"
                                        data-code="{%course.code%}" data-name="{%course.name%}"
                                        data-units="{%course.units%}">
                                        <td><x-checkbox ng-click="borrow($event)" name="selectCourse" /></td>
                                        <td ng-bind="course.code"></td>
                                        <td ng-bind="course.name"></td>
                                        <td ng-bind="course.units"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>



                    </div>
                </div>
                <form action="/course_registration" method="POST" class="popup-footer" data-semester="{{$requestedSemester}}" data-session="{{$requestedSession}}" data-level="{{$requestedLevel}}">
                    
                    <input type="hidden" name="semester" value="{{$requestedSemester}}"/>
                    <input type="hidden" name="level" value="{{$requestedLevel}}"/>
                    <input type="hidden" name="session" value="{{$requestedSession}}"/>
                    @csrf
                    <button type="btn btn-white" ng-click="stopBorrowing()">
                        Cancel
                    </button>
                    <input type="hidden" name="courses[]" id="courses"/>
                    <button type="button" class="btn btn-primary" ng-click="saveBorrowedCourses($event)">
                        Borrow Courses
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-template>
