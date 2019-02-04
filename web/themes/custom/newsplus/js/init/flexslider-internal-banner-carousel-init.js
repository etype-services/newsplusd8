(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mtInternalBannerCarousel = {
    attach: function (context, settings) {
      $(context).find("#internal-slider-carousel").once("mtInternalBannerCarouselInit").each(function () {
        var $window = $(window), flexslider;

        // Tiny helper function to add breakpoints.
        function getGridSize() {
          return (window.innerWidth < 768) ? 3 : 6;
        }

        $(this).flexslider({
          animation: "slide",
          controlNav: false,
          animationLoop: false,
          slideshow: false,
          itemWidth: 166,
          itemMargin: 4.8,
          prevText: "",
          nextText: "",
          asNavFor: "#internal-banner-slider",
          minItems: getGridSize(), // Use function to pull in initial value.
          maxItems: getGridSize(), // Use function to pull in initial value.
          start: function (slider) {
            flexslider = slider;
          }
        });
        // Check grid size on resize event.
        $window.resize(function () {
          var gridSize = getGridSize();
          flexslider.vars.minItems = gridSize;
          flexslider.vars.maxItems = gridSize;
        });
        $(this).fadeIn("slow");
      });
    }
  };
})(jQuery, Drupal, drupalSettings);