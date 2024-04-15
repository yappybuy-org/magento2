

define([
'uiComponent',
'jquery',
'ko',
], function(Component, $, ko) {
	'use strict';
    return Component.extend({
		yappyBuyPressed:function(data, event) {
			alert('yappyBuyPressed');
	    },
		isActive:function(){
			return true;
		}
	});
});