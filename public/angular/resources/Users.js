app.factory('Users', ['$resource', function($resource){
	return $resource(
		'/api/users/:id',
		{id : '@id'},
		{
			'get':    {method:'GET', params: {records:10}},
			'post':    {method:'POST', params: {}},
			'update':    {method:'PUT', params: {}},
			'destroy':    {method:'DELETE', params: {}}
		}
	);
}]);