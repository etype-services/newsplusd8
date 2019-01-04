(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mtInternalBannerSlider = {
    attach: function (context, settings) {
      $(context).find('#internal-banner-slider').once('mtInternalBannerSliderInit').each(function() {
        $(this).flexslider({
          useCSS: false,
          animation: drupalSettings.newsplus.flexsliderInternalBannerSliderInit.internalBannerEffect,
          controlNav: false,
          directionNav: false,
          animationLoop: false,
          slideshow: false,
          sync: "#internal-slider-carousel"
        });
        $(this).fadeIn("slow");
      });
    }
  };
})(jQuery, Drupal, drupalSettings);