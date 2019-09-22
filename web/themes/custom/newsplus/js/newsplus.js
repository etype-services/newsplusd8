(function ($, Drupal) {
    "use strict"
    Drupal.behaviors.newsplusIframes = {
        attach: function (context, settings) {
            $("iframe").each(function () {
                var attr = $(this).attr("title");
                if (typeof attr === typeof undefined || attr === false) {
                    $(this).attr("title", "Iframe loaded from external site");
                }
            });
        }
    };
})(jQuery, Drupal);