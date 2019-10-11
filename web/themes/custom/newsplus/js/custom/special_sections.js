(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusSections = {
        attach: function (context, settings) {
            var text;
            /* Add aria-label to special sections image links for ada compliance */
            $(".view-special-sections .views-field-field-listing-image img").once("newsplusSections").each(function () {
                text = $(this).attr("title");
                $(this).attr("aria-label", text);
            });
        }
    };
})(jQuery, Drupal);