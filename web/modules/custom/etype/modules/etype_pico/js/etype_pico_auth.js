(function ($, Drupal, drupalSettings) {
    "use strict";
    Drupal.behaviors.picoAuthBehavior = {
        attach: function (context, settings) {
            // console.log(drupalSettings.toknizdUrl);
            window.addEventListener('pico.loaded',function () {
              let theBlock = $("#pico");
              let theAttr = theBlock.attr("data-pico-status");
              // console.log(theAttr);
              if (theAttr === 'paying') {
                theBlock.html('<a href="' + drupalSettings.toknizdUrl + '">Load the e-Edition</a>');
                window.location.replace(drupalSettings.toknizdUrl);
              } else {
                if (drupalSettings.picoLandingPage.length > 1) {
                  window.location.replace(drupalSettings.picoLandingPage);
                } else {
                  window.location.replace("/?pn=manage_payment");
                }
              }
            });
        }
    };
})(jQuery, Drupal, drupalSettings);
