(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.dcRegisterArticle = {
    attach: function (context, settings) {
      $(".owl-carousel").owlCarousel({
        items: 1,
        loop: true,
        center: true,
        autoplay: true,
        nav: true,
        autoplayHoverPause: true,
      });
    }
  };
})(jQuery, Drupal);
