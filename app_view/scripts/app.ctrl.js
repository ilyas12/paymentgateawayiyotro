'use strict';

/**
 * @ngdoc function
 * @name app.controller:AppCtrl
 * @description
 * # MainCtrl
 * Controller of the app
 */
angular.module('app')  
  .controller('AppCtrl', ['$scope', '$translate', '$localStorage', '$window', '$document', '$location', '$rootScope', '$timeout', '$mdSidenav', '$mdColorPalette', '$anchorScroll','USER_ROLES','AuthService','$state','API','PathApiService',
                                               'AuthService','$templateCache',
    function ( $scope,   $translate,   $localStorage,   $window,   $document,   $location,   $rootScope,   $timeout,   $mdSidenav,   $mdColorPalette,   $anchorScroll , USER_ROLES, AuthService,$state,API,PathApiService,$templateCache ) {
      // add 'ie' classes to html
      var isIE = !!navigator.userAgent.match(/MSIE/i) || !!navigator.userAgent.match(/Trident.*rv:11\./);
      isIE && angular.element($window.document.body).addClass('ie');
      isSmartDevice( $window ) && angular.element($window.document.body).addClass('smart');
      // config
	  var user_id = AuthService.user_id();
	   
      $document.find('body').on('mousemove keydown DOMMouseScroll mousewheel mousedown touchstart',function  () {
        AuthService.is_login();
      });
      window.onunload = function () {
        console.log('close');
      }

      $scope.paging = {page_size : 10,total : 0};
      $scope.app = {
        name: application_name,
        version: '1.0',
        // for chart colors
        color: {
           primary: '#3f51b5',
          info:    '#2196f3',
          success: '#4caf50',
          warning: '#ffc107',
          danger:  '#f44336',
          accent:  '#7e57c2',
          white:   '#ffffff',
          light:   '#f1f2f3',
          dark:    '#475069'
        },
        setting: {
          theme: {
            primary: 'green',
            accent: 'light-green',
            warn: 'lime'
          },
          asideFolded: false
        },
        search: {
          content: '',
          show: false
        }
      }

      $scope.setTheme = function(theme){
        $scope.app.setting.theme = theme;
      }
      $rootScope.$on('$routeChangeStart', function(event, next, current) {
          if (typeof(current) !== 'undefined'){
              $templateCache.remove(current.templateUrl);
          }
      });
      $window.onload = function() {
        
      };
      // save settings to local storage
      if ( angular.isDefined($localStorage.appSetting) ) {
		
        $scope.app.setting = $localStorage.appSetting;
		
      } else {
        $localStorage.appSetting = $scope.app.setting;
      }
	  
      $scope.$watch('app.setting', function(){
        $localStorage.appSetting = $scope.app.setting;
      }, true);

      // angular translate
      $scope.langs = {en_US:'English', zh_CN:'中文', id_IDN:'Indonesia', vi_VIE:'Vietnam'};
      $scope.selectLang = $scope.langs[$translate.proposedLanguage()] || "English";
      $scope.setLang = function(langKey) {
        // set the current lang
        $scope.selectLang = $scope.langs[langKey];
        // You can change the language during runtime
        $translate.use(langKey);
      };

      function isSmartDevice( $window ) {
        // Adapted from http://www.detectmobilebrowsers.com
        var ua = $window['navigator']['userAgent'] || $window['navigator']['vendor'] || $window['opera'];
        // Checks for iOs, Android, Blackberry, Opera Mini, and Windows mobile devices
        return (/iPhone|iPod|iPad|Silk|Android|BlackBerry|Opera Mini|IEMobile/).test(ua);
      };

      $scope.getColor = function(color, hue){
        if(color == "bg-dark" || color == "bg-white") return $scope.app.color[ color.substr(3, color.length) ];
        return rgb2hex($mdColorPalette[color][hue]['value']);
      }

      //Function to convert hex format to a rgb color
      function rgb2hex(rgb) {
        return "#" + hex(rgb[0]) + hex(rgb[1]) + hex(rgb[2]);
      }

      function hex(x) {
        var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 
        return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
      }

      $rootScope.$on('$stateChangeSuccess', openPage);

      function openPage() {
        $scope.app.search.content = '';
        $scope.app.search.show = false;
        $scope.closeAside();
        // goto top
        $location.hash('view');
        $anchorScroll();
        $location.hash('');
       
        $scope.route = $state.current.activetab;
		
		    $scope.username = AuthService.username() !=''?AuthService.username():"Test Admin";

      }

      $scope.goBack = function () {
        $window.history.back();
      }

      $scope.openAside = function () {
        $timeout(function() { $mdSidenav('aside').open(); });
      }
      $scope.closeAside = function () {
        $timeout(function() { $document.find('#aside').length && $mdSidenav('aside').close(); });
      }

      $scope.logout = function() {
		  API.post(PathApiService.login()+'/logout/',{user_id:AuthService.user_id()}).then(function(result){});
        AuthService.logout();
        $state.go('access.login');
      };
      
    $scope.change_password = function () {
      $state.go('app.change_password');
    }
		
	$scope.notification_user = [];
	
	$scope.$on('new_message', function(event,anyData) { 

		if(anyData.user_id == user_id){
			
			$scope.$apply(function () {
				$scope.notification = 1;
				$scope.notification_user[anyData.user_from] = 1;
			});
		
		}
		
  });
      
  $scope.reset_notification = function(){
	
		$scope.notification = '';
	};
	
	$scope.base_url = base_url;
	
  }]);
