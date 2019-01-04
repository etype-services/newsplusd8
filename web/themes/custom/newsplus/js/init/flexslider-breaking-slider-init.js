(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mtflexsliderBreakingSlider = {
    attach: function (context, settings) {
      $(context).find('.view-titles .flexslider').once('mtflexsliderBreakingSliderInit').each(function() {
        $(this).flexslider({
          animation: drupalSettings.newsplus.flexsliderBreakingSliderInit.breakingEffect,        // Select your animation type, "fade" or "slide"
          slideshowSpeed: drupalSettings.newsplus.flexsliderBreakingSliderInit.breakingEffectTime,   // Set the speed of the slideshow cycling, in milliseconds
          prevText: "",
          nextText: "",
          pauseOnAction: false,
          useCSS: false,
          controlNav: false,
          directionNav: false
        });
        $(this).fadeIn("slow");
        $(".view-titles .view-content, .view-titles .more-link").fadeIn("slow");
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
