app.controller('paymentConfirmationModalCtrl', function ($modal,$scope, $state, $modalInstance,API,PathApiService,ngNotify,param,AuthService) {
	var api = PathApiService.payment_confirmation();
	var action_url = '';
	var parameter = [];
	$scope.data = [];
	$scope.action = param.action;
    $scope.user_selector = [];
    $scope.showAlert = false;

	

	$scope.get_action_url = function(par){
		var api = PathApiService.payment_confirmation();	
		switch(par.action){
			case "detail_data":
				$scope.get_data();
			break;
			case "edit_fee":
				$scope.get_data();
				api += "/edit_fee/"+par.id;
			break;
			case "approve_data":
				api += "/approveData/"+par.id;
			break;
			case "approve_to_data":
				$scope.get_user_selector();
				api += "/approveToData/"+par.id;
			break;
			case "complate_data":
				api += "/complateData/"+par.id;
			break;
			case "hold_data":
				api += "/holdData/"+par.id;
			break;
			case "cancel_data":
				api += "/cancelData/"+par.id;
			break;
			default : api += "/index";
		}
		action_url = api;
	}

	$scope.get_data =function  () {
		API.get(PathApiService.payment_confirmation()+"/get/"+param.id).then(function(result){
			$scope.data = result.data;
		});
	}
	

	$scope.get_user_selector =function  (argument) {
		API.get(PathApiService.user()+"/load").then(function(result){
			$scope.user_selector = result.data;
		});
	}
	
	$scope.ok = function () {

		switch(param.action){
			case "edit_fee":
				parameter = {fee:$scope.data.fee,received_amount:$scope.data.received_amount,user_id:AuthService.user_id()};
			break;
			case "approve_data":
				parameter = {approve_user_id:AuthService.user_id(),user_id:AuthService.user_id()};
			break;
			case "approve_to_data":
				if($scope.data.user_id == undefined || $scope.data.user_id == ''){
					 alert_ngnotify('error','all field required',ngNotify);
					 return false;
				}
				parameter = {approve_user_id:$scope.data.user_id.selected.id,user_id:AuthService.user_id()};
			break;
			case "complate_data":
				parameter = {user_id:AuthService.user_id()};
			break;
			case "hold_data":
				parameter = {user_id:AuthService.user_id()};
			break;
			default : parameter = {user_id:AuthService.user_id()};
		}
		console.log(action_url);


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
    $scope.calculate_fee = function  () {
    	var amount = parseFloat($scope.data.amount);
    	var fee = parseFloat($scope.data.fee);

    	var total = amount + fee;
    	console.log(total);
    	if(isNaN(total)){
    		return false;
    	}
    	$scope.data.received_amount = total;

    }

    function init() {
    	$scope.get_action_url(param);
    }
    init();

});