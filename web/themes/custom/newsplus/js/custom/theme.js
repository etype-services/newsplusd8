(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusTheme = {
        attach: function (context, settings) {
            // Does not add attribute.
            $("#superfish-secondary-menu-toggle, #superfish-main-toggle").attr("aria-label", "Menu Toggle");
            // Hacky workaround for Ponca City News
            $("#secondary-menu-userlogout > a").attr("href", "/etype-login");
        }
    };
})(jQuery, Drupal);