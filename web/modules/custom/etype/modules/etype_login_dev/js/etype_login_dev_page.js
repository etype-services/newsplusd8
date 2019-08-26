(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeloginPageBehavior = {
        attach: function (context, settings) {
            var key = 1;
            /* Wildcard to allow for domain in url */
            $('a[href*="/etype-login"]').click(function () {
                Cookies.set("redirectDestination", window.location.pathname);
            });
            $('a[href*="/user/logout"]').click(function () {
                Cookies.remove("redirectDestination");
            });
            $("#etype_login_e_edition").each(function () {
                $(this).attr("id", "etype_login_e_edition_" + key);
                key += 1;
            });
        }
    };
})(jQuery, Drupal);