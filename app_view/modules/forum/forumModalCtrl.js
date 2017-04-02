app.controller('forumModalCtrl', function ($modal,$scope, $state, $modalInstance,API,PathApiService,ngNotify,param,AuthService) {
	var api = PathApiService.withdraw();
	var action_url = '';
	var parameter = [];
	$scope.data = [];
	$scope.action = param.action;

	

	$scope.get_action_url = function(par){
		var api = PathApiService.withdraw();	
		switch(par.action){
			case "approve_data":
				api += "/approveData/"+par.id;
			break;
			case "complate_data":
				api += "/complateData/"+par.id;
			break;
			case "hold_data":
				api += "/holdData/"+par.id;
			break;
			default : api += "/index";
		}
		action_url = api;
	}

	
	$scope.ok = function () {

		if($scope.myForm != undefined){
	        if(!$scope.myForm.$valid){
	            $scope.showAlert=true;
	            console.log("er");
	            return false;
	        }else{
	            $scope.showAlert = false;
	        }
	    }

        switch(param.action){
			case "data":
				parameter = {password:$scope.data.password,user_id:AuthService.user_id()};
			break;
			default : parameter = {user_id:AuthService.user_id()};
		}

		API.post(action_url,parameter).then(function(result){
			if(result.error == "1"){

				alert_ngnotify('error',result.msg,ngNotify);
	            $modalInstance.close();
				return false;
				//$state.reload();	
			}
			alert_ngnotify('info','success',ngNotify);
            $modalInstance.close();
			$state.reload();
			
		});
        
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };


    function init() {
    	$scope.get_action_url(param);
    }
    init();

});