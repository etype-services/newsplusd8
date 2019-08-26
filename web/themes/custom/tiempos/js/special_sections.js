(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposSections = {
        attach: function (context, settings) {
            var text = $(".view-special-sections .view-content > div > a").html();
            console.log(text);
        }
    };
})(jQuery, Drupal);