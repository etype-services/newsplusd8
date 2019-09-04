(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposSections = {
        attach: function (context, settings) {
            var text;
            /* Add aria-label to special sections image links for ada compliance */
            $(".view-special-sections .view-content div").once("tiemposSections").each(function () {
                text = $(this).children('a:nth-child(2)').html();
                console.log(text);
                if (text === "") {
                    text = $(this).children("a:nth-child(1)").attr("title", text);
                }
                $(this).children("a:nth-child(1)").attr("aria-label", text);
            });
        }
    };
})(jQuery, Drupal);