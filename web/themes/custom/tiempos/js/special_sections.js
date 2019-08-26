(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposSections = {
        attach: function (context, settings) {
            $(".view-special-sections > div > a:nth-child(2)").once("tiemposSections").each(function () {
                var text = $("this").text();
                console.log(text);
            });
        }
    };
})(jQuery, Drupal);