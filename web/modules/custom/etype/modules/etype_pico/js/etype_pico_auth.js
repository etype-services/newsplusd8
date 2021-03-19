(function ($, Drupal, drupalSettings) {
  "use strict";
  Drupal.behaviors.picoAuthBehavior = {
    attach: function (context, settings) {
      // console.log(drupalSettings.toknizdUrl);
      document.addEventListener('pico.loaded',function () {
        let theBlock = $("#pico");
        let theAttr = theBlock.attr("data-pico-status");
        // console.log(theAttr);
        if (theAttr === 'paying') {
          // alert("Pico Loaded - paying customer");
          // theBlock.html('<a href="' + drupalSettings.toknizdUrl + '">Go to the e-Edition</a>');
          location.replace(drupalSettings.toknizdUrl);
        } else {
          // alert("Pico Loaded - non-paying customer");
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
