app.controller('userListCtrl', function($scope, $location, $filter,API,PathApiService,ngNotify,AuthService,$state,$stateParams,$modal) {
  
    $scope.description = "User";
    $scope.datas = [];
    $scope.showAlert = false;


    $scope.loadData = function(page)
    {
        var serach = $scope.search_text !="" && $scope.search_text!=undefined?$scope.search_text:"";
        var paging = page !="" && page!=undefined?page:1;
        var where = "per_page="+$scope.paging.page_size;
            where += " &page="+paging;
            where += " &q="+serach; 

        API.get(PathApiService.user()+"/load?"+where).then(function(result){
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

    $scope.add_data = function () {
        $state.go('app.user_add');
    };
    $scope.edit_data = function(id)
    {
        $state.go('app.user_edit', { user_id: id });
    }

    $scope.delete_data = function(id){
        param = {"action":"delete","id":id};
        var data = {"size":"sm","template":"modules/confirm/delete_confirm.html","ctrl":"userModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.password_data = function(id){
        param = {"action":"change_pwd","id":id};
        var data = {"size":"lg","template":"modules/user/userPasswordFormView.html","ctrl":"userModalCtrl","param":param};
        open_modal(data,$modal);
    }


    function init(){
        $scope.loadData();
    }
    init();
});