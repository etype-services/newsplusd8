/**
 * @file
 * Additional theme js.
 */

(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusFixes = {
        attach: function (context, settings) {
            var list = new Array();
            $(".flexslider > .slides > li > img").each(function () {
                var alt = jQuery(this).attr("alt");
                if (list.includes(alt)) {
                    jQuery(this).parent().append("<div class=\"flexslider-img-caption\">" + alt + "</div>");
                    list.push(alt);
                }
                console.log(list);
            });
        }
    };
})(jQuery, Drupal);