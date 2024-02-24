@php 

$course = request()->get('course');
$class_id = request()->get('class');


@endphp

<x-template title="Admin - Results" nav="results" data="courses:null, class: null, sessions: [], selected: {}">
    
    <div class="p-4">
        <style>
            @media screen and (max-width: 367.5px ) {
                #search-label {
                    top: .3rem
                }
            }
            
        </style>
        
        <div id="advisor-results" class="flex items-center text-sm font-semibold gap-4 text-body-200">
            <a href="/results" class="active">View Results</a>
            <a href="/moderator/upload-results">Upload Results</a>
            <a href="/moderator/cgpa-summary-result">CGPA Summary Result</a>
            <a href="/moderator/add-result">Manually Add Results</a>
        </div>

        <div class="w-full mt-4" x-data="{semester:'{{$semester}}', course: false, session: '{{$session}}'}">
            <form method="get" class="flex items-center gap-x-2 w-full relative flex-wrap mb-4">
                @csrf
                <label for="student-search" class="text-body-200 absolute top-3 left-1" id="search-label">
                    <span class="material-symbols-rounded">search</span>
                </label>
                <input type="search" name="studentSearch" id="student-search" placeholder="Enter Name or Reg Number" class="input">
                
                <button type="submit"
                    class="btn-sm btn-primary">
                    Submit
                </button>
            </form>

            <form method="get" class="flex items-end flex-wrap gap-x-2" id="result-options-form">

                <div class="">
                    <label for="class">Class</label>
                    
                    <select x-on:change="setClass" name="class" id="class" class="w-full input">
                        <option value="" class>Select Class</option>
                        @if(auth()->user()->role == 'admin')
                            @foreach(\App\Models\Admin::academicSets() as $class)
                                <option value='{{$class->id}}' :selected="'{{$class->id}}'=='{{$class_id}}'">{{$class->name}}</option>
                            @endforeach
                        @else 
                            @foreach($classes as $class)
                                <option value='{{$class->id}}' :selected="'{{$class->id}}'=='{{$class_id}}'">{{$class->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            
                <div class="">
                    <label for="session">Session</label>
                    
                    <select x-on:change="session=$event.target.value" :disabled="sessions.length<1" name="session" id="session" class="w-full input" disabled>
                        <option value="" class>Select session</option>
                        <template x-for="session in sessions" :key="session">
                            <option value="" :selected="'{{$session}}'==session" :value="session" x-text="session"></option>
                        </template>
                    </select>
                </div>

                <div class="">
                    <label for="semester">Semester</label>
                    <select :disabled="!session" name="semester" id="semester" class="input" x-on:change="selectSemesterAndSuggestCourses" disabled>
                        <option value="">Select semester</option>
                        <option value="harmattan" :selected="'harmattan'==semester">Harmattan</option>
                        <option value="rain" :selected="'rain'==semester">Rain</option>
                    </select>
                </div>

                <div class="">
                    <label for="course">Course</label>
                    <select x-on:change="course=$event.target.value"  :disabled="!semester" name="course" id="course" class="input" disabled>
                        <option value="">Select course</option>
                        <option value="all">All courses</option>
                        <template x-for="course in courses" :key="course.id">
                            <option :value="course.id" x-text="course.course.code"></option>
                        </template>
                    </select>
                </div>

                <button :disabled="!course" type="submit" disabled class="btn-sm btn-primary !m-0">
                    View Result
                </button>
            </form>
        </div>
    </div>

    @if($course)
    @if($course === 'all')
        @include('results.all-results', ['class' => $class])
    @else 
        @include('results.course-results')
    @endif
    @endif
    

    
</x-template>