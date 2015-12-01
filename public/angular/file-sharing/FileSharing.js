app.controller('FileSharing', function FileSharingCtrl($scope, $window, $location, Users, $routeParams,$timeout, SharedFiles){
	$scope.fileCount = 0;
	$scope.files = [];
	$scope.filesAdded = 0;
	$scope.class = 'dragarea';
	$scope.recipients = [];

	$scope.msg = '';

	$scope.expires = 1;

	$scope.recipient;

	$scope.flow;

	$scope.errors = [{
		"file" : "",
		"recipients" : "",
		"message" : "",
	}];

	$scope.info = {
		"file" : []
	};

	$scope.sending = false;

	initslider();

	$scope.$watch('flow', function(){
		if($scope.flow) {
			// alert('flow set');
			$scope.flow.on('fileAdded', function(file, event){
				if(maxSize && (maxSize < file.size)){
					var limit = parseFloat(maxSize/1048576).toFixed(2);
					var size = parseFloat(file.size/1048576).toFixed(2);
					$scope.info.file.push(file.name + ', size ' + size + "M exeeds the limit of " + limit + 'M');

					return false;
				}
			})
		}
	});



	$scope.options = {
		target: '/api/file/upload', 
		headers: {'X-Auth-Token':[[API_KEY]]}, 
		testChunks: false,
		query: $routeParams,
		singleFile: false
	};


	$scope.initRecipient = function(){
		Users.get({records:100}, function(response) {
			$scope.users = response.data;
		});
		$scope.recipient = {email: ""}
	}


	/**
	 * [addFiles description]
	 *
	 * @param {[type]} $files [description]
	 */
	$scope.addFiles = function ($files) {
		if(!$files.length){
			return;
		}

		$scope.filesAdded++;
		$scope.fileCount += $files.length;

		$scope.errors['file'] = false;


		$timeout(function(){
			angular.forEach($files, function(file){
				file.flowObj.resume();
			});
		}, 200);

	}




	/**
	 * Removes uploaded files
	 *
	 * @param  {[type]} index [description]
	 *
	 * @return {[type]}       [description]
	 */
	$scope.removeUploadedFile = function(index){
		$scope.files.splice(index, 1);

		// TODO: send service call to delete the file.
	}



	/**
	 * [fileUploaded description]
	 *
	 * @param  {[type]} message [description]
	 * @param  {[type]} file    [description]
	 *
	 * @return {[type]}         [description]
	 */
	$scope.fileUploaded = function(message, file) {
		var json = angular.fromJson(message);
		$scope.files.push(json);
		$scope.fileCount--;
	}


	/**
	 * Add / validate the entered recipient.
	 *
	 * @param {[type]} index [description]
	 */
	$scope.addRecipient = function(){
		$scope.recipient.valid = $scope.validateEmail($scope.recipient.email);

		// console.log($scope.recipient);
		if($scope.recipient.valid){
			$scope.recipients.push($scope.recipient)
			$scope.initRecipient();
			$scope.errors['recipient'] = false;
		} 
		
	}


	/**
	 * Removes a recipient.
	 *
	 * @param  {[type]} index [description]
	 *
	 * @return {[type]}       [description]
	 */
	$scope.removeRecipient = function(index){
		$scope.recipients.splice(index, 1);
	}



	/**
	 * Add more recipient workflow
	 */
	$scope.addMoreRecipient = function(){
		$scope.recipients.push({email: "", valid: false});
	}


	/**
	 * Validate an email.
	 *
	 * @param  {[type]} email [description]
	 *
	 * @return {[type]}       [description]
	 */
	$scope.validateEmail = function(email) { 
	    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email);
	} 


	/**
	 * Validate the list of recipients.
	 *
	 * @return {[type]} [description]
	 */
	$scope.validRecipients = function(){
		var valid = [];

		angular.forEach($scope.recipients, function(recipient){
			if(recipient.valid){
				valid.push(recipient.email);
			}
		});

		return valid;
	}


	/**
	 * [send description]
	 *
	 * @return {[type]} [description]
	 */
	$scope.send = function(){
		
		if ($scope.recipient.email) {
			$scope.addRecipient();
		}

		$scope.sending = false;

		var valid = true;
		if($scope.files.length == 0){
			$scope.errors['file'] = 'Please add at least one valid file';
			valid = false;
		} else {
			$scope.errors['file'] = '';
		}

		var recipients = $scope.validRecipients();
		if(recipients.length == 0){
			$scope.errors['recipient'] = 'Please add at least one valid recipient';
			valid = false;
		} else {
			$scope.errors['recipient'] = '';
		}

		if(valid){
			$scope.sending = true;

			// check if files are still uploading.
			if($scope.fileCount){

				$timeout(function(){
					$scope.send();
				}, 5000)

				return;
			}

			if(jQuery('#ex1').val().length == 0){
				var expires_at = 30;
			} else {
				var expires_at = parseInt(jQuery('#ex1').val());
			}

			var payload = {
				"files" : $scope.files,
				'recipients' : recipients,
				'message' : $scope.message,
				'expires_at' : expires_at,
			};


			SharedFiles.store(
				payload,
				function(response){
					$scope.sending = false;
					window.location.href= "/thanks";
				},
				function(errResponse){
					$scope.sending = false;
					alert('error sending request');
				}
			)

		}

	}


	// init recipients.
	$scope.initRecipient();
});