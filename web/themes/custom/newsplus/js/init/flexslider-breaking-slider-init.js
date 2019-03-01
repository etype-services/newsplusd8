jQuery(document).ready(function ($) {
    "use strict";
    var obj = S("#block-views-block-breaking-news-block-1 .item-list .flexslider");
    if (obj.length > 0) {
        $(window).load(function () {
            obj.fadeIn("slow");
            obj.flexslider({
                animation: drupalSettings.newsplus.flexsliderBreakingSliderInit.breakingEffect,
                slideshowSpeed: drupalSettings.newsplus.flexsliderBreakingSliderInit.breakingEffectTime,
                prevText: "",
                nextText: "",
                pauseOnAction: false,
                useCSS: false,
                controlNav: false,
                directionNav: false
            });

        });
    }
});
