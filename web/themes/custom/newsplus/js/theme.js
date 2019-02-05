/**
 * @file
 * Additional theme js.
 */

(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusFixes = {
        attach: function (context, settings) {
            var flexImgList = new Array();
            $("img.image-style-flexslider-full").each(function () {
                //var alt = jQuery(this).attr("alt");
                //console.log(alt);
                //if (!flexImgList.includes(alt)) {
                //    jQuery(this).parent().append("<div class=\"flexslider-img-caption\">" + alt + "</div>");
                //    flexImgList.push(alt);
                //}
                console.log(jQuery(this));
            });
        }
    };
})(jQuery, Drupal);