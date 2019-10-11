(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusTheme = {
        attach: function (context, settings) {
            var text;
            // Does not add attribute.
            $("#superfish-secondary-menu-toggle, #superfish-main-toggle").attr("aria-label", "Menu Toggle");
            // Hacky workaround for Ponca City News
            text = $("#secondary-menu-userlogout > a").attr("href");
            if (text === "/user/login") {
                $("#secondary-menu-userlogout > a").attr("href", "/etype-login");
            }
        }
    };
})(jQuery, Drupal);