app.controller('withdrawListCtrl', function($scope, $location, $filter,API,PathApiService,ngNotify,AuthService,$state,$stateParams,$modal) {
    
    var state_current = $state.current.name;
    $scope.search_view =0;
    $scope.description = "Withdraw";
    $scope.datas = [];
    $scope.showAlert = false;


    $scope.loadData = function(page)
    {
        var serach = $scope.search_text !="" && $scope.search_text!=undefined?$scope.search_text:"";
        var paging = page !="" && page!=undefined?page:1;
        var where = "?per_page="+$scope.paging.page_size;
            where += "&page="+paging;
            where += "&q="+serach; 

        var api = PathApiService.withdraw();

        switch(state_current){
            case "app.withdraw_search":
                //api += "/load";
                $scope.description = "Withdraw Search";
                return false;
            break;
            case "app.withdraw_today":
                $scope.description = "Withdraw Today";
                api += "/load_today";
            break;
            case "app.withdraw_on_going":
                $scope.description = "Withdraw On Going";
                api += "/load_on_going";
            break;
            default : api += "/load";
        }

        API.get(api+where).then(function(result){
            $scope.datas = result.data;
            $scope.paging.total = result.total;
        });
    }
    $scope.search_data = function()
    {
        $scope.loadData(1);
    }
    $scope.DoCtrlPagingAct = function(text, page, pageSize, total) {
        $scope.loadData(page);
    };


    $scope.search_top_up = function(page)
    {
        var withdraw_code = $scope.withdraw_code !="" && $scope.withdraw_code!=undefined?$scope.withdraw_code:"";
        var serach = $scope.search_text !="" && $scope.search_text!=undefined?$scope.search_text:"";

        /*
        if(withdraw_code==""){
            alert_ngnotify('error',' top up number can not be empty ',ngNotify);
            $scope.search_view = 0;
            return false;
        }
        */
        var where = "?withdraw_code="+withdraw_code;
            where +='&q='+serach;
        var api = PathApiService.withdraw()+"/load";

        API.get(api+where).then(function(result){
            $scope.datas = result.data;
            $scope.paging.total = result.total;
            $scope.search_view =1;
        });
    }

    $scope.complate_data = function(id){
        param = {"action":"complate_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"withdrawModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.hold_data = function(id){
        param = {"action":"hold_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"withdrawModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.approve_data = function(id){
        param = {"action":"approve_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"withdrawModalCtrl","param":param};
        open_modal(data,$modal);
    }




    function init(){
        $scope.loadData();
    }
    init();
});