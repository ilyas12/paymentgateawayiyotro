app
.directive('autoFocus', function($timeout) {
    return {
        restrict: 'AC',
        link: function(_scope, _element) {
            $timeout(function(){
                _element[0].focus();
            }, 0);
        }
    };
})
.directive('stopEvent', function () {
    return {
      restrict: 'A',
      link: function (scope, element, attr) {
        element.on(attr.stopEvent, function (e) {
          e.stopPropagation();
        });
      }
    };
 })
 
.directive('footable_directive', function(){
  return function(scope, element)
  {

    if(scope.$last && !$('.footable').hasClass('footable-loaded')) {
		console.log("a");
            $('.footable').footable();
    }

    var footableObject = $('.footable').data('footable');
    if (footableObject  !== undefined) {
		console.log("a");
            footableObject.appendRow($(element));
    }

  };
 })
.directive('myEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.myEnter);
                });
                event.preventDefault();
            }

            /*
            var code = event.keyCode || event.which;
            if (code === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.myEnter);
                });
                console.log(scope.scan_focus_sn);
                event.preventDefault();
                document.querySelector('#scan_focus_sn').focus();
            }
            */
        });
    };
})
.directive('focusOnShow', function($timeout) {
    return {
        restrict: 'A',
        link: function($scope, $element, $attr) {
            if ($attr.ngShow){
                $scope.$watch($attr.ngShow, function(newValue){
                    if(newValue){
                        $timeout(function(){
                            $element.focus();
                        }, 0);
                    }
                })      
            }
            if ($attr.ngHide){
                $scope.$watch($attr.ngHide, function(newValue){
                    if(!newValue){
                        $timeout(function(){
                            $element.focus();
                        }, 0);
                    }
                })      
            }

        }
    };
})

 .directive('access', function(AuthService) {
    return {
        restrict: 'A',
        link: function($scope, $element, $attr) {
            var result;
            var role  = $attr.access;
            var roles = AuthService.rolePermissions();

            var makeVisible = function () {
                $element.removeClass('hidden');
            };

            var makeHidden = function () {
                $element.addClass('hidden');
            };

            roles = roles.split(",");
            
            if(roles.indexOf(role) >-1 ){
                makeVisible();
            }else{
                makeHidden();
            }


        }
    }
})
 .directive('sameAs', function() {
        return {
            require: 'ngModel',
            link: function(scope, elem, attrs, ngModel) {
                ngModel.$parsers.unshift(validate);

                // Force-trigger the parsing pipeline.
                scope.$watch(attrs.sameAs, function() {
                    ngModel.$setViewValue(ngModel.$viewValue);
                });

                function validate(value) {
                    var isValid = scope.$eval(attrs.sameAs) == value;

                    ngModel.$setValidity('same-as', isValid);

                    return isValid ? value : undefined;
                }
            }
        };
})
.directive('compile', ['$compile', function ($compile) {
    return function(scope, element, attrs) {
        scope.$watch(
            function(scope) {
                return scope.$eval(attrs.compile);
            },
            function(value) {
                element.html(value);
                $compile(element.contents())(scope);
            }
        );
    };
}])
;