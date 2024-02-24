<head>
  <script src="{{asset('js/angular.js')}}"></script>
</head>

<body ng-cloak ng-app="myapp" w3-test-directive>
  <div ng-controller="HelloController">
    <p>Name : <input type="text" ng-model="name"></p>
    <h1>Hello {% name %}</h1>
    <p>Total in dollar: {% quantity * cost %}</p>
    <h2>Welcome {% helloTo.title %} to the world of Tutorialspoint! {% 10 + 2 %}</h2>
  </div>


  <div ng-controller="HelloController">
    <p>Name : <input type="text" ng-model="name"></p>
    <h1>Hello {% name %}</h1>
    <p>Total in dollar: {% quantity * cost %}</p>
    <h2>Welcome {% helloTo.title %} to the world of Tutorialspoint! {% 10 + 2 %}</h2>
  </div>

 <style>
  .classR {
    color: red;
  }
  </style>
  <div ng-controller="BrightController">
    <h2 ng-show="visible()">Welcome {% helloTo.title %} to the world of Tutorialspoint! {% 10 + 2 %}</h2>
    <button ng-disabled="disabled()" ng-class="{'classR': visibleColor}">click</button>
  </div>

 

  <form method="POST">
    <input type="submit" value="submit"/>
  </form>


</body>

</html>