app.controller('memberFormCtrl', function($scope, $location, $filter,API,PathApiService,ngNotify,AuthService,$state,$stateParams) {
  	var api  = PathApiService.member();
    $scope.form_type = "";
    $scope.description = "Member";
    $scope.data = [];
    $scope.user_group_selector = [];
    $scope.showAlert = false;

    $scope.setDescription = function(){
    		$scope.description = " Member Detail ";
            $scope.getData($stateParams.member_id);
         
    }

    $scope.getData = function(id){
        action_url = api+"/get/"+id;
        API.post(action_url,{user_id:AuthService.user_id()}).then(function(result){
            $scope.data = result.data;
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
                            username:$scope.data.username,
                            full_name:$scope.data.full_name,
                            mobile:$scope.data.mobile,
                            email:$scope.data.email,
                            user_access_group_id:$scope.data.user_access_group_id.id,
                            user_id:AuthService.user_id(),
                        };
        if($scope.form_type=='add'){
            parameter.password = $scope.data.password;
        }

        var action_url = api;
        switch($scope.form_type){
            case "add":
                action_url += "/insert";
            break;
            case "edit":
                action_url += "/update/"+$stateParams.user_id;
            break;
            default : action_url += "/index";
        }

        API.post(action_url,parameter).then(function(result){
            if(result.error == 0){
                alert_ngnotify('info','success ',ngNotify);
                $state.go('app.user');   
            }else{
                alert_ngnotify('danger',result.msg,ngNotify);
            }
        
        });
    }
    $scope.formCancel = function(){
        $state.go('app.member');
    }


    function init(){

    	$scope.setDescription();
    }
    init();
});