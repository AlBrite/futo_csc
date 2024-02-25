@php 
  use \App\Models\Course;

  $level = request()->get('level');
  $semester = request()->get('semester');

  $courses = Course::getCourses($level, $semester);

  $getcourse = false;
  $course_id = request()->get('course_id');
  $mobileClose = "";
  if ($course_id) {
    $getcourse = Course::find($course_id);
    $mobileClose = "hidden lg:block";
  }
$semester_data = $semester ? "'$semester'":'null';
$level_data = $level ? "'$level'":'null';
$active_id = $course_id ? "'$course_id'":'null';
  
@endphp 
<x-template nav="courses" name="dashboard" data="editData: null, active_course: null, active_id: {!!$active_id!!}, level: {!!$level_data!!}, semester: {!!$semester_data!!}, courses: null, file_url: null,files: [], dragging: false, editStudent: false">


  
  <div ng-controller="CourseController" ng-init="init()" class="lg:flex justify-between items-stretch max-h-full overflow-hidden" >
    <div class="lg:w-[380px] bg-green-50 dark:bg-black dark:text-white ">
      <div class="p-5">
        <form class="grid grid-cols-3 place-items-center gap-3 w-full flex-wrap">
          <div ng-bind="courses.length + ' courses'"></div>
            <div class="select mr-2">
                <select name="level" id="level" title="level" class="rounded" ng-model="level">
                    <option value="">Level</option>
                    <option value="100" {{$level==='100'?'selected':''}}>100L</option>
                    <option value="200" {{$level=='200'?'selected':''}}>200L</option>
                    <option value="300" {{$level=='300'?'selected':''}}>300L</option>
                    <option value="400" {{$level=='400'?'selected':''}}>400L</option>
                    <option value="500" {{$level=='500'?'selected':''}}>500L</option>
                </select>
            </div>
            
            <div class="select">
                <select ng-disabled="!level" name="semester" id="semester" title="semester" ng-model="semester"class="rounded">
                    <option value="">Semester</option>
                    <option value="harmattan">Harmattan</option>
                    <option value="rain">Rain</option>
                </select>
            </div>
            
            <div>
                
                <button
                ng-disabled="!semester" 
                type="button"
                ng-click="getCourses()"
                class="btn text-white bg-[var(--primary)] rounded hover:bg-[var(--primary-700)] text-sm p-[.3rem]" disabled>
                    View
                </button>
            </div>
        </form>


        <div class="scroller">
          <div class="course-loading-skeleton"></div>
            <div ng-class="{'hiddenx lg:block': active_id && semester && level}" ng-show="true" class="flex flex-col">
              
              <div ng-repeat="course in courses track by course.id">
                <div ng-click="loadCourse($event)" data_id="{% course.id %}" ng-class="{'active':course.id==active_id}" class="group eachcourse flex card border rounded-md overflow-clip cursor-pointer dark:border-gray-700 group-hover:bg-slate-500">
                  <img src="{{asset('svg/course_image_default.svg')}}" class="w-24 h-24 object-cover" alt="course-image">
                  <div class="p-2 flex flex-col justify-center flex-1">
                    <p class="text-black dark:text-white font-bold" ng-bind="course.name"></p>
                    <p class="flex items-center gap-1">
                        <span class="text-black/45 dark:text-gray-300 weight-400 text-sm pr-2 border-r border-r-slate-[var(--body-300)] " ng-bind="course.code"></span>
                        <span class="divider"></span>
                        <span class="text-body-200 weight-400"><span ng-bind="course.units"></span> Units</span>
                    </p>

                  </div>
                </div>
              </div>
            </div>
            
            
          
        </div>
      </div>
    </div>
    <div class="lg:flex-1 ">
      <div  class="md:flex flex-col justify-between min-h-full">
        <div class="scroller">
          <div class="p-4">
              <div class="flex justify-between items-center">
                <div class="lg:invisible flex items-center cursor-pointer"  ng-click="active_id=null;active_course=null">
                  <span class="material-symbols-rounded">
                    arrow_back
                  </span>
                  <span>Back</span>
                </div>

                <div>
                  <button class="btn-white" ng-click="showCourseForm=true">Add Course</button>
                </div>

                
              </div>
              <!--add course-->
              @include('popups.add-course')
              <!--/add course-->

              <!--show selected course-->
              @include('popups.show-course')
              <!--/show selected course-->
            
              
              
              
              
              
              <!--edit course-->
              @include('popups.edit-course')
              <!--/edit course-->
            </div>
        </div>
      </div>
    </div>
  </div>

        

  <script src="{{asset('scripts/upload.js')}}"></script>  

</x-template>
