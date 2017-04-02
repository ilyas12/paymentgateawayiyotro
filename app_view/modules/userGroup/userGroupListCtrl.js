app.controller('userGroupListCtrl', function($scope, $state, $location, $filter,API,PathApiService,$modal,AuthService) {
  	$scope.description = "User Group";
  	$scope.datas = [];


    $scope.loadData = function(page)
    {
        var serach = $scope.search_text !="" && $scope.search_text!=undefined?$scope.search_text:"";
        var paging = page !="" && page!=undefined?page:1;
        var where = "per_page="+$scope.paging.page_size;
            where += " &page="+paging;
            where += " &q="+serach; 

        API.get(PathApiService.user_group()+"/load?"+where).then(function(result){
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
	/* Modal */
	$scope.add_data = function () {
        var param = {"action":"insert"};
        var data = {"size":"lg","param":param,"template":"modules/userGroup/userGroupFormView.html","ctrl":"userGroupModalCtrl"};
		open_modal(data,$modal);
    };
    $scope.edit_data = function(id)
    {
		var param = {"action":"edit","id":id};
    	var data = {"size":"lg","param":param,"template":"modules/userGroup/userGroupFormView.html","ctrl":"userGroupModalCtrl"};
		open_modal(data,$modal);
    }


    $scope.delete_data = function(id){
        param = {"action":"delete","id":id};
        var data = {"size":"sm","template":"modules/confirm/delete_confirm.html","ctrl":"userGroupModalCtrl","param":param};
        open_modal(data,$modal);
    }
	
	$scope.manage_link = function(id){
		var param = {"action":"manage_link","id":id};
    	var data = {"size":"lg","param":param,"template":"modules/userGroup/userGroupAccessFormView.html","ctrl":"userGroupModalCtrl"};
		open_modal(data,$modal);
    }


    function init(){
        $scope.loadData();
    }
    init();
});