(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusTheme = {
        attach: function (context, settings) {
            $("#superfish-secondary-menu-toggle, #superfish-main-toggle").attr("aria-label", "Menu Toggle");
        }
    };
})(jQuery, Drupal);