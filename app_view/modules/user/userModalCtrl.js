app.controller('userModalCtrl', function ($modal,$scope, $state, $modalInstance,API,PathApiService,ngNotify,param,AuthService) {
	var api = PathApiService.user();
	var action_url = '';
	var parameter = [];
	$scope.data = [];
	$scope.action = param.action;

	

	$scope.get_action_url = function(par){
		var api = PathApiService.user();	
		switch(par.action){
			case "delete":
				api += "/delete/"+par.id;
			break;
			case "change_pwd":
				api += "/change_pwd/"+param.id;
				$scope.getData(param.id);
			break;
			default : api += "/index";
		}
		action_url = api;
	}

    $scope.getData = function(id){
        action_url = api+"/get/"+id;
        API.post(action_url,{user_id:AuthService.user_id()}).then(function(result){
            $scope.data = result.data;
            $scope.data.user_access_group_id = {"id":result.data.id_group,"text":result.data.group_name};
        });
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