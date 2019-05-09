// See https://jsfiddle.net/43g1c395/
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
                autoplayTimeout: 6100,
                smartSpeed: 6000,
            });
        }
    };
})(jQuery, Drupal);