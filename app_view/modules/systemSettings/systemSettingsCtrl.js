app.controller('systemSettingsCtrl', function($scope,$state, $location, $filter,API,PathApiService,$modal,AuthService,ngNotify) {
  	$scope.description = "System Settings";
  	$scope.data = [];
	
	var param = {};
	$scope.loadData = function()
	{
	    API.get(PathApiService.system_settings()+"/get/1").then(function(result){
	        $scope.data = result.data;
	        $scope.data.payment_date_month = parseInt(result.data.payment_date_month);
	    });
	}
	$scope.loadData();
	$scope.ok = function(){
		var parameter = {
							"payment_date_month":$scope.data.payment_date_month,
							user_id:AuthService.user_id()
						};
        API.post(PathApiService.system_settings()+"/update/1",parameter).then(function(result){
            ngNotify.set('Success', {
                position: 'top',
                theme: 'pure',
                type: 'info'
            });
            $state.reload(); 
			
        });
	}
});