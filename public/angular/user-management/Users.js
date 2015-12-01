app.controller('Users', function Users($scope, $window, $location, $route, $routeParams, $timeout, Users, NgTableParams){

	var init = function() {
		var payload = {};

		$scope.tableParams = new NgTableParams({
	        page: 1,            // show first page
	        count: 10           // count per page
	    }, {
	        //total: data.length, // length of data
	        getData: function ($defer, params) {

	        	var payload = { records: params.count(), page: params.page()}
	        	Users.get(payload, function(response) {
	        		var data = response.data
	        		$defer.resolve(data);
	        		params.total(response.meta.pagination.total);
	        		
	        	});
	        }
	    })
	}();

	$scope.destroyUser = function(user) {
		if (confirm('Do you want to delete the user ' + user.email + '?')) {
			Users.destroy(user, function() {
			$route.reload();
			});
		};
	}
});