import { app } from "../myApp.js";
import { getAttr, Location } from "../extend.js";

app.controller('AdvisorController', function($scope) {
    $scope.advisor_id = null;
    $scope.advisor = null;
    $scope.editAdvisor = null;
    $scope.addAdvisor = null;
    $scope.firstname = null;
    $scope.lastname = null;
    $scope.middlename = null;
    $scope.graduation_session = null;
    $scope.open = false;
    $scope.level = null;

    
    $scope.displayAdvisor = (advisor_id) => {
    
        $scope.advisor_id = advisor_id;
    
    
        api('/advisor', {advisor_id})
          .then(response => {
            Location.set({advisor_id});
            $scope.advisor = response;
            console.log(response);
          })
          .catch(error => console.log(error));
      
    }
    
    // $scope.changeSession = ($evt) => {
    //   const value = $evt.target.value;
    //   const yearMatcher = value.match(/^(\d+){4,4}\/(\d+){4,4}$/);

    //   if (yearMatcher) {
    //     const [ start, end ] = value.split('/').map(item => parseInt(item));
    //     console.log(yearMatcher);
    //     const end_session = `${start+5}/${end+5}`;
        
    //     $scope.graduation_session = end_session;
    
    
    //   }
    
    // }
    
    // $scope.handleAdvisorUpdate = () =>{
    //   if ($scope.advisor_id) {
    //     api('/advisor', {
    //       advisor_id: $scope.advisor_id
    //     })
    //     .then(res => {
    //       $scope.editAdvisor = res;
    //       const nameParts = res.user.name.split(" ");
    //       $scope.firstname = nameParts[0];
    //       $scope.lastname = nameParts.length > 1 ? nameParts[1] : '';
    //       $scope.middlename = nameParts.length > 2 ? nameParts[2] : '';
    //     })
    //     .catch(err => console.log(err))
    //   }
    // }
});

