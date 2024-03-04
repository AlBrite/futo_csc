import { app } from "../myApp.js";
import { getAttr, Location } from "../extend.js";

app.controller('AdvisorController', function($scope) {

  
    $scope.advisor_id = Location.get('advisor_id');
    $scope.advisor = false;
    $scope.edit = null;
    $scope.add = null;
    $scope.firstname = null;
    $scope.lastname = null;
    $scope.middlename = null;
    $scope.graduation_session = null;
    $scope.open = false;
    $scope.level = null;
    $scope.addClass = false;
    $scope.classes = [];
    $scope.winLarge = window.innerWidth >= 1024;

    $scope.closeEditor = function() {
      $scope.edit = false;
    }
    $scope.openEditor = () =>{
      $scope.edit = true;
    }

    $scope.openAdder = function() {
      $scope.add = true;
    }
    
    $scope.show = (advisor_id) => {
    
        
        api('/advisor', {advisor_id})
          .then(response => {
            Location.set({advisor_id});
            $scope.advisor = response;
            
            $scope.$apply();
          })
          .catch(error => console.log(error));
      
    }

    $scope.init = () => {
      
      if ($scope.advisor_id) {

        api('/advisor', {advisor_id: $scope.advisor_id})
        .then(response => {

          $scope.advisor = response;
          $scope.$apply();
        })
        .catch(error => console.log(error));

      }

      
    }

    $scope.back = () => {
      $scope.advisor = null;
      $scope.advisor_id = null;
      Location.drop('advisor_id');
    }
   
  
   
    

    $scope.loadClasses = function() {
      api('/classes')
        .then(classes => {
          $scope.classes = classes;
          $scope.$apply();
        })
        .catch(error => log(error));
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
    
    
});

