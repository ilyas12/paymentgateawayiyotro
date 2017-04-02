app.controller('forumListCtrl', function($scope, $location, $filter,API,PathApiService,ngNotify,AuthService,$state,$stateParams,$modal,$http) {
    
    var state_current = $state.current.name;
    $scope.search_view =0;
    $scope.description = "Forum";
    $scope.datas = [];
    $scope.member_selector = [];
    $scope.member_id = {};
    $scope.showAlert = false;


    $scope.searchMember = function($select) {
        return $http.get(base_system_url+"index.php/Forum/getMemberSelector", {
          params: {
            q: $select.search,
            sensor: false
          }
        }).then(function(response){
            console.log(response.data);
            $scope.member_selector = response.data;
        });
    };

    $scope.loadData = function(page)
    {
        var serach = $scope.search_text !="" && $scope.search_text!=undefined?$scope.search_text:"";
        var paging = page !="" && page!=undefined?page:1;
        var where = "?per_page="+$scope.paging.page_size;
            where += "&page="+paging;
            where += "&q="+serach; 

        var api = PathApiService.forum();

        switch(state_current){
            case "app.forum_search":
                //api += "/load";
                $scope.description = "Forum Search";
                return false;
            break;
            case "app.forum_today":
                $scope.description = "Forum Today";
                api += "/load_today";
            break;
            case "app.forum_on_going":
                $scope.description = "Forum On Going";
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


    $scope.search_forum = function(page)
    {
        var topic = $scope.topic !="" && $scope.topic!=undefined?$scope.topic:"";
        var code = $scope.code !="" && $scope.code!=undefined?$scope.code:"";
        var member_id = $scope.member_id !="" && $scope.member_id.selected!=undefined?$scope.member_id.selected.id:"";
        
        var where = "?topic="+topic+"&member_id="+member_id+"&code="+code;
        
        var api = PathApiService.forum()+"/load";

        API.get(api+where).then(function(result){
            $scope.datas = result.data;
            $scope.paging.total = result.total;
            $scope.search_view =1;
        });
    }

    $scope.detail_data = function(id){
        $state.go('app.forum_detail', { forum_id: id });
    }




    function init(){
        $scope.loadData();
    }
    init();
});