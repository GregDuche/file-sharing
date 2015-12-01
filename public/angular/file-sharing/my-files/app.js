var app = angular.module('app', ['ngRoute', 'ngResource', 'flow', 'ui.bootstrap', 'ngTable']);

app.config(['flowFactoryProvider', function (flowFactoryProvider) {
    flowFactoryProvider.factory = fustyFlowFactory;
}]);


app.config(['$routeProvider', function($routeProvider) {
  $routeProvider
   .when('/', {
    templateUrl: '/angular/file-sharing/my-files/my-files.html',
    controller: 'MyFiles'
  })
  .otherwise({
	 redirectTo: '/'
   });
}]);







