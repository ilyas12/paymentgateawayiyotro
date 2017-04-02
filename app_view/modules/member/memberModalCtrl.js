app.controller('memberModalCtrl', function ($modal,$scope, $state, $modalInstance,API,PathApiService,ngNotify,param,AuthService) {
	var api = PathApiService.member();
	var action_url = '';
	var parameter = [];
	$scope.data = [];
	$scope.action = param.action;

	

	$scope.get_action_url = function(par){
		var api = PathApiService.member();	
		switch(par.action){
			case "active_data":
				api += "/active_data/"+par.id;
			break;
			case "inactive_data":
				api += "/inactive_data/"+par.id;
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
			case "change_pwd":
				parameter = {password:$scope.data.password,user_id:AuthService.user_id()};
			break;
			default : parameter = {user_id:AuthService.user_id()};
		}

		API.post(action_url,parameter).then(function(result){
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