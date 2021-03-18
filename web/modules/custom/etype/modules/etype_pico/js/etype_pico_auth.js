(function ($, Drupal, drupalSettings) {
  "use strict";
  Drupal.behaviors.picoAuthBehavior = {
    attach: function (context, settings) {
      // console.log(drupalSettings.toknizdUrl);
      window.addEventListener('pico.loaded',function () {
        // alert("Pico Loaded");
        let theBlock = $("#pico");
        let theAttr = theBlock.attr("data-pico-status");
        // console.log(theAttr);
        if (theAttr === 'paying') {
          // theBlock.html('<a href="' + drupalSettings.toknizdUrl + '">Go to the e-Edition</a>');
          location.replace(drupalSettings.toknizdUrl);
        } else {
          if (drupalSettings.picoLandingPage.length > 1) {
            location.replace(drupalSettings.picoLandingPage);
          } else {
            location.replace("/?pn=manage_payment");
          }
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
