define([
    'jquery'
], function($) {
    'use strict';
    return function(target) {
        return target.extend({
            isVisible: function () {
                var msgs = this.messageContainer.errorMessages();
                for (var i = 0; i < msgs.length; i++)
                {

                }
                return this.isHidden(this.messageContainer.hasMessages());
            }
        });
    };
});
