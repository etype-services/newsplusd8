/* jshint esversion: 6 */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposArticle = {
        attach: function (context, settings) {
            let articleCaption;
            let flexCaption = $('.flex-caption');
            let styles = "caption is-sans-serif is-size-7";
            let flexslider = $("#flexslider-1 > .slides li");
            let len = flexslider.length;
            let img = $(".page-node-type-article article .field--name-body img, .page-node-type-article article .field--type-image img");
            console.log(flexCaption.length);
            if (flexCaption.length) {
                img.each(function () {
                    let text = $(this).attr("alt");
                    let width = $(this).width();
                    let imgClass = $(this).attr("class");
                    if (text !== "undefined") {
                        articleCaption = "<span style=\"width:" + width + "px\" class=\"" + styles + "\">" + text + "</span>";
                        $(this).once("tiemposBehavior").attr("class", "").after(articleCaption).parent().addClass(imgClass).css("width", width + "px");
                    }
                });
            }

            if (len > 1) {
                $("#flexslider-1").css("margin-bottom", "3rem");
            }
        }
    };
})(jQuery, Drupal);
