app.controller('SidebarCtrl', function ($scope, $rootScope,$state, PathApiService, AuthService,API,$state,$location,$log) {
	
	//$scope.sidebar = [];
	$scope.header = "Menus";

	var user_id = AuthService.user_id();
	var param = {user_id:user_id};
	$scope.dashboard_url = base_view_url+'#/app/dashboard';
	var api = PathApiService.api()+"/getsidebar/";

  $scope.getsidebar = function(){
      API.post(PathApiService.api()+"/getsidebar/"+AuthService.user_id(),{user_id:AuthService.user_id()}).then(function(result){
        $scope.menus = result.data;
      }, function(err) {
        console.log(err);
      });
  };
  $scope.getsidebar();
  /*
  
              {"label":"[[USER_MANAGEMENT]]","child_count":2,"routes":"#","icon":"fa fa-user","menu_active":"User Management","child":[{"label":"[[USER_LIST]]","routes":"app.user_list"}]},
              {"label":"[[EMPLOYEE]]","child_count":1,"routes":"app.employee_list","icon":"fa fa-user","menu_active":"Employee","child":[{"label":"[[EMPLOYEE]]","routes":"app.employee_list"}]},
              {"label":"[[PURCHASE_ORDER]]","child_count":2,"routes":"app.purchase_list","icon":"fa fa-shopping-cart","menu_active":"Purchase","child":[{"label":"[[SUPPLIER]]","routes":"app.supplier_list"}]}
  */

   // test menus 
   var menus = 
              [
              {"label":"Dashboard","child_count":0,"routes":"app.dashboard","icon":"fa fa-dashboard","menu_active":"Dashboard","child":[]},
              {"label":"Company","child_count":0,"routes":"app.company","icon":"fa fa-building","menu_active":"Company","child":[]},
              {"label":"User","child_count":0,"routes":"app.user","icon":"fa fa-user","menu_active":"User","child":[]},
              ];
    
   	//$scope.menus = menus;
   			
    
});