app.controller('companyCtrl', function($scope, $location, $filter,API,PathApiService,ngNotify,AuthService,$state,$stateParams) {
  
    $scope.description = "Company";
    $scope.data = [];
    $scope.showAlert = false;

    $scope.load = function(){
        API.get(PathApiService.company()+"/load").then(function(result){
            if(result.error == 1){
                alert_bootsrap('danger',result.msg,$scope);
            }else{
                $scope.data = result.data;
            }
        });
    }
    $scope.formSubmit = function(){
        
        if(!$scope.myForm.$valid){
            $scope.showAlert=true;
            console.log("er");
            return false;
        }else{
            $scope.showAlert = false;
        }
        var parameter = {
                            name:$scope.data.name,
                            address:$scope.data.address,
                            city:$scope.data.city,
                            state:$scope.data.state,
                            country:$scope.data.country,
                            postal_code:$scope.data.postal_code,
                            phone:$scope.data.phone,
                            email:$scope.data.email,
                            fax:$scope.data.fax,
                            website:$scope.data.website,
                            user_id:AuthService.user_id(),
                        };

        API.post(PathApiService.company()+"/update/1",parameter).then(function(result){
            alert_ngnotify('info','success update data',ngNotify);
            $state.reload();

        });
    }
    $scope.formCancel = function(){
        $state.go('app.dashboard');
    }

    $scope.closeAlert = function(index) {
      $scope.alerts.splice(index, 1);
    };

    function init(){
        $scope.load();
    }
    init();



});