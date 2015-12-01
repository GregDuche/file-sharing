app.controller('MyFiles', function Users($scope, $window, $timeout, $location, $route, $routeParams, $timeout, SharedFiles, SharedFilesRecipient, NgTableParams){

	$scope.sent = true;
	
	var init = function() {

		$scope.tableParams = new NgTableParams({
	        page: 1,            // show first page
	        count: 10           // count per page
	    }, {
	        //total: data.length, // length of data
	        getData: function ($defer, params) {
	        	$scope.sent = true;
	        	var payload = { records: params.count(), page: params.page()}
	        	payload.include = 'request,file,request.recipients';
	        	payload.user_id = user_id;
	        	SharedFiles.get(payload, function(response) {
	        		var data = response.data
	        		$defer.resolve(data);
	        		params.total(response.meta.pagination.total);
	        		
	        	});
	        }
	    })

	    $scope.receivedTableParams = new NgTableParams({
	        page: 1,            // show first page
	        count: 10           // count per page
	    }, {
	        //total: data.length, // length of data
	        getData: function ($defer, params) {
	        	
	        	
	        	var payload = { records: params.count(), page: params.page()}
	        	payload.include = 'request.sharedFiles.file';
	        	payload.user_id = user_id;
	        	SharedFilesRecipient.get(payload, function(response) {
	        		var data = response.data
	        		$defer.resolve(data);
	        		params.total(response.meta.pagination.total);
	        		
	        	});
	        }
	    })
	}();

	$scope.destroyFile = function(file) {
		if (confirm('Do you want to delete the file ' + file.id + '?')) {
			SharedFiles.destroy(file, function() {
				$route.reload();
			});
		};
	}
});