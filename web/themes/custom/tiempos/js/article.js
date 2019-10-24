(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposArticle = {
        attach: function (context, settings) {
            var articleCaption;
            var flexslider = $("#flexslider-1 > .slides li");
            var len = flexslider.length;
            var img = $(".page-node-type-article article .field--name-body img, .page-node-type-article article .field--type-image img");

            img.each(function () {
                var text = $(this).attr("alt");
                var styles = "caption is-sans-serif is-size-7";

                if (text !== "undefined") {
                    if ($(this).hasClass("align-left")) {
                        styles += " align-left";
                        articleCaption = "<p class=\"" + styles + "\">" + text + "</p>";
                        $(this).once("tiemposBehavior").wrap("<div class=\"align-left\"></div>").after(articleCaption);
                    } else {
                        articleCaption = "<p class=\"" + styles + "\">" + text + "</p>";
                        $(this).once("tiemposBehavior").after(articleCaption);
                    }
                }
            });

            if (len > 1) {
                $("#flexslider-1").css("margin-bottom", "3rem");
            }
        }
    };
})(jQuery, Drupal);
