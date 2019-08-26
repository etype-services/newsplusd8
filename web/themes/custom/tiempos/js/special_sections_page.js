(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposSectionsPage = {
        attach: function (context, settings) {
            /* Add aria-label to special sections image links for ada compliance */
            $("article").once("tiemposSectionsPage").each(function () {
                var text = $(this).children("a:nth-child(2)").html();
                $(this).children("a:nth-child(1)").attr("aria-label", text);
            });
        }
    };
})(jQuery, Drupal);