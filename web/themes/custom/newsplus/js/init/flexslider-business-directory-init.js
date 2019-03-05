(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.mtflexsliderBusinessSlider = {
        attach: function (context, settings) {
            $(context).find("#block-views-block-business-directory-block-1 .flexslider").once("mtflexsliderBusinessSliderInit").each(function () {
                $(this).flexslider({
                    animation: "slide",
                    slideshowSpeed: 5000,
                    prevText: "",
                    nextText: "",
                    pauseOnAction: false,
                    useCSS: false,
                    controlNav: false,
                    directionNav: false
                });
                $(this).fadeIn("slow");
            });
        }
    };
})(jQuery, Drupal, drupalSettings);
