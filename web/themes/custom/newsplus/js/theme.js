/**
 * @file
 * Additional theme js.
 */

(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusFixes = {
        attach: function (context, settings) {
            $(".flexslider .slides li img").each(function () {
                var alt = jQuery(this).attr('alt');
                jQuery(this).parent().append("<div class=\"flexslider-img-caption\">" + alt + "</div>");
            });
        }
    };
})(jQuery, Drupal);