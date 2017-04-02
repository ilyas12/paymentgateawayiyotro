app.controller('userGroupModalCtrl', function ($modal,$scope, $state, $modalInstance,API,PathApiService,param,ngNotify,AuthService) {
	
	if(param.action == 'insert'){
		$scope.description = "User Group Add";
	}
	if(param.action=='edit'){
		$scope.description = "User Group Edit";	
	}

	var api_url = PathApiService.user_group();
	var action_url = '';
	var parameter = [];
	$scope.pages = [];
	$scope.getdata = function(){
		
		if(param.action == "edit"){
		
			action_url = api_url+"/get/"+param.id;
			
		}else{
		
			action_url = api_url+"/manage_link/"+param.id;
			
		}
		API.post(action_url,{user_id:AuthService.user_id()}).then(function(result){
	        
	        $scope.data = result.data;
			
			$scope.pages.selected = result.privilege;
			
	        
	    });
	}
	
	if(param.action == "edit" || param.action == "manage_link"){ $scope.getdata(); }
	
	
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
		case "manage_link":
			api_url += "/store_link/"+param.id;
		break;
		default : api_url += "/index";
	}
	
	$scope.ok = function () {
        
		if(param.action == "insert" || param.action == "edit"){
			if($scope.data == undefined || $scope.data.name == ''){
				ngNotify.set('All Field is required', {
					position: 'top',
					theme: 'pure',
					type: 'error'
				});
				return false;
			}
		}
        switch(param.action){
			case "insert":
			case "edit":
				parameter = {name:$scope.data.name,user_id:AuthService.user_id()};
			break;
			case "manage_link":
				parameter = {user_id:AuthService.user_id(),access_right:$scope.pages.selected};
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
			
			if(param.ctrl != "" && param.ctrl != undefined){ 
				var data = {"size":"lg","param":param.callback,"template":param.template,"ctrl":param.ctrl}; 
				open_modal(data,$modal); 
			}else{
			
				$state.reload();
			
			}
			
		});
        
    };
	    $scope.cancel = function () {
	        $modalInstance.dismiss('cancel');
			
			if(param.ctrl != "" && param.ctrl != undefined){ 
				var data = {"size":"lg","param":param.callback,"template":param.template,"ctrl":param.ctrl}; 
				open_modal(data,$modal); 
			}
	    };
});