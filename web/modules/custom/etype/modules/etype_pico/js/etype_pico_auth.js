(function ($, Drupal, drupalSettings) {
    "use strict";
    Drupal.behaviors.picoAuthBehavior = {
        attach: function (context, settings) {
            // console.log(drupalSettings.toknizdUrl);
            window.addEventListener('pico.loaded',function () {
              let theBlock = $("pico");
              let theAttr = theBlock.attr("data-pico-status");
              console.log(theAttr);
              if (theAttr === 'paying') {
                window.location.replace(drupalSettings.toknizdUrl);
              } else {
                window.location.replace("/");
              }
            });
        }
    };
})(jQuery, Drupal, drupalSettings);
