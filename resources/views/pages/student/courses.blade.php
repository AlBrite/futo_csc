@php
    $user = auth()->user();
    $student = $user->student;
    $enrollments = $student->courseRegistrationPerSemester;

    $courses = true;

    $hasEnrolled = count($enrollments) > 0;
    $title = 'Enrolled Courses';
    if (!$hasEnrolled) {
        $title = 'Not Enrolled to any course';
    }

@endphp

<x-template title="{{ $title }}" nav='courses'>
    

        @if ($hasEnrolled)

            <x-page-header>
                Course Registration History

                <a href="/course-registration" class="btn-primary btn-sm">
                    Register Courses
                </a>
            </x-page-header>

            <div class="">
                <div class="box">
                    <div  class="box-wrapper w-full overflox-x-auto">
                    <table class="responsive-table min-w-full">
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Semester</th>
                                <th class="text-center">Level</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->session }}</td>
                                    <td class="sentence-case">{{ $enrollment->semester }}</td>
                                    <td class="text-center">{{ $enrollment->level }}</td>
                                    <td class="text-right">
                                        <a
                                            href="{{ route('view.enrollment', [
                                                'semester' => $enrollment->semester,
                                                'level' => $enrollment->level,
                                            ]) }}">

                                            <button class="text-xs btn btn-primary transition px-1 lg:px-2"
                                                type="button">
                                                View <span class="hidden lg:inline">Details</span>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>

                </div>
            </div>
        @else
            <div id="no-courses" class="flex h-full p-2 overflow-y-scroll relative flex-col gap-5 items-center">
                <img class="w-72" src="{{ asset('svg/no_courses.svg') }}" alt="no_courses_icon">
                <div class="flex flex-col items-center gap-5 text-center">
                    <p class="text-white-800">
                        Oops! It looks like you haven't registered for any courses yet. <br>
                        Register your courses before the deadline to ensure you can view them when they become
                        available.
                    </p>

                    <a href="/course-registration">
                        <button type="button"
                            class="btn bg-[var(--primary)] rounded text-white hover:bg-[var(--primary-700)] transition">
                            Register Courses
                        </button>
                    </a>
                </div>
            </div>
        @endif
   


</x-template>
