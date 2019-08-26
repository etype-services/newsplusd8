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
            $("#etype_login_e_edition").once("etypeloginPageBehavior").each(function(e) {
                $(this).attr("id", "etype_login_e_edition_" + e);
            });
        }
    };
})(jQuery, Drupal);