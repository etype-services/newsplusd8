function($, Drupal, drupalSettings) {
    "use strict";
    Drupal.behaviors.FontFixer = {
        attach: function (context, settings) {
            $(".flex-direction-nav .flex-nav-next").append("<i class=\"fas fa-arrow-left\" aria-hidden=\"true\"></i>");
            $(".flex-direction-nav .flex-nav-prev").append("<i class=\"fas fa-arrow-right\" aria-hidden=\"true\"></i>");
        }
    };
}(jQuery, Drupal, drupalSettings);