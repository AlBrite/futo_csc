import { app } from "../myApp.js";
import { Location } from "../extend.js";

app.controller('CourseController', function($scope){
  $scope.level = Location.get('level');
  $scope.semester = Location.get('semester');
  $scope.active_id = Location.get('course_id');
  $scope.active_course = null;
  $scope.course = null;
  $scope.courses = [];
  $scope.data = {};
  $scope.showCourseForm = null;
  $scope.editData = null;
  $scope.data={};
  $scope.check=false;



  $scope.loadCourseOnChange  = async () => {
    if ($scope.level && $scope.semester) {
      try {
        const res = await api('/courses', {
          level: $scope.level,
          semester: $scope.semester
        });
        $scope.active_id = null;
        $scope.active_course = null;
        $scope.courses = res;
        $scope.$apply();
        Location.set({
          level: $scope.level,
          semester: $scope.semester
        });
      } catch(error) {
        console.log(error);
      }
    }
  }

  $scope.back = () => {
    $scope.active_course = null;
    $scope.active_id = null;
    Location.drop('course_id')
  }


  $scope.loadCourse = (event) => {
    let element = $(event.target);
    if (!element.is('.eachcourse')) {
      element = element.closest('.eachcourse');
    }
    const course_id = element.data('id');


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
        $scope.$apply();
      })
      .catch(error => console.error(error));
    } catch(e){
      console.log(e);
    }
  }


  $scope.updateCourse = () => {
    if ($scope.active_id) {
      try {
        api('/course', {
          course_id: $scope.active_id
        })
        .then(response=> {
          $scope.editData = response;
          $scope.$apply();
        })
        .catch(error => console.error(error));
      } catch(e){}
    }
  }

  $scope.init = () => {
    $scope.courses = [];
    
    if ($scope.level && $scope.semester) {
       api('/courses', {
         level: $scope.level,
         semester: $scope.semester
       })
       .then(res=>{
        $scope.courses=res
        $scope.$apply();
       })
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
         $('[data-id=68]').focus();
         $scope.$apply();
       })
       .catch(error => console.error(error));
     }
  }


  $scope.suggestLevelAndSemester = () => {
    
      if (!$scope.data.code) {
          return;
      }
      const match = $scope.data.code.trim().match(/([1-5])[0-9]([1-9])$/);
        if (match) {
          $scope.data.level = parseInt(match[1]) * 100;
          $scope.data.semester = parseInt(match[2]) % 2 == 0 ? 'rain' : 'harmattan';
      }
  }



$scope.addCourse = (event) => {
  return;
  event.preventDefault();

  api('/course/create', $scope.data)
    .then(response=>{
      this.course = response;
      $scope.clear();
      console.log(this.course);
    })
    .catch(error=>console.log(error));

  
}

  

  $scope.clear = () => {
    $scope.editData = null;
    $scope.showCourseForm = null;
  };
});



// app.directive('viewCourseSkeleton', function() {
//   return {
//     template: ``
//   }
// });

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

  

 

  

});

document.querySelectorAll('.displayCourse[course_id]').forEach(element => {
  element.addEventListener('click', () => {
    const course_id = element.getAttribute('course_id');

    Location.load('show-course', {
      course_id
    });

  });
});