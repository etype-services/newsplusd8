(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposArticle = {
        attach: function (context, settings) {
            var flexslider = $("#flexslider-1 > .slides li");
            var len = flexslider.length;
            var img = $(".page-node-type-article article .field--name-body img");
            img.each(function () {
                var text = $(this).attr("alt");
                if (text !== "undefined") {
                    var articleCaption = "<p class=\"caption is-sans-serif is-size-7\">" + text + "</p>";
                    //$(this).parents(".field").once("tiemposBehavior").append(articleCaption);
                    // Because we do not know what the parent might be.
                    $(this).once("tiemposBehavior").after(articleCaption);
                }
            });

            if (len > 1) {
                $("#flexslider-1").css("margin-bottom", "3rem");
            }
        }
    };
})(jQuery, Drupal);
