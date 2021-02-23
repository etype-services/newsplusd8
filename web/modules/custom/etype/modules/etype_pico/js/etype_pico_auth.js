(function ($, Drupal, drupalSettings) {
    "use strict";
    Drupal.behaviors.picoAuthBehavior = {
        attach: function (context, settings) {
            // console.log(drupalSettings.toknizdUrl);
            window.addEventListener('pico.loaded',function () {
              let theBlock = $("PicoBlock");
              let theAttr = theBlock.attr("data-pico-status");
              if (theAttr !== 'excluded') {
                window.location.replace(drupalSettings.toknizdUrl);
              }
            });
        }
    };
})(jQuery, Drupal, drupalSettings);
