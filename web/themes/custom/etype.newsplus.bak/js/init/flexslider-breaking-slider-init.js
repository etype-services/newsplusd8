/**
 * @file
 * Add flexslider functionality to breaking news block.
 */

jQuery(document).ready(function () {
    "use strict";
    var obj = jQuery("#breaking-slider");
    console.log(obj.length);
    if (obj.length > 0) {
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
    }
});