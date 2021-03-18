(function ($, Drupal, drupalSettings) {
    "use strict";
    Drupal.behaviors.picoAuthBehavior = {
        attach: function (context, settings) {
          // console.log(drupalSettings.toknizdUrl);
          window.location.replace(drupalSettings.toknizdUrl);
        }
    };
})(jQuery, Drupal, drupalSettings);
