(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposcniBehavior = {
        attach: function (context, settings) {
            /* Adjust padding to center main menu. 40 pixels is the width of the
             Social Icons. */
            var count = $(".navbar-end a").length;
            var pad = 40 * count;
            $("#main-navbar-menu").css("padding-left", pad + "px");
        }
    };
})(jQuery, Drupal);