(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposClassified = {
        attach: function (context, settings) {
            $(".view-classified h3").once("tiemposClassified").each(function () {
                var href = $(this).children("a").attr("href");
                var res = href.split("/");
                $(this).addClass(res[2]);
            });
            $(".view-header h1").each(function () {
                var href = window.location.pathname.split("/");
                console.log(window.location.pathname);
                $(this).addClass(href[2]);
            });
        }
    };
})(jQuery, Drupal);