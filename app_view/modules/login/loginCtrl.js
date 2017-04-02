app.controller('loginCtrl', function ($scope, $rootScope, PathApiService, AuthService,API,$state) {
	$scope.data = [];
	$scope.alerts = [];

	if (AuthService.isAuthenticated()) {
		$state.go('app.dashboard');
	}


	$scope.login = function(){
		
		API.post(PathApiService.login()+"/login",{username:$scope.data.username,password:$scope.data.password}).then(function(result){
			
			if(result.error == 0)
			{
				AuthService.login(result).then(function(authenticated) {
					$state.go('app.dashboard');
				}, function(err) {
					console.log(err);
				});
			}
			else
			{
				$scope.alerts = [
					{ type: 'danger', msg: result.msg }
				];
			}
		});
	};
	$scope.closeAlert = function(index) {
		$scope.alerts.splice(index, 1);
	};
});