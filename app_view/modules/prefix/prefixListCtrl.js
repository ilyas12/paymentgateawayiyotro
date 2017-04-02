app.controller('prefixListCtrl', function($scope, $location, $filter,API,PathApiService,$modal) {
  	$scope.description = "Prefix";
  	$scope.datas = [];
	
	var param = {};
	$scope.loadData = function()
	{
	    API.get(PathApiService.prefix()+"/load").then(function(result){
	        $scope.datas = result.data;
	    
	    });
	}
	$scope.loadData();
	/* Modal */
	$scope.open = function (size) {
        
        param = {"action":"insert"};
        var data = {"size":"lg","param":param,"template":"modules/prefix/prefixFormView.html","ctrl":"prefixModalCtrl"};
		open_modal(data,$modal);
    };
    $scope.edit_data = function(id)
    {
		param = {"action":"edit","id":id};
    	var data = {"size":"lg","param":param,"template":"modules/prefix/prefixFormView.html","ctrl":"prefixModalCtrl"};
		open_modal(data,$modal);
    }
    $scope.delete_data = function(id){
		param = {"action":"delete","id":id};
    	var data = {"size":"sm","param":param,"template":"modules/confirm/delete_confirm.html","ctrl":"prefixModalCtrl"};
		open_modal(data,$modal);
    }
});