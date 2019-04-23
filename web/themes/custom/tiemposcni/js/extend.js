(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposcniBehavior = {
        attach: function (context, settings) {
            var w = $(".navbar-end").width();
            console.log(w);
            var pad = (w / 2);
            console.log(pad);
            $("#main-navbar-menu").css("padding-left", pad + "px");
        }
    };
})(jQuery, Drupal);