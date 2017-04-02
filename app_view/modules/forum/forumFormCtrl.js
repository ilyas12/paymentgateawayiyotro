app.controller('forumFormCtrl', function($scope, $location, $filter,API,PathApiService,ngNotify,AuthService,$state,$stateParams) {
    var api  = PathApiService.forum();
    $scope.admin_conclusion = 0;
    $scope.description = " Fourm Detail";
    $scope.datas = [];
    $scope.showAlert = false;

    $scope.setDescription = function(){

    }

    $scope.getData = function(id){
        action_url = api+"/get/"+id;
        API.post(action_url,{user_id:AuthService.user_id()}).then(function(result){
            $scope.data = result.data;
        });
    }



    $scope.formSubmit = function(){
        if($scope.data.admin_conclusion_text == '' || $scope.data.admin_conclusion_text == undefined || $scope.data.admin_conclusion_text == null){
            $scope.showAlert=true;
            return false;
        }else{
            $scope.showAlert = false;
        }
        
        var parameter = {
                            admin_conclusion:$scope.data.admin_conclusion_text,
                            type:$scope.admin_conclusion,
                            user_id:AuthService.user_id(),
                        };

        //console.log(parameter);return false;
        var action_url = api+"/update/"+$stateParams.forum_id;

        API.post(action_url,parameter).then(function(result){
            if(result.error == 0){
                alert_ngnotify('info','success ',ngNotify);
                $state.reload();   
            }else{
                alert_ngnotify('danger',result.msg,ngNotify);
            }
        
        });
    }
    $scope.adminConclusionAdd = function(type){
        //type 1= admin_conculsion type =2 fourm
        $scope.admin_conclusion = type;
        $scope.data.admin_conclusion_text = undefined;
    }
    $scope.adminConclusionCancel = function(){
        $scope.admin_conclusion = 0;
        $scope.data.admin_conclusion_text = undefined;
    }
    
    $scope.close_data = function(id){
        var api = PathApiService.forum()+"/close_data/"+id;

        API.get(api).then(function(result){
            $state.reload();
        });
    }


    function init(){
        $scope.setDescription();
        $scope.getData($stateParams.forum_id);
    }
    init();
});