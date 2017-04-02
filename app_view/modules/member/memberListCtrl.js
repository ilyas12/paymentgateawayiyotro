app.controller('memberListCtrl', function($scope, $location, $filter,API,PathApiService,ngNotify,AuthService,$state,$stateParams,$modal) {
  
    $scope.description = "Member";
    $scope.datas = [];
    $scope.showAlert = false;


    $scope.loadData = function(page)
    {
        var serach = $scope.search_text !="" && $scope.search_text!=undefined?$scope.search_text:"";
        var paging = page !="" && page!=undefined?page:1;
        var where = "per_page="+$scope.paging.page_size;
            where += "&page="+paging;
            where += "&q="+serach; 

        API.get(PathApiService.member()+"/load?"+where).then(function(result){
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

    $scope.detail_data = function(id)
    {
        $state.go('app.member_detail', { member_id: id });
    }

    $scope.active_data = function(id){
        param = {"action":"active_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"memberModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.inactive_data = function(id){
        param = {"action":"inactive_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"memberModalCtrl","param":param};
        open_modal(data,$modal);
    }


    function init(){
        $scope.loadData();
    }
    init();
});