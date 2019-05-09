(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposBreakingNews = {
        attach: function (context, settings) {
            $("#breaking-news").owlCarousel({
                margin: 5,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayHoverPause: true,
                checkVisible: false,
                items: 1,
                loop: true,
                autoplayTimeout: 6015,
                smartSpeed: 6000,
            });
        }
    };
})(jQuery, Drupal);