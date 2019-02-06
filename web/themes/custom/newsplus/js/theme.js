/**
 * @file
 * Additional theme js.
 */

(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusFixes = {
        attach: function (context, settings) {
            $(".dfp").find("*").css("max-width", "100%");
        }
    };
})(jQuery, Drupal);