(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposBusinessDirectory = {
        attach: function (context, settings) {
            var text;

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
                        items: 2
                    },
                    768:{
                        items: 4
                    },
                }
            });

            /* Add aria-label to special sections image links for ada compliance */
            $("#business-directory .item").once("tiemposSections").each(function () {
                text = $(this).children("a:nth-child(2)").html();
                $(this).children("a:nth-child(1)").attr("aria-label", text);
            });
        }
    };
})(jQuery, Drupal);