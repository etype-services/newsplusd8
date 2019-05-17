(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposArticle = {
        attach: function (context, settings) {
            var img = $(".page-node-type-article article .field--name-field-image img");
            var text = img.attr("alt");
            var article_caption = "<p class=\"caption is-sans-serif is-size-7\">" + text + "</p>";
            var flexslider = $("#flexslider-1 > .slides li");
            var len = flexslider.length;
            console.log(len);

            /* Image captions for Article */
            $(".page-node-type-article .field--name-field-image").once("tiemposBehavior").append(article_caption);

            if (len > 1) {
                $("#flexslider-1").css("margin-bottom", "3rem");
            }
        }
    };
})(jQuery, Drupal);