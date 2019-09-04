(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposSections = {
        attach: function (context, settings) {
            var text;
            /* Add aria-label to special sections image links for ada compliance */
            $(".view-special-sections .view-content div").once("tiemposSections").each(function () {
                text = $(this).children("a:nth-child(2)").html();
                if (text === undefined) {
                    /*
                     * CNI sites don't show the title.
                     * it is hidden in the views and added as the "title" for the link.
                     */
                    text = $(this).children("a:nth-child(1)").attr("title");
                }
                $(this).children("a:nth-child(1)").attr("aria-label", text);
            });
        }
    };
})(jQuery, Drupal);