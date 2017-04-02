app.controller('prefixModalCtrl', function ($scope, $state, $modalInstance,API,PathApiService,param,ngNotify,AuthService,$modal) {
	var api_url = PathApiService.prefix();
	var user_id = AuthService.user_id();
	
	var parameter = [];
	
	$scope.getdata = function(){
		
		action_url = api_url+"/get/"+param.id;
		API.post(action_url,{user_id:user_id}).then(function(result){
	        
	        $scope.data = result.data;
            
	        
	    });
	}
	
	if(param.action == "edit") { $scope.getdata(); }
	
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
			if($scope.data.running_number == undefined){
			
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
				parameter = {
					prefix:$scope.data.prefix,
					length:$scope.data.length,
					user_id:user_id
				};
			break;
			default : parameter = {user_id:user_id};
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
			} else {   
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