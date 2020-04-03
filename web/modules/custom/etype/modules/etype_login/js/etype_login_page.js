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
            $(".user-logged-in .footer-menu a[data-drupal-link-system-path=\"etype-login\"]").parent().addClass("is-really-invisible");
            $(".user-logged-in #etype_e_edition").parent('.menu-item').addClass("is-really-invisible");
        }
    };
})(jQuery, Drupal);