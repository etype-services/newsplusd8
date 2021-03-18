(function ($, Drupal, drupalSettings) {
    "use strict";
    Drupal.behaviors.picoAuthBehavior = {
        attach: function (context, settings) {
          if (drupalSettings.picoLandingPage.length > 1) {
            window.location.replace(drupalSettings.picoLandingPage);
          } else {
            window.location.replace("/?pn=manage_payment");
          }
        }
    };
})(jQuery, Drupal, drupalSettings);
