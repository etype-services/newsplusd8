(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposClassified = {
        attach: function (context, settings) {
            $(".view-classified h3").once("tiemposClassified").each(function () {
                var href = $(this).children("a").attr("href");
                var res = href.split("/");
                $(this).addClass(res[2]);
            });
        }
    };
})(jQuery, Drupal);