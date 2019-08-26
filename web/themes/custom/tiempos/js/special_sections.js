(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposSections = {
        attach: function (context, settings) {
            $(".view-special-sections .view-content div").each(function () {
                var text = $(this).children("a:nth-child(2)").html();
                console.log(text);
            });
        }
    };
})(jQuery, Drupal);