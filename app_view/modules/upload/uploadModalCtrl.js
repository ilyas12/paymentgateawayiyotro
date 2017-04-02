app.controller('uploadModalCtrl', function ($modal,$scope, $state, $modalInstance,API,PathApiService,param,ngNotify,AuthService,Func,Upload,$timeout) {
	
	if(param.action == 'insert'){
		$scope.description = "Upload Add";
	}
	if(param.action=='edit'){
		$scope.description = "Upload Edit";	
	}

	var api_url = PathApiService.upload();
	var action_url = '';
	var parameter = [];
	$scope.pages = [];
	$scope.getdata = function(){
		if(param.action == "edit"){
			action_url = api_url+"/get/"+param.id;
		}
		API.post(action_url,{user_id:AuthService.user_id()}).then(function(result){
	        $scope.data = result.data;
	        $scope.data.thumbnail = undefined;	 
			
	    });
	}
	
	if(param.action == "edit" ){ $scope.getdata(); }
	
	
	switch(param.action){
		case "insert":
			api_url += "/insert";
		break;
		case "edit":
			api_url += "/update/"+param.id;
		break;
		case "delete":
			api_url += "/delete/"+param.id;
		break;
		default : api_url += "/index";
	}
	
	$scope.ok = function () {
        
		if(param.action == "insert" || param.action == "edit"){
			if( $scope.data.title == undefined 
				)
			{
				alert_ngnotify('error','All Field is required',ngNotify);
				return false;

			}

		}

        switch(param.action){
			case "insert":
			case "edit":
				parameter = {
							title:$scope.data.title,
							content:$scope.data.content,
							thumbnail:$scope.data.thumbnail,
							user_id:AuthService.user_id()
							};
			break;
			default : parameter = {user_id:AuthService.user_id()};
		}
		
		API.post(api_url,parameter).then(function(result){
			ngNotify.set('Success', {
                position: 'top',
                theme: 'pure',
                type: 'info'
            });
			
            $modalInstance.close();
			$state.reload();	
		});
        
    };
	$scope.cancel = function () {
	        $modalInstance.dismiss('cancel');
	};

    $scope.upload = function(file) {
            file.upload = Upload.upload({
              url: PathApiService.upload()+'/upload_thumbnail',
              data: {user_id : AuthService.user_id(), file: file},
            });

            file.upload.then(function (response) {
                  $timeout(function () {
                    //file.result = response.data;
                    if(response.data.error){	
                        $scope.errorUpload = response.data.msg;
					        $scope.progress = undefined;
					        $scope.data.file = undefined;
					        $scope.data.thumbnail = null;
                    }else{
                    	$scope.data.thumbnail = response.data.data;
                    }

                  });
            }, function (response) {
              if (response.status > 0)
                $scope.errorUpload = response.status + ': ' + response.data;

		        $scope.progress = undefined;
		        $scope.data.file = undefined;
		        $scope.data.thumbnail = null;

            }, function (evt) {
              // Math.min is to fix IE which reports 200% sometimes
              $scope.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
            });
    }
    $scope.upload_form  = false;
    $scope.removeUpload=function  () {
        $scope.data.errorUpload = undefined;
        $scope.progress = undefined;
        $scope.data.file = undefined;
        $scope.data.thumbnail = null;
   	}
});