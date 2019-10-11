(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusTheme = {
        attach: function (context, settings) {
            var text;
            // Does not add attribute.
            $("#superfish-secondary-menu-toggle, #superfish-main-toggle").attr("aria-label", "Menu Toggle");
        }
    };
})(jQuery, Drupal);