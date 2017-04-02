'use strict';

/* Filters */
// need load the moment.js to use this filter. 
angular.module('app')
  .filter('fromNow', function() {
    return function(date) {
      return moment(date).fromNow();
    }
  })
  .filter('dateInMillis', function() {
    return function(dateString) {
      return Date.parse(dateString);
    };
  })
  ;


