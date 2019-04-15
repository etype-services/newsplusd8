(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposBehavior = {
        attach: function (context, settings) {
            $(".navbar-burger", context).once("tiemposBehavior").click(function () {
                $(".navbar-start").toggleClass("is-really-invisible");
            });
        }
    };
})(jQuery, Drupal);