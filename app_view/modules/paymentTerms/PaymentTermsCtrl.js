app.controller('PaymentTermsCtrl', function($scope, $location, $filter,API,PathApiService,$modal) {



  	$scope.description = "Payment Terms";



  	$scope.datas = [];

	

	var param = {};



	$scope.loadData = function()

	{



	    API.get(PathApiService.payment_terms()+"/load").then(function(result){



	        $scope.datas = result.data;

	    

	    });



	}



	$scope.loadData();



	/* Modal */

	$scope.open = function (size) {

        

        param = {"action":"insert"};

        var data = {"size":"lg","param":param,"template":"new_payment_terms.html","ctrl":"PaymentTermsModalCtrl"};

		open_modal(data,$modal);



    };



    $scope.edit_data = function(id)

    {

		param = {"action":"edit","id":id};

    	var data = {"size":"lg","param":param,"template":"new_payment_terms.html","ctrl":"PaymentTermsModalCtrl"};

		open_modal(data,$modal);

    }



    $scope.delete_data = function(id){

		param = {"action":"delete","id":id};

    	var data = {"size":"sm","param":param,"template":"delete_confirm.html","ctrl":"PaymentTermsModalCtrl"};

		open_modal(data,$modal);



    }



});

