app.controller('PaymentMethodCtrl', function($scope, $location, $filter,API,PathApiService,$modal) {



  	$scope.description = "Payment Method";



  	$scope.datas = [];

	

	var param = {};



	$scope.loadData = function()

	{



	    API.get(PathApiService.payment_method()+"/load").then(function(result){



	        $scope.datas = result.data;

	    

	    });



	}



	$scope.loadData();



	/* Modal */

	$scope.open = function (size) {

        

        param = {"action":"insert"};

        var data = {"size":"lg","param":param,"template":"new_payment_method.html","ctrl":"PaymentMethodModalCtrl"};

		open_modal(data,$modal);



    };



    $scope.edit_data = function(id)

    {

		param = {"action":"edit","id":id};

    	var data = {"size":"lg","param":param,"template":"new_payment_method.html","ctrl":"PaymentMethodModalCtrl"};

		open_modal(data,$modal);

    }



    $scope.delete_data = function(id){

		param = {"action":"delete","id":id};

    	var data = {"size":"sm","param":param,"template":"delete_confirm.html","ctrl":"PaymentMethodModalCtrl"};

		open_modal(data,$modal);



    }



});

