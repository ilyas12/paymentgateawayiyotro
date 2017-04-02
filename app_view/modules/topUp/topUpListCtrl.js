app.controller('topUpListCtrl', function($scope, $location, $filter,API,PathApiService,ngNotify,AuthService,$state,$stateParams,$modal) {
    
    var state_current = $state.current.name;
    $scope.search_view =0;
    $scope.description = "Top Up";
    $scope.datas = [];
    $scope.showAlert = false;


    $scope.loadData = function(page)
    {
        var serach = $scope.search_text !="" && $scope.search_text!=undefined?$scope.search_text:"";
        var paging = page !="" && page!=undefined?page:1;
        var where = "?per_page="+$scope.paging.page_size;
            where += "&page="+paging;
            where += "&q="+serach; 

        var api = PathApiService.top_up();

        switch(state_current){
            case "app.top_up_search":
                //api += "/load";
                $scope.description = "Top Up Search";
                return false;
            break;
            case "app.top_up_today":
                $scope.description = "Top Up Today";
                api += "/load_today";
            break;
            case "app.top_up_on_going":
                $scope.description = "Top Up On Going";
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
        var topup_number = $scope.topup_number !="" && $scope.topup_number!=undefined?$scope.topup_number:"";
        var serach = $scope.search_text !="" && $scope.search_text!=undefined?$scope.search_text:"";


        /*
        if(topup_number==""){
            alert_ngnotify('error',' top up number can not be empty ',ngNotify);
            $scope.search_view = 0;
            return false;
        }
        */

        var where = "?topup_number="+topup_number;
            where +='&q='+serach;
        var api = PathApiService.top_up()+"/load";

        API.get(api+where).then(function(result){
            $scope.datas = result.data;
            $scope.paging.total = result.total;
            $scope.search_view =1;
        });
    }

    $scope.complate_data = function(id){
        param = {"action":"complate_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"topUpModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.hold_data = function(id){
        param = {"action":"hold_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"topUpModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.download_data = function(id){
        window.open(base_system_url+"index.php/topUp/downloadReceipt/"+id, '_blank');
    }



    function init(){
        $scope.loadData();
    }
    init();
});