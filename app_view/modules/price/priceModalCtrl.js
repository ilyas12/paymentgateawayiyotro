app.controller('priceModalCtrl', function ($modal,$scope, $state, $modalInstance,API,PathApiService,param,ngNotify,AuthService,Func) {
	
	if(param.action == 'insert'){
		$scope.description = "Price Add";
	}
	if(param.action=='edit'){
		$scope.description = "Price Edit";	
	}

	var api_url = PathApiService.price();
	var action_url = '';
	var parameter = [];
	$scope.pages = [];
	$scope.getdata = function(){
		if(param.action == "edit"){
			action_url = api_url+"/get/"+param.id;
		}
		API.post(action_url,{user_id:AuthService.user_id()}).then(function(result){
	        $scope.data = result.data;
			$scope.pages.selected = result.privilege;
			
	        
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
			if($scope.data == undefined || $scope.data.fee == '' || $scope.data.fee == undefined ||
				$scope.data.start == '' || $scope.data.start == undefined ||
				$scope.data.end == '' || $scope.data.end == undefined 
				)
			{
				alert_ngnotify('error','All Field is required',ngNotify);
				return false;

			}
			if(!Func.isNumber($scope.data.fee) && !Func.isNumber($scope.data.start) && !Func.isNumber($scope.data.end) ){
				alert_ngnotify('error','Please insert number',ngNotify);
				return false;
			}
		}

        switch(param.action){
			case "insert":
			case "edit":
				parameter = {
							end:$scope.data.end,
							fee:$scope.data.fee,
							start:$scope.data.start,
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
});