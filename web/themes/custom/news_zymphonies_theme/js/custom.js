(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newszymphoniesBehavior = {
        attach: function (context, settings) {

            /*
            var clientsOwl = function() {
                $('.field--name-field-clients-logo').owlCarousel({
                    items: 2, margin: 10, dots: true, autoPlay: 3000, navigation: true, responsive: {
                        500: {items: 2, dots: true, navigation: true},
                        700: {items: 3, dots: true, navigation: true},
                        900: {items: 5, dots: true, navigation: true},
                    },
                });
            };

            var serviceOwl  = function() {
                $('.field--name-field-service').owlCarousel({
                    items: 1, margin: 10, dots: true, autoPlay: 3000, navigation: true, responsive: {
                        500: {items: 1, dots: true, navigation: true},
                        700: {items: 2, dots: true, navigation: true},
                        900: {items: 4, dots: true, navigation: true},
                    },
                });
            };
            */

            var themeMenu = function themeMenuFunc() {

                // Mobile menu toggle
                $('.navbar-toggle').once("newszymphoniesBehavior").click(function () {
                    $('#superfish-main-accordion').toggleClass('sf-hidden').slideToggle();
                });

            };

            var moreLink = function moreLinkFunc() {
                $(".more-link a").once("newszymphoniesBehavior").append('<i class="fas fa-angle-double-right"></i>');
            };

            // clientsOwl();
            // serviceOwl();
            themeMenu();
            moreLink();

        }
    };
})(jQuery, Drupal);
