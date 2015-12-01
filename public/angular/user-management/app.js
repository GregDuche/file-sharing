var app = angular.module('app', ['ngRoute', 'ngResource', 'ngTable']);

app.config(['$routeProvider', function($routeProvider) {
  $routeProvider
   .when('/', {
    templateUrl: '/angular/user-management/users.html',
    controller: 'Users'
  })
  .otherwise({
	 redirectTo: '/'
   });
}]);







