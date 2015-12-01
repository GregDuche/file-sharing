app.factory('SharedFiles', ['$resource', function($resource){
	return $resource(
		'/api/shared-files/:id', 
		{id : '@id'}, 
		{ 
			'get':    {method:'GET'},
		    'store':  {method:'POST'},
			'destroy':  {method:'DELETE'}
		}
	);
}])