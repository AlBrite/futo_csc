@php
    $user = \App\Models\User::active();
    $student = $user->student;
    $records = $student->courses;
    $set = $student->academicSet;
    $enrolledCourses = $student->courses;

@endphp
<x-template nav="home" title="Student Dashboard">
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
        <div class="">
            <h1 class="text-lg text-body-300 font-semibold">Dashboard</h1>
            <div class="flex flex-col gap-3 lg:gap-5">
                <div class="courses mt-2 grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- DASHBOARD CARD -->
                    <div class="col-span-1 overflow-hidden bg-primary-50 rounded-md h-40 p-4 flex flex-col justify-between shadow">
                        <div class="flex items-center gap-2 text-black-300">
                            <span class="material-symbols-rounded">
                                auto_stories
                            </span>
                            <p>Courses Registered</p>
                        </div>
                        <div class="flex justify-end text-primary-300">
                            <p class="text-[2.5rem] font-semiboold">71</p>
                        </div>
                    </div>

                    <div
                        class="col-span-1 overflow-hidden bg-secondary-50 dark:bg-orange-900 rounded-md h-40 p-4 flex flex-col justify-between shadow">
                        <div class="flex items-center gap-2 text-black-300 text-secondary-300">
                            <span class="material-symbols-rounded">
                                bar_chart
                            </span>
                            <p>Results Published</p>
                        </div>
                        <div class="flex justify-end text-secondary-300">
                            <p class="text-[2.5rem] font-semiboold">55</p>
                        </div>
                    </div>

                    <div class="col-span-1 overflow-hidden bg-danger-50 rounded-md h-40 p-4 flex flex-col justify-between shadow">
                        <div class="flex items-center gap-2 text-black-300">
                            <span class="material-symbols-rounded">
                                grade
                            </span>
                            <p>CGPA</p>
                        </div>
                        <div class="flex justify-end text-danger-300">
                            <p class="text-[2.5rem] font-semiboold">{{ auth()->user()->student->calculateCGPA() }}</p>
                        </div>
                    </div>
                </div>

                <div class="box">
                    <h1 class="box-header text-body-300 font-semibold !pt-10 !pl-10">Semester Courses</h1>

                    <div class="box-body">


                        <table class="responsive-table table-auto">
                            <thead>
                                <th class="w-20">Course Code</th>
                                <th>Course Title</th>
                                <th class="w-20 text-center">Units</th>
                                <th class="w-20">Type</th>
                                <th class="w-20"></th>
                            </thead>
                            <tbody>

                                @foreach ($records as $record)
                                    <tr>
                                        <td class="uppercase">{{ $record->course->code }}</td>
                                        <td>{{ $record->course->name }}</td>
                                        <td class="text-center">{{ $record->course->units }}</td>
                                        <td class="uppercase">
                                            {{ $record->course->mandatory === 0 ? 'ELECTIVE' : 'COMPULSORY' }}</td>
                                        <td>

                                            <a href="/course/{{ $record->course->id }}"
                                                class="text-xs font-semibold p-[.3rem] rounded text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition inline-block text-center">View
                                                details</a>

                                            <!-- <button id="{{ $record->course->id }}" @click="retrieveCourse, courseId={{ $record->course->id }}"
                                            class="text-xs font-semibold p-[.3rem] rounded text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition"
                                            type="button">
                                            View details
                                        </button> -->
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    @endif
</x-template>
