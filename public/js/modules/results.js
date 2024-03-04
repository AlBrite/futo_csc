import { Location } from "../extend.js";
import { app } from "../myApp.js";

app.controller('ResultsController', function($scope) {
  $scope.semester = Location.get('semester');
  $scope.session = Location.get('session');
  $scope.level = Location.get('level');
  $scope.courses = [];
  $scope.class_id = Location.get('class_id');
  $scope.class = null;
  $scope.course = null;
  $scope.sessions =  [];


  $scope.selectSemesterAndSuggestCourses = () => {
    if($scope.semester && $scope.session) {
      api('/enrolledCourses', {
        semester: $scope.semester,
        session: $scope.session
      })
      .then(response => {
        $scope.courses = response;
        console.log(response);
        $scope.$apply();
      })
    }
  }
  
  $scope.setClass = () => {
    if ($scope.class_id) {
      api('/class', {
        class_id: $scope.class_id
      })
      .then(res => {
        let classes = [];
        for(let year = res.start_year; year < res.start_year+5; year++) {
          classes.push(`${year}/${year+1}`)
        }
        
        $scope.sessions = classes;
        console.log(classes);
        $scope.$apply();
      })
      .catch(error=>console.error(error))
      
    }
  
  
  }
  
  $scope.fetchCourse = () => {
      if ($scope.semester && $scope.level) {
        api_get('/courses', {
          semester: $scope.semester,
          level: $scope.level
        })
        .then(response => {
          $scope.courses = response;
          $scope.$apply();
        })
      }
  }


});

app.directive('resultTableSkeleton', function(){
  return {
    template: `<div class="loading-skeleton">
    <table class="visible-on-print print:text-black responsive-table whitespace-nowrap w-full lg:!w-[300px]">
        <thead class="print:bg-white print:text-black">
            <tr>
                <th class="w-10"><span style="width:24px" class="skeleton"></span></th>
                <th><span style="width:39px" class="skeleton"></span></th>
                <th><span style="width:58px" class="skeleton"></span></th>
                <th class="w-10"><span style="width:59px" class="skeleton"></span></th>
                <th class="w-10"><span style="width:28px" class="skeleton"></span></th>
                <th class="w-10"><span style="width:25px" class="skeleton"></span></th>
                <th class="w-10"><span style="width:38px" class="skeleton"></span></th>
                <th class="w-10"><span style="width:33px" class="skeleton"></span></th>
                <th class="w-10"><span style="width:41px" class="skeleton"></span></th>
                <th class="w-10"><span style="width:52px" class="skeleton"></span></th>
            </tr>
        </thead>
        <tbody>
          

                                                        
                <tr>

                    <td><span style="width:8px" class="skeleton"></span></td>
                    <td><span style="width:104px" class="skeleton"></span></td>
                    <td><span style="width:88px" class="skeleton"></span></td>
                    <td align="center"><span style="width:31px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:10px" class="skeleton"></span></td>
                    <td align="center"><span style="width:65px" class="skeleton"></span></td>
                </tr>
                                                            
                <tr>

                    <td><span style="width:8px" class="skeleton"></span></td>
                    <td><span style="width:121px" class="skeleton"></span></td>
                    <td><span style="width:84px" class="skeleton"></span></td>
                    <td align="center"><span style="width:31px" class="skeleton"></span></td>
                    <td align="center"><span style="width:8px" class="skeleton"></span></td>
                    <td align="center"><span style="width:8px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:10px" class="skeleton"></span></td>
                    <td align="center"><span style="width:65px" class="skeleton"></span></td>
                </tr>
                                                            
                <tr>

                    <td><span style="width:8px" class="skeleton"></span></td>
                    <td><span style="width:150px" class="skeleton"></span></td>
                    <td><span style="width:86px" class="skeleton"></span></td>
                    <td align="center"><span style="width:31px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:8px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:9px" class="skeleton"></span></td>
                    <td align="center"><span style="width:65px" class="skeleton"></span></td>
                </tr>
                                                            
                <tr>

                    <td><span style="width:8px" class="skeleton"></span></td>
                    <td><span style="width:89px" class="skeleton"></span></td>
                    <td><span style="width:84px" class="skeleton"></span></td>
                    <td align="center"><span style="width:31px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:8px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:9px" class="skeleton"></span></td>
                    <td align="center"><span style="width:65px" class="skeleton"></span></td>
                </tr>
                                                            
                <tr>

                    <td><span style="width:8px" class="skeleton"></span></td>
                    <td><span style="width:81px" class="skeleton"></span></td>
                    <td><span style="width:86px" class="skeleton"></span></td>
                    <td align="center"><span style="width:31px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:14px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:9px" class="skeleton"></span></td>
                    <td align="center"><span style="width:65px" class="skeleton"></span></td>
                </tr>
                                                            
                <tr>

                    <td><span style="width:8px" class="skeleton"></span></td>
                    <td><span style="width:116px" class="skeleton"></span></td>
                    <td><span style="width:86px" class="skeleton"></span></td>
                    <td align="center"><span style="width:31px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:9px" class="skeleton"></span></td>
                    <td align="center"><span style="width:65px" class="skeleton"></span></td>
                </tr>
                                                            
                <tr>

                    <td><span style="width:8px" class="skeleton"></span></td>
                    <td><span style="width:116px" class="skeleton"></span></td>
                    <td><span style="width:86px" class="skeleton"></span></td>
                    <td align="center"><span style="width:31px" class="skeleton"></span></td>
                    <td align="center"><span style="width:8px" class="skeleton"></span></td>
                    <td align="center"><span style="width:8px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:10px" class="skeleton"></span></td>
                    <td align="center"><span style="width:65px" class="skeleton"></span></td>
                </tr>
                                                            
                <tr>

                    <td><span style="width:8px" class="skeleton"></span></td>
                    <td><span style="width:77px" class="skeleton"></span></td>
                    <td><span style="width:86px" class="skeleton"></span></td>
                    <td align="center"><span style="width:31px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:16px" class="skeleton"></span></td>
                    <td align="center"><span style="width:9px" class="skeleton"></span></td>
                    <td align="center"><span style="width:65px" class="skeleton"></span></td>
                </tr>
                                                            
                
        </tbody>
    </table>
</div>`
  }
})
