(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposArticle = {
        attach: function (context, settings) {
            var articleCaption;
            var styles = "caption is-sans-serif is-size-7";
            var flexslider = $("#flexslider-1 > .slides li");
            var len = flexslider.length;
            var img = $(".page-node-type-article article .field--name-body img, .page-node-type-article article .field--type-image img");
            img.each(function () {
                var text = $(this).attr("alt");
                var width = $(this).width();
                var imgClass = $(this).attr("class");
                if (text !== "undefined") {
                    articleCaption = "<span style=\"width:" + width + "px\" class=\"" + styles + "\">" + text + "</span>";
                    $(this).once("tiemposBehavior").attr("class", "").after(articleCaption).parent().addClass(imgClass);
                }
            });

            if (len > 1) {
                $("#flexslider-1").css("margin-bottom", "3rem");
            }
        }
    };
})(jQuery, Drupal);
