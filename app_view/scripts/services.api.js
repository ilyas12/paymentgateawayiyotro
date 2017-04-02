angular.module('app.services', [])
/* Post Data to API */
.factory('API',function($http,$q,AuthService,$state,ngNotify){
	return{
		get: function(api_url) {
			var returnData = $q.defer();
			if(api_url.indexOf("?") > -1){
				if(AuthService.user_id()!=''){
					api_url +='&user_login='+AuthService.user_id();
				}
			}else{
				api_url +='?user_login='+AuthService.user_id();
			}
			$http({
				url: api_url,
				method: 'GET'
			}).success(function(data) {

				if(data.error == '404'){
					AuthService.logout();
					//$state.go('access.login');
				}
				returnData.resolve(data);
			}).error(function(error) {
				alert_ngnotify('error','cannot connect database, plese contact your administator',ngNotify);
				console.log(error);
				returnData.reject();
			});
			// returns the promise of the API call - whether successful or not
			return returnData.promise;
		},
		post: function(api_url,obj) {
		// tell Angular to wait for iiiiiiiiiiiit....
			var returnData = $q.defer();
			$http({
				url: api_url,
				method: "POST",
				data: obj
			}).success(function(data) {
				
				if(data.error == '404'){
					AuthService.logout();
					//$state.go('access.login');
				}
				returnData.resolve(data);
			}).error(function(error) {
				alert_ngnotify('error','cannot connect database, plese contact your administator',ngNotify);
				console.log(error);
				returnData.reject();
			});
			// returns the promise of the API call - whether successful or not
			return returnData.promise;
		}
	}
})
/* Url Config */
.service('PathApiService',function(){
	var login 					= base_system_url+'index.php/AuthController';
	var api	 					= base_system_url+'index.php/ApiController';
	
	/* Company */
	var company 				= base_system_url+'index.php/Company';
	var department 				= base_system_url+'index.php/department';

	var user          			= base_system_url+'index.php/User';
	var user_group				= base_system_url+'index.php/UserGroup';

	var bank					= base_system_url+'index.php/Bank';
	var provinsi				= base_system_url+'index.php/Provinsi';
	var kabupaten_kota			= base_system_url+'index.php/Kabkot';

	var payment_method			= base_system_url+'index.php/PaymentMethod';
    var payment_terms			= base_system_url+'index.php/PaymentTerms';
    var prefix	 				= base_system_url+'index.php/Prefix';

    var language				= base_system_url+'index.php/Language';

    var top_up	 				= base_system_url+'index.php/TopUp';
    var withdraw	 			= base_system_url+'index.php/Withdraw';
    var forum	 				= base_system_url+'index.php/Forum';
    var member	 				= base_system_url+'index.php/Users';

    var payment_confirmation	= base_system_url+'index.php/PaymentConfirmation';
    var price	 				= base_system_url+'index.php/Price';
    var status	 				= base_system_url+'index.php/Status';
    var upload	 				= base_system_url+'index.php/Upload';

	
	return {
		
		login						: function() 	{return login;},
		api							: function()	{return api;},
		/*Company */
		company						: function() 	{return company;},
		company_settings			: function() 	{return company_settings;},
		
		language 					: function()	{return language;},

		payment_method 				: function()	{return payment_method;},
        payment_terms 				: function()	{return payment_terms;},

		system_settings 			: function()	{return system_settings;},

		user  						: function()	{return user;},
		prefix						: function()	{return prefix;},
		user_group  				: function()	{return user_group;},
		
		department 					: function() 	{return department;},
		bank						: function()	{return bank;},
		provinsi					: function()	{return provinsi;},
		kabkot						: function()	{return kabupaten_kota;},

		prefix						: function()	{return prefix;},
		top_up						: function()	{return top_up;},
		withdraw					: function()	{return withdraw;},
		forum						: function()	{return forum;},
		member						: function()	{return member;},
		
		payment_confirmation		: function()	{return payment_confirmation;},
		price						: function()	{return price;},
		status						: function()	{return status;},
		upload						: function()	{return upload;},
		
		
	};
	
})
/* Auth Config */
.service('AuthService', function($q, $http, USER_ROLES,$base64,$interval,$rootScope,$document,$state) {
	var LOCAL_TOKEN_KEY = 'Studio1234567890!@#$%^&&*)_';
	var username = '';
	var user_id = '';
	var isAuthenticated = false;
	var role = '';
	var user_access_group_id = '';
	var rolePermissions='';
	var expiry='';
	
	var authToken;
	function loadUserCredentials() {
		var token = window.localStorage.getItem(LOCAL_TOKEN_KEY);
		if (token) {
			useCredentials(token);
		}
	}
	function storeUserCredentials(token) {
		window.localStorage.setItem(LOCAL_TOKEN_KEY, token);
		useCredentials(token);
	}
	function useCredentials(token) {
		token = $base64.decode(token);
		username = token.split('.')[0];
		isAuthenticated = true;
		authToken = token;
		rolePermissions = token.split('.')[3];
		expiry = token.split('.')[4];
		user_id = token.split('.')[1];
		user_access_group_id = token.split('.')[2];
		switch(token.split('.')[2]){
			case "1":
				role = USER_ROLES.admin;
			break;
			default : role = "user";
		}
		//role = USER_ROLES.admin
		// Set the token as header for your requests!
		$http.defaults.headers.common['X-Auth-Token'] = token;


		var time = new Date();
			time = time.getTime();
		var d = parseFloat(time) - parseFloat(expiry);
		//console.log(expiry+'__'+time+'__'+d);
		// 10 minute = 600000
		if(d > 600000){
			logout();
		}else{
			var time = new Date();
			time = time.getTime()+(10*60*1000);
			if(d > 300000){
				storeUserCredentials($base64.encode(username+'.'+user_id+'.'+token.split('.')[2]+"."+rolePermissions+"."+time));	
			}
		}

	}
	function destroyUserCredentials() {
		authToken = undefined;
		username = '';
		role = '';
		isAuthenticated = false;
		user_id = '';
		$http.defaults.headers.common['X-Auth-Token'] = undefined;
		window.localStorage.removeItem(LOCAL_TOKEN_KEY);
	}
	var login = function(data) {
		return $q(function(resolve, reject) {
			if(data.error == 0){
				var time = new Date();
					time = time.getTime()+(10*60*1000);
					//time = time.getTime()+(*1000);
				// Make a request and receive your auth token from your server
				storeUserCredentials($base64.encode(data.data.full_name+'.'+data.data.id+'.'+data.data.user_access_group_id+"."+data.data.role+"."+time));
				resolve('Login success.');
				set_time();
			} else {
				reject('Login Failed.');
			}
		});
	};
	var logout = function() {
		destroyUserCredentials();
		/* session destroy */
		$http.get(base_system_url+'index.php/AuthController/logout/').success(function(data) {
			$state.go('access.login');
        }).error(function(data, status, headers, config) {

        });
	};
	var isAuthorized = function(authorizedRoles) {
		if (!angular.isArray(authorizedRoles)) {
			authorizedRoles = [authorizedRoles];
		}
		return (isAuthenticated && authorizedRoles.indexOf(role) !== -1);
	};
	var is_login = function (){
		loadUserCredentials();
		if(user_id!=''){
			var time = new Date();
			//console.log(time.getTime()+' '+$rootScope.idleEndTime);
			if (time.getTime()>$rootScope.idleEndTime)
	        {
	        	logout();
	        }else{
	        	$rootScope.idleEndTime = time.getTime()+(10*60*1000);//; //reset end time
	        }
	    }

	}
	function set_time(){
		if(user_id!=''){
			var time = new Date();
			$rootScope.idleEndTime = time.getTime()+(10*60*1000); // 10 minute
		}
	}
	loadUserCredentials();
	return {
		login			: login,
		logout			: logout,
		is_login		: is_login,
		isAuthorized	: isAuthorized,
		isAuthenticated	: function() {return isAuthenticated;},
		username		: function() {return username;},
		user_id			: function() {return user_id;},
		role			: function() {return role;},
		rolePermissions	: function() {return rolePermissions;}
	};
})
.service('ConncetionService', function($q, $http, $rootScope,AuthService) {

})
.factory('AuthInterceptor', function ($rootScope, $q, AUTH_EVENTS) {
	return {
		responseError: function (response) {
			$rootScope.$broadcast({
				401: AUTH_EVENTS.notAuthenticated,
				403: AUTH_EVENTS.notAuthorized
			}[response.status], response);
			return $q.reject(response);
		}
	};
})
.config(function ($httpProvider) {
	$httpProvider.interceptors.push('AuthInterceptor');
})


.factory('Func', function() {
    return {
		isUndefined : function(val){
			//return angular.isUndefined(val) || val === null ;
			return !angular.isDefined(val) || val===null;
		},
		isNumber : function(n){
			return !isNaN(parseFloat(n)) && isFinite(n);
		}
	}
});