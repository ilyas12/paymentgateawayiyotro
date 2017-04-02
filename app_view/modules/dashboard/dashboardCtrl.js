app.controller('dashboardCtrl', function ($scope, $rootScope, PathApiService, AuthService,API,$modal) {
	
	var data = {};
	$scope.description = "Dashboard";
	
  $scope.plot_pie = [];

	var user_id = AuthService.user_id();

    $scope.load = function(){
        API.get(PathApiService.api()+"/dashboard_data").then(function(result){
            if(result.error == 1){
                alert_bootsrap('danger',result.msg,$scope);
            }else{
                $scope.data = result.data;
                
                $scope.plot_pie.push({
                  label: "Payment",
                  data: parseInt(result.data.payment)
                });

                $scope.plot_pie.push({
                  label: "Top Up",
                  data: parseInt(result.data.topup_request)
                });

                $scope.plot_pie.push({
                  label: "Withdraw",
                  data: parseInt(result.data.withdraw)
                });

                $scope.plot_pie.push({
                  label: "Forum",
                  data: parseInt(result.data.forum)
                });


            }
        });
    }


    function init(){
        $scope.load();
    }
    init();
	
	
});