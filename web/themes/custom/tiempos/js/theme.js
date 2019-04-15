(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposBehavior = {
        attach: function (context, settings) {
            $(".navbar-brand", context).once("tiemposBehavior").click(function () {
                $(".navbar-start").toggleClass("is-really-invisible");
            });
        }
    };
})(jQuery, Drupal);