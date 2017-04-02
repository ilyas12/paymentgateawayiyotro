app.controller('MainCtrl', ['$scope', '$location', '$filter', function($scope, $location, $filter,API,PathApiService,AuthService) {

  $scope.user_id = AuthService.user_id();

}]);