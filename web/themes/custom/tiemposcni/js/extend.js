(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposcniBehavior = {
        attach: function (context, settings) {
            var w = $(".navbar-end").width;
            var pad = (w / 2);
            $("#main-navbar-menu").css("padding-left", pad + "px");
        }
    };
})(jQuery, Drupal);