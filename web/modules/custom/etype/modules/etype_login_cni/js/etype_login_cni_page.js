(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeloginPageBehavior = {
        attach: function (context, settings) {
            /* Wildcard to allow for domain in url */
            $('a[href*="/etype-login"]').click(function () {
                Cookies.set("redirectDestination", window.location.pathname);
            });
            $('a[href*="/user/logout"]').click(function () {
                Cookies.remove("redirectDestination");
            });
            $('.user-logged-in a[data-drupal-link-system-path="etype-login"]').parent().css({
                "display": "none",
                "visibility": "hidden"
            });
        }
    };
})(jQuery, Drupal);