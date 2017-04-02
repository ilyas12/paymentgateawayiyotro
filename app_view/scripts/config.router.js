'use strict';
/**
* @ngdoc function
* @name app.config:uiRouter
* @description
* # Config
* Config for the router
*/
angular.module('app')
.run(
	['$rootScope', '$state', '$stateParams', 'AUTH_EVENTS', 'AuthService','$log',
		function ( $rootScope,   $state,   $stateParams, AUTH_EVENTS, AuthService,$log) {
			$rootScope.$log = $log;
			$rootScope.$state = $state;
			$rootScope.$stateParams = $stateParams;
			$rootScope.$on('$stateChangeStart', function (event,next, nextParams, fromState) {
				if ('data' in next && 'authorizedRoles' in next.data) {
					var authorizedRoles = next.data.authorizedRoles;
					if (!AuthService.isAuthorized(authorizedRoles)) {
						event.preventDefault();

						$rootScope.$broadcast(AUTH_EVENTS.notAuthorized);

					}
				}

				if (!AuthService.isAuthenticated()) {
					if (next.name !== 'access.login') {
						event.preventDefault();
						$state.go('access.login');
					}
				}

			});
		}
	]
)
.config(
	['$stateProvider', '$urlRouterProvider', 'MODULE_CONFIG','USER_ROLES',
		function ( $stateProvider,   $urlRouterProvider,  MODULE_CONFIG , USER_ROLES,AuthService) {
			$urlRouterProvider.otherwise(function ($injector, $location) {
			var $state = $injector.get("$state");
			$state.go("app.dashboard");
		});
		$stateProvider

			//  route login
			.state('access', {
				url: '/access',
				template: '<div class="indigo bg-big"><div ui-view class="fade-in-down smooth"></div></div>'
			})
			.state('access.login',{
				url:'/login',
				templateUrl:base_view_url+'modules/login/loginFormView.html',
				data:{title:'Login'},
				controller: "loginCtrl",
				resolve:load([
					base_view_url+'modules/login/loginCtrl.js'
				])
			})
			// end route login

			
			.state('app', {
				abstract: true,
				url: '/app',
				views: {
					'': {
						templateUrl: base_view_url+'shared/template/view/layout.html'
					},
					'aside': {
						templateUrl: base_view_url+'shared/template/view/aside.html'
					},
					'content': {
						templateUrl: base_view_url+'shared/template/view/content.html'
					}
				}
			})
			.state('app.dashboard', {
				url: '/dashboard',
				templateUrl: base_view_url+'modules/dashboard/dashboardView.html',
				data : { title: 'Dashboard'},
				controller : "dashboardCtrl",
				resolve: load([
					base_view_url+'modules/dashboard/dashboardCtrl.js',
					'ui.select',
				]),
				activetab: 'Dashboard'
			})
			/* route company */
			.state('app.company', {
				url: '/company',
				templateUrl: base_view_url+'modules/company/companyFormView.html',
				data : { title: 'Company' },
				controller : "companyCtrl",
				resolve: load([
					base_view_url+'modules/company/companyCtrl.js',
					'ui.select',
					]),
				activetab: 'Company'
			})
			/* route User */
			.state('app.user', {
				url: '/user',
				templateUrl: base_view_url+'modules/user/userListView.html',
				data : { title: 'User' },
				controller : "userListCtrl",
				resolve: load([
					base_view_url+'modules/user/userListCtrl.js',
					base_view_url+'modules/user/userModalCtrl.js',
					'ui.select',
					]),
				activetab: 'User'
			})
			.state('app.change_password',{
				url: '/change_password',
				templateUrl: base_view_url+'modules/password/passwordChangeFormView.html',
				data : { title: 'Change Password' },
				controller : 'passwordCtrl',
				resolve : load([
					base_view_url+'modules/password/passwordCtrl.js',
					'ui.select',
				])
			})
			.state('app.user_add', {
				url: '/user_add',
				templateUrl: base_view_url+'modules/user/UserFormView.html',
				data : { title: 'User' },
				controller : "userFormCtrl",
				resolve: load([
					base_view_url+'modules/user/userFormCtrl.js',
					'ui.select',
					]),
				activetab: 'User'
			})
            .state('app.user_edit', {
				url: '/user_edit/:user_id',
				templateUrl: base_view_url+'modules/user/UserFormView.html',
				data : { title: 'User' },
				controllerProvider: function($stateParams){
					 var ctrlName =  "userFormCtrl";
      				 return ctrlName;
				},
				resolve : load([
					base_view_url+'modules/user/userFormCtrl.js',
					'ui.select',
				]),
				activetab: 'User'
			})
			/* route User */
			.state('app.user_group', {
				url: '/user_group',
				templateUrl: base_view_url+'modules/userGroup/userGroupListView.html',
				data : { title: 'User Group' },
				controller : "userGroupListCtrl",
				resolve: load([
					base_view_url+'modules/userGroup/userGroupModalCtrl.js',
					base_view_url+'modules/userGroup/userGroupListCtrl.js',
					'ui.select',
					]),
				activetab: 'User Group'
			})

			.state('app.payment_method', {
				url: '/payment_method',
				templateUrl: base_view_url+'modules/paymentMethod/payment_method.html',
				data : { title: 'Payment Method' },
				controller : "PaymentMethodCtrl",
				resolve : load([
				  base_view_url+'modules/paymentMethod/PaymentMethodCtrl.js',
				  base_view_url+'modules/paymentMethod/PaymentMethodModalCtrl.js',
				  'ui.select'
				  ]),
				activetab: 'Setting'
			})
            
            .state('app.payment_terms', {
				url: '/payment_terms',
				templateUrl: base_view_url+'modules/paymentTerms/payment_terms.html',
				data : { title: 'Payment Terms' },
				controller : "PaymentTermsCtrl",
				resolve : load([
				  base_view_url+'modules/paymentTerms/PaymentTermsCtrl.js',
				  base_view_url+'modules/paymentTerms/PaymentTermsModalCtrl.js',
				  'ui.select'
				  ]),
				activetab: 'Setting'
			})

			.state('app.bank', {
				url: '/bank',
				templateUrl: base_view_url+'modules/bank/bankListView.html',
				data : { title: 'Bank' },
				controller : "BankCtrl",
				resolve : load([
				  base_view_url+'modules/bank/BankCtrl.js',
				  base_view_url+'modules/bank/BankModalCtrl.js',
				  'ui.select'
				  ]),
				activetab: 'Setting'
			})

			.state('app.provinsi', {
				url: '/provinsi',
				templateUrl: base_view_url+'modules/provinsi/provinsiListView.html',
				data : { title: 'List Provinsi' },
				controller : 'ProvinsiCtrl',
				resolve : load([
					base_view_url+'modules/provinsi/ProvinsiCtrl.js',
					base_view_url+'modules/provinsi/ProvinsiModalCtrl.js',
					
				]),
				activetab: 'Customer'
			})
			
			.state('app.kota', {
				url: '/kota',
				templateUrl: base_view_url+'modules/kota/kabkotListView.html',
				data : { title: 'List Kabupaten Kota' },
				controller : 'KabkotCtrl',
				resolve : load([
					base_view_url+'modules/kota/KabkotCtrl.js',
					base_view_url+'modules/kota/KabkotModalCtrl.js',
					
				]),
				activetab: 'Customer'
			})
			.state('app.prefix', {
				url: '/prefix',
				templateUrl: base_view_url+'modules/prefix/prefixListView.html',
				data : { title: 'Prefix' },
				controller : "prefixListCtrl",
				resolve: load([
					base_view_url+'modules/prefix/prefixListCtrl.js',
					base_view_url+'modules/prefix/prefixModalCtrl.js',
					'ui.select',
					]),
				activetab: 'Prefix'
			})
			/*
			.state('app.top_up', {
				url: '/top_up',
				templateUrl: base_view_url+'modules/topUp/topUpListView.html',
				data : { title: 'Top Up' },
				controller : "topUpListCtrl",
				resolve: load([
					base_view_url+'modules/topup/topUpListCtrl.js',
					base_view_url+'modules/topup/topUpModalCtrl.js',
					'ui.select',
					]),
				activetab: 'TOP UP'
			})
			*/
			.state('app.top_up_today', {
				url: '/top_up_today',
				templateUrl: base_view_url+'modules/topUp/topUpListView.html',
				data : { title: 'Top Up Today' },
				controller : "topUpListCtrl",
				resolve: load([
					base_view_url+'modules/topup/topUpListCtrl.js',
					base_view_url+'modules/topup/topUpModalCtrl.js',
					'ui.select',
					]),
				activetab: 'TOP UP'
			})
			.state('app.top_up_on_going', {
				url: '/top_up_on_going',
				templateUrl: base_view_url+'modules/topUp/topUpListView.html',
				data : { title: 'Top Up Ongoing' },
				controller : "topUpListCtrl",
				resolve: load([
					base_view_url+'modules/topup/topUpListCtrl.js',
					base_view_url+'modules/topup/topUpModalCtrl.js',
					'ui.select',
					]),
				activetab: 'TOP UP'
			})
			.state('app.top_up_search', {
				url: '/top_up_search',
				templateUrl: base_view_url+'modules/topUp/topUpFormSearchView.html',
				data : { title: 'Top Up Search' },
				controller : "topUpListCtrl",
				resolve: load([
					base_view_url+'modules/topup/topUpListCtrl.js',
					base_view_url+'modules/topup/topUpModalCtrl.js',
					'ui.select',
					]),
				activetab: 'TOP UP'
			})
			.state('app.withdraw_today', {
				url: '/withdraw_today',
				templateUrl: base_view_url+'modules/withdraw/withdrawListView.html',
				data : { title: 'Withdraw Today' },
				controller : "withdrawListCtrl",
				resolve: load([
					base_view_url+'modules/withdraw/withdrawListCtrl.js',
					base_view_url+'modules/withdraw/withdrawModalCtrl.js',
					'ui.select',
					]),
				activetab: 'TOP UP'
			})
			.state('app.withdraw_on_going', {
				url: '/withdraw_on_going',
				templateUrl: base_view_url+'modules/withdraw/withdrawListView.html',
				data : { title: 'Withdraw Ongoing' },
				controller : "withdrawListCtrl",
				resolve: load([
					base_view_url+'modules/withdraw/withdrawListCtrl.js',
					base_view_url+'modules/withdraw/withdrawModalCtrl.js',
					'ui.select',
					]),
				activetab: 'TOP UP'
			})
			.state('app.withdraw_search', {
				url: '/withdraw_search',
				templateUrl: base_view_url+'modules/withdraw/withdrawFormSearchView.html',
				data : { title: 'Withdraw Search' },
				controller : "withdrawListCtrl",
				resolve: load([
					base_view_url+'modules/withdraw/withdrawListCtrl.js',
					base_view_url+'modules/withdraw/withdrawModalCtrl.js',
					'ui.select',
					]),
				activetab: 'TOP UP'
			})
			.state('app.forum_today', {
				url: '/forum_today',
				templateUrl: base_view_url+'modules/forum/forumListView.html',
				data : { title: 'Forum Today' },
				controller : "forumListCtrl",
				resolve: load([
					base_view_url+'modules/forum/forumListCtrl.js',
					base_view_url+'modules/forum/forumFormCtrl.js',
					base_view_url+'modules/forum/forumModalCtrl.js',
					'ui.select',
					]),
				activetab: 'Forum'
			})
			.state('app.forum_on_going', {
				url: '/forum_on_going',
				templateUrl: base_view_url+'modules/forum/forumListView.html',
				data : { title: 'Forum Ongoing' },
				controller : "forumListCtrl",
				resolve: load([
					base_view_url+'modules/forum/forumListCtrl.js',
					base_view_url+'modules/forum/forumFormCtrl.js',
					base_view_url+'modules/forum/forumModalCtrl.js',
					'ui.select',
					]),
				activetab: 'Forum'
			})
			.state('app.forum_search', {
				url: '/forum_search',
				templateUrl: base_view_url+'modules/forum/forumFormSearchView.html',
				data : { title: 'Forum Search' },
				controller : "forumListCtrl",
				resolve: load([
					base_view_url+'modules/forum/forumListCtrl.js',
					base_view_url+'modules/forum/forumFormCtrl.js',
					base_view_url+'modules/forum/forumModalCtrl.js',
					'ui.select',
					]),
				activetab: 'Forum'
			})
            .state('app.forum_detail', {
				url: '/forum_detail/:forum_id',
				templateUrl: base_view_url+'modules/forum/forumFormView.html',
				data : { title: 'User' },
				controllerProvider: function($stateParams){
					 var ctrlName =  "forumFormCtrl";
      				 return ctrlName;
				},
				resolve : load([
					base_view_url+'modules/forum/forumFormCtrl.js',
					base_view_url+'modules/forum/forumModalCtrl.js',
					'ui.select',
				]),
				activetab: 'Forum'
			})
			.state('app.member', {
				url: '/member',
				templateUrl: base_view_url+'modules/member/memberListView.html',
				data : { title: 'Member' },
				controller : "memberListCtrl",
				resolve: load([
					base_view_url+'modules/member/memberListCtrl.js',
					base_view_url+'modules/member/memberFormCtrl.js',
					base_view_url+'modules/member/memberModalCtrl.js',
					'ui.select',
					]),
				activetab: 'Member'
			})
            .state('app.member_detail', {
				url: '/member_detail/:member_id',
				templateUrl: base_view_url+'modules/member/memberFormView.html',
				data : { title: 'User' },
				controllerProvider: function($stateParams){
					 var ctrlName =  "memberFormCtrl";
      				 return ctrlName;
				},
				resolve : load([
					base_view_url+'modules/member/memberFormCtrl.js',
					base_view_url+'modules/member/memberModalCtrl.js',
					'ui.select',
				]),
				activetab: 'Member'
			})
			.state('app.payment_confirmation_today', {
				url: '/payment_confirmation_today',
				templateUrl: base_view_url+'modules/paymentConfirmation/paymentConfirmationListView.html',
				data : { title: 'Payment Confirmation' },
				controller : "paymentConfirmationListCtrl",
				resolve: load([
					base_view_url+'modules/paymentConfirmation/paymentConfirmationListCtrl.js',
					base_view_url+'modules/paymentConfirmation/paymentConfirmationModalCtrl.js',
					'ui.select',
					]),
				activetab: 'Payment Confirmation'
			})
			.state('app.payment_confirmation_on_going', {
				url: '/payment_confirmation_on_going',
				templateUrl: base_view_url+'modules/paymentConfirmation/paymentConfirmationListView.html',
				data : { title: 'Payment Confirmation Ongoing' },
				controller : "paymentConfirmationListCtrl",
				resolve: load([
					base_view_url+'modules/paymentConfirmation/paymentConfirmationListCtrl.js',
					base_view_url+'modules/paymentConfirmation/paymentConfirmationModalCtrl.js',
					'ui.select',
					]),
				activetab: 'Payment Confirmation'
			})
			.state('app.payment_confirmation_my', {
				url: '/payment_confirmation_my',
				templateUrl: base_view_url+'modules/paymentConfirmation/paymentConfirmationListView.html',
				data : { title: 'My Payment Confirmation ' },
				controller : "paymentConfirmationListCtrl",
				resolve: load([
					base_view_url+'modules/paymentConfirmation/paymentConfirmationListCtrl.js',
					base_view_url+'modules/paymentConfirmation/paymentConfirmationModalCtrl.js',
					'ui.select',
					]),
				activetab: 'Payment Confirmation'
			})
			.state('app.payment_confirmation_search', {
				url: '/payment_confirmation_search',
				templateUrl: base_view_url+'modules/paymentConfirmation/paymentConfirmationFormSearchView.html',
				data : { title: 'My Payment Confirmation ' },
				controller : "paymentConfirmationListCtrl",
				resolve: load([
					base_view_url+'modules/paymentConfirmation/paymentConfirmationListCtrl.js',
					base_view_url+'modules/paymentConfirmation/paymentConfirmationModalCtrl.js',
					'ui.select',
					]),
				activetab: 'Payment Confirmation'
			})
			.state('app.price', {
				url: '/price',
				templateUrl: base_view_url+'modules/price/priceListView.html',
				data : { title: 'Price' },
				controller : "priceListCtrl",
				resolve: load([
					base_view_url+'modules/price/priceModalCtrl.js',
					base_view_url+'modules/price/priceListCtrl.js',
					'ui.select',
					]),
				activetab: 'Setting'
			})
			.state('app.upload', {
				url: '/upload',
				templateUrl: base_view_url+'modules/upload/list.html',
				data : { title: 'Upload' },
				controller : "UploadListCtrl",
				resolve: load([
					base_view_url+'modules/upload/uploadModalCtrl.js',
					base_view_url+'modules/upload/UploadListCtrl.js',
					'ui.select',
					]),
				activetab: 'Setting'
			})
			.state('app.status', {
				url: '/status',
				templateUrl: base_view_url+'modules/status/statusListView.html',
				data : { title: 'status' },
				controller : "statusListCtrl",
				resolve: load([
					base_view_url+'modules/status/statusModalCtrl.js',
					base_view_url+'modules/status/statusListCtrl.js',
					'ui.select',
					]),
				activetab: 'Setting'
			})
			;
			function load(srcs, callback) {
				return {
					deps: ['$ocLazyLoad', '$q',
						function( $ocLazyLoad, $q ){
							var deferred = $q.defer();
							var promise  = false;
							srcs = angular.isArray(srcs) ? srcs : srcs.split(/\s+/);
							if(!promise){
								promise = deferred.promise;
							}
							angular.forEach(srcs, function(src) {
								promise = promise.then( function(){
									angular.forEach(MODULE_CONFIG, function(module) {
										if( module.name == src){
											if(!module.module){
												name = module.files;
											}else{
												name = module.name;
											}
										}else{
											name = src;
										}
									});
									return $ocLazyLoad.load(name);
								});
							});
							deferred.resolve();
							return callback ? promise.then(function(){ return callback(); }) : promise;
						}
					]
				}
			}
		}
	]
);