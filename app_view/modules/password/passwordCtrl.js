app.controller('passwordCtrl', function($scope, $location, $filter,API,PathApiService,$modal,ngNotify,AuthService) {
  	$scope.description = "Password";
  	$scope.data = [];
	
	var param = {};
	$scope.loadData = function()
	{
	    API.get(PathApiService.user()+"/get_data_by_session").then(function(result){
	        $scope.data = result.data;
	        console.log(result.data);
	    });
	}
	$scope.loadData();
	/* Modal */
	$scope.ok = function () {
        
		
			if($scope.data.old_password == undefined || $scope.data.new_password == undefined ||  $scope.data.new_password_confirm == undefined){
			
				ngNotify.set('All Field is required', {
					position: 'top',
					theme: 'pure',
					type: 'error'
				});
				return false;
			}
		
			if( $scope.data.new_password !==  $scope.data.new_password_confirm ){
			
				ngNotify.set('the confirm password is not the same', {
					position: 'top',
					theme: 'pure',
					type: 'error'
				});
				return false;
			}

			parameter = {
					old_password:$scope.data.old_password,
					new_password:$scope.data.new_password,
					user_id:AuthService.user_id()
				};
			
			
        API.post(PathApiService.user()+"/change_password",parameter).then(function(result){
			if(	result.error == 1){
	            ngNotify.set(result.msg, {
	                position: 'top',
	                theme: 'pure',
	                type: 'error'
	            });
				return false;
			}
			
            ngNotify.set('Success', {
                position: 'top',
                theme: 'pure',
                type: 'info'
            });
            $scope.data.old_password ="";
            $scope.data.new_password ="";
            $scope.data.new_password_confirm ="";

        });
        
    };
});