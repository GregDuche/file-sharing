var app = angular.module('app', ['ngRoute', 'ngResource', 'flow', 'ui.bootstrap']);

app.config(['flowFactoryProvider', function (flowFactoryProvider) {
    flowFactoryProvider.factory = fustyFlowFactory;
}]);


app.config(['$routeProvider', function($routeProvider) {
  $routeProvider
   .when('/', {
    templateUrl: '/angular/file-sharing/share.html',
    controller: 'FileSharing'
  })
  .otherwise({
	 redirectTo: '/'
   });
}]);







