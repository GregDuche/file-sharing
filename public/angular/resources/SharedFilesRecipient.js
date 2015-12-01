app.factory('SharedFilesRecipient', ['$resource', function($resource){
	return $resource(
		'/api/shared-files-recipient/:id', 
		{id : '@id'}, 
		{ 
			'get':    {method:'GET'}
		}
	);
}])