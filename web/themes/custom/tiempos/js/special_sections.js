(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposSections = {
        attach: function (context, settings) {
            var text = $(".view-special-sections > div > a:nth-child(2)").text();
            console.log(text);
        }
    };
})(jQuery, Drupal);