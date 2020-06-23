/* jshint esversion: 6 */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposHomePage = {
        attach: function (context, settings) {
            let showImages = function () {
                $(".image-container img").css({
                    visibility: "visible"
                });
            };

            let imageFixer = function () {
                $(".front-page-sections").each(function () {
                    let obj = this;
                    let height;
                    let heights = [];
                    let maxHeight;
                    $("img", obj).each(function () {
                        height = $(this).height();
                        // console.log('height: ' + height);
                        heights.push(parseInt(height));
                    });
                    // console.log(heights);
                    maxHeight = Math.min.apply(Math, heights);
                    // console.log('maxHeight: ' + maxHeight);
                    if (maxHeight > 0) {
                        $(".image-container img", obj).css({
                            height: maxHeight,
                            visibility: "visible"
                        });
                    }
                    $(".image-container").css({
                        background: "none"
                    });
                });
            };
            $(".region-content").imagesLoaded().done(function(instance) {
                // console.log('All images successfully loaded');
                imageFixer();
            });

            $(window).resize(function () {
                imageFixer();
            });

            setTimeout(showImages, 1000);
        }
    };
})(jQuery, Drupal);