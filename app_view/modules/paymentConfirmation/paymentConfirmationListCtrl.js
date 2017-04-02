app.controller('paymentConfirmationListCtrl', function($scope, $location, $filter,API,PathApiService,ngNotify,AuthService,$state,$stateParams,$modal) {
    
    var state_current = $state.current.name;
    $scope.search_view =0;
    $scope.description = "Payment Confirmation";
    $scope.datas = [];
    $scope.showAlert = false;
    $scope.user_id = String(AuthService.user_id());
    console.log($scope.user_id);

    $scope.loadData = function(page)
    {
        var serach = $scope.search_text !="" && $scope.search_text!=undefined?$scope.search_text:"";
        var paging = page !="" && page!=undefined?page:1;
        var where = "?per_page="+$scope.paging.page_size;
            where += "&page="+paging;
            where += "&q="+serach; 

        var api = PathApiService.payment_confirmation();

        switch(state_current){
            case "app.payment_confirmation_search":
                //api += "/load";
                $scope.description = "Payment Confirmation Search";
                return false;
            break;
            case "app.payment_confirmation_today":
                $scope.description = "Payment Confirmation Today";
                api += "/load_today";
            break;
            case "app.payment_confirmation_on_going":
                $scope.description = "Payment Confirmation On Going";
                api += "/load_on_going";
            break;
            case "app.payment_confirmation_my":
                $scope.description = "My Payment Confirmation ";
                api += "/load_my";
            break;
            default : api += "/load";
        }

        API.get(api+where).then(function(result){
            $scope.datas = result.data;
            console.log(result.data);
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


    $scope.search_payment = function(page)
    {
        var search_payment_confirmation = $scope.search_payment_confirmation !="" && $scope.search_payment_confirmation!=undefined?$scope.search_payment_confirmation:"";

        /*
        if(withdraw_code==""){
            alert_ngnotify('error',' top up number can not be empty ',ngNotify);
            $scope.search_view = 0;
            return false;
        }
        */
        var where = "?q="+search_payment_confirmation;
        var api = PathApiService.payment_confirmation()+"/load";

        API.get(api+where).then(function(result){
            $scope.datas = result.data;
            $scope.paging.total = result.total;
            $scope.search_view =1;
        });
    }

    $scope.complate_data = function(id){
        param = {"action":"complate_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"paymentConfirmationModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.hold_data = function(id){
        param = {"action":"hold_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"paymentConfirmationModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.approve_data = function(id){
        param = {"action":"approve_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"paymentConfirmationModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.cancel_data = function(id){
        param = {"action":"cancel_data","id":id};
        var data = {"size":"sm","template":"modules/confirm/confirm.html","ctrl":"paymentConfirmationModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.escalation_data = function  (id) {
        param = {"action":"approve_to_data","id":id};
        var data = {"size":"lg","template":"modules/confirm/confirmApproveUser.html","ctrl":"paymentConfirmationModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.detail_data = function  (id) {
        param = {"action":"detail_data","id":id};
        var data = {"size":"lg","template":"modules/paymentConfirmation/paymentConfirmationDetailView.html","ctrl":"paymentConfirmationModalCtrl","param":param};
        open_modal(data,$modal);
    }
    $scope.edit_fee = function  (id) {
        param = {"action":"edit_fee","id":id};
        var data = {"size":"lg","template":"modules/paymentConfirmation/paymentConfirmationEditFeeView.html","ctrl":"paymentConfirmationModalCtrl","param":param};
        open_modal(data,$modal);
    }




    function init(){
        $scope.loadData();
    }
    init();
});