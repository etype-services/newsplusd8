(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposCarousel = {
        attach: function (context, settings) {
            $("#business-directory").owlCarousel({
                loop: true,
                margin: 5,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayHoverPause: true,
                checkVisible: false,
                responsive:{
                    0:{
                        items: 1
                    },
                    768:{
                        items: 2
                    },
                    1087:{
                        items: 3
                    }
                }
            })
        }
    };
})(jQuery, Drupal);