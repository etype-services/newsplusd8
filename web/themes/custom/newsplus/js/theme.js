/**
 * @file
 * Additional theme js.
 */

(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusFixes = {
        attach: function (context, settings) {
            $(".flexslider > .slides > li > img").parent().append("<div>" + $("this").attr("alt") + "</div>");
        }
    };
})(jQuery, Drupal);