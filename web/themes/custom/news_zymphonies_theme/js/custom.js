/* ---------------------------------------------
* Filename:     custom.js
* Version:      1.0.0 (2017-02-12)
* Website:      http://www.zymphonies.com
* Description:  Global Script
* Author:       Zymphonies Team
                info@zymphonies.com
-----------------------------------------------*/

(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newszymphoniesBehavior = {
        attach: function (context, settings) {

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

            var themeMenu = function() {

                // Main menu
                $('#main-menu').smartmenus();

                // Mobile menu toggle
                $('.navbar-toggle').once("newszymphoniesBehavior").click(function () {
                    $('.region-primary-menu').ToggleSlide();
                });

                // Mobile dropdown menu
                if ($(window).width() < 767) {
                    $('.region-primary-menu li a:not(.has-submenu)').click(function () {
                        $('.region-primary-menu').hide();
                    });
                }
            };

            var searchForm = function() {
                $('.block-searchform #edit-submit').once("newszymphoniesBehavior").append("<i class=\"fas fa-search\"></i>");
            };

            // clientsOwl();
            // serviceOwl();
            themeMenu();
            searchForm();

        }
    };
})(jQuery, Drupal);
