(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeloginPageBehavior = {
        attach: function (context, settings) {
            $('a[href*="/etype-login"]').click(function () {
                Cookies.set("redirectDestination", window.location.pathname);
            });
            $('a[href*="/user/logout"]').click(function () {
                Cookies.remove("redirectDestination");
            });
        }
    };
})(jQuery, Drupal);