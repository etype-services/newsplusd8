(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposHomePage = {
        attach: function (context, settings) {
            var imageFixer = function () {
                $(".front-page-sections").each(function () {
                    var obj = this;
                    var height;
                    var heights = [];
                    var maxHeight;
                    $("img", obj).each(function () {
                        height = $(this).height();
                        // console.log(height);
                        heights.push(parseInt(height));
                    });
                    // console.log(heights);
                    maxHeight = Math.min.apply(Math, heights);
                    // console.log(maxHeight);
                    if (maxHeight > 0) {
                        $(".image-container img", obj).css("height", maxHeight);
                    }
                });
            };
            imageFixer();
            $(window).resize(function() {
                imageFixer();
            });
        }
    };
})(jQuery, Drupal);