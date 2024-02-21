@props(['title', 'nav'])

@php
    $defaults = ['title'=>'CSC Admin Portal', 'nav'=>''];
    foreach($defaults as $default=>$value) {
      if (!isset($$default)) {
        $$default = $value;
      }
    }
    $user = \App\Models\User::active();
    $student = $user->student;
    $records = $student->courses;
    
    $set = $student->academicSet;
    $advisor = $set->advisor;
    $enrolledCourses = $student->courses;

    
  $role = $user?->role;
  $profile = $user->$role;
    $cgpa = \App\Models\AcademicRecord::calculateCGPA($student);

    exit;
@endphp
<x-layout nav="{{$nav}}" title="{{$title}}">

  <div class="lg:flex lg:gap-4 mx-4">
    <div class="flex-1">
      {{$slot}}

    </div>
    <div class="w-96 p-4 hidden lg:block">
      <div class="scroller">
        <div class="rounded border border-slate-400 flex flex-col gap-4 items-center px-4 py-16">
            <x-profile-pic :user="$profile" src="{{asset('images/user.jpg')}}" alt="Student-Image" class="object-cover aspect-square rounded-full w-32"/>


            <div class="flex flex-col items-center gap-1 text-center">
                <h1 class="text-body-800 text-2xl">
                    {{$user->name}}
                </h1>
                <p class="text-slate-800">
                    {{$profile->reg_no??$profile->staff_id}}
                </p>
                <p class="text-slate-600 text-sm">
                    Class:
                    <span class="font-semibold text-slate-800">
                        {{$set->name}}
                    </span>
                </p>
                @if($role === 'student')
                  <p class="text-slate-600 text-sm">Role:
                      <span class="font-semibold text-slate-800">
                          {{$role}}
                      </span>
                  </p>
                @else 

                @endif
            </div>
        </div>

        <div class="bg-primary-50 rounded flex items-center justify-center p-1 gap-4">
            <div class="flex flex-col items-center justify-end">
                <p class="text-secondary-800 text-lg">{{$set->name}}</p>
                <p class="text-body-300">session</p>
            </div>
            <span class="bg-secondary-800 w-[1px] h-10"></span>
            <div class="flex flex-col items-center justify-end">
                <p class="text-secondary-800 text-lg">Harmattan</p>
                <p class="text-body-300">semester</p>
            </div>
        </div>

        <div class="notice-card bg-primary-50 dark:bg-green-950/40 rounded w-full h-full pl-4 pr-14 pt-10 pb-4 flex flex-col justify-between items-start relative ">
            <div class="relative z-10">
              <p class="text-lg text-secondary-800 dark:text-orange-300 z-10">
                  XX days left for course registration, register now to access your results
              </p>
              <a href="./course-registration.html" class="relative z-1">
                  <button type="button"
                      class="btn bg-[var(--primary)] rounded text-white hover:bg-[var(--primary-700)] transition">
                      Register Courses
                  </button>
              </a>
            </div>
            <img src="{{asset('svg/frame.svg')}}" alt="frame" class="absolute bottom-0 right-0">
        </div>
    </div>

  </div>
    
   
    
</x-layout>