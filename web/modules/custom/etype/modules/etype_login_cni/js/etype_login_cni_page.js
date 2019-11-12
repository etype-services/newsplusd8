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
            $('.footer-menu .user-logged-in a[data-drupal-link-system-path="etype-login"]').parent("li").css({
                "display": "none!important", "visibility": "hidden",
            });
        }
    };
})(jQuery, Drupal);