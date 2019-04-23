(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposcniBehavior = {
        attach: function (context, settings) {
            $(window).load(function () {
                var w = $(".navbar-end").width();
                console.log(w);
                $("#main-navbar-menu").css("padding-left", w + "px");
            });
        }
    };
})(jQuery, Drupal);