/* jshint esversion: 6 */
(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposFrontPageSections = {
        attach: function (context, settings) {

            let showImages = function () {
                $(".image-container").css({
                    background: "none"
                });
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
                  let titleHeight;
                  let titleHeights = [];
                  let titleMaxHeight;
                  $("h2", obj).each(function () {
                    titleHeight = $(this).height();
                    titleHeights.push(parseInt(titleHeight));
                  });
                  titleMaxHeight = Math.max.apply(Math, titleHeights);
                  // console.log(titleHeights);
                  $("h2", obj).css({
                    height: titleMaxHeight,
                  });
                });
            };
            $(".front-page-sections").imagesLoaded().done(function (instance) {
              // console.log('All images successfully loaded');
              imageFixer();
            });

            $(window).resize(function () {
              $(".front-page-sections h2").css({
                height: "unset",
              });
              imageFixer();
            });

            // Make sure images show, as sometimes buggy ad scripts seem to prevent the script completing.
            setTimeout(showImages, 1500);
        }
    };
})(jQuery, Drupal);
