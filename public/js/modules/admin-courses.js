import { app } from "../myApp.js";
import { Location } from "../extend.js";

app.controller('CourseController', function($scope){
  $scope.level = null;
  $scope.semester = null;
  $scope.active_id = null;
  $scope.active_course = null;
  $scope.course = null;
  $scope.showCourseForm = null;


  $scope.getCourses = () => {
    if ($scope.level && $scope.semester) {
      api('/courses', {
        level: $scope.level,
        semester: $scope.semester
      })
      .then(res=>{
        setTimeout(() => {

          $scope.active_id=null;
          $scope.active_course=null;
          $scope.courses = res;

        }, 500);
      })
      .catch(error=>console.log(error));
    }
    $scope.course = $c
  }


  $scope.loadCourse = (event) => {
    let element = $(event.target);
    if (!element.is('.eachcourse')) {
      element = element.closest('.eachcourse[data-id]');
    }
    const course_id = element.data('id');


    alert(course_id);
    return;
    try {
     
      let queryParams = {course_id};
      if ($scope.semester) {
        queryParams.semester = $scope.semester;
      }
      if ($scope.level) {
        queryParams.level = $scope.level;
      }
      
    
      $scope.active_id = course_id;
      appendAndChangeLocation(queryParams)
      api('/course', {
        course_id
      })
      .then(response=> {
        $scope.active_course = response;
      })
      .catch(error => console.error(error));
    } catch(e){
      console.log(e);
    }
  }

  $scope.init = () => {
    $scope.courses = [];

    if ($scope.level && $scope.semester) {
       api('/courses', {
         level: $scope.level,
         semester: $scope.semester
       })
       .then(res=>$scope.courses=res)
       .catch(error=>console.log(error));
     }
     if ($scope.active_id) {
       appendAndChangeLocation({course_id:$scope.active_id})
       api('/course', {
         course_id: $scope.active_id
       })
       .then(response=> {
         console.log(response);
         //$scope.editData = response;
         $scope.active_course = response;
       })
       .catch(error => console.error(error));
     }
  }
});

app.controller("AdminCoursesController", function($scope){
  $scope.courseOpen = false;
  $scope.courseId = null;
  $scope.showCourseForm = false;
  $scope.editData = null;
  $scope.active_course = null;
  $scope.level = 500;
  $scope.courses=null;
  $scope.editStudent=false;
  console.log(this,$scope);

  $scope.selectedLevel = function() {
    return true;
  }

  
  // $scope.retrieveCourse = async () => {
  //   try {
  //     $scope.courseOpen = true;
  //     if ($scope.courseId) {
  //       const course = await api('/student_course_details_home', {
  //         course_id: this.courseId
  //       });
  //     }

  //   } catch(e){
  //     console.log(e);
  //   }
  // }

  

  // $scope.updateCourse = () => {
  //   if ($scope.active_id) {
  //     try {
  //       api('/course', {
  //         course_id: $scope.active_id
  //       })
  //       .then(response=> {
  //         $scope.editData = response;
  //       })
  //       .catch(error => console.error(error));
  //     } catch(e){}
  //   }
  // }

  

});

document.querySelectorAll('.displayCourse[course_id]').forEach(element => {
  element.addEventListener('click', () => {
    const course_id = element.getAttribute('course_id');

    Location.load('show-course', {
      course_id
    });

  });
});