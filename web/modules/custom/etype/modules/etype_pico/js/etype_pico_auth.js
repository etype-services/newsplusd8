(function ($, Drupal, drupalSettings) {
  "use strict";
  Drupal.behaviors.picoAuthBehavior = {
    attach: function (context, settings) {
      // console.log(drupalSettings.toknizdUrl);
      let theBlock = $("#pico");
      document.addEventListener('pico.loaded',function () {
        let theAttr = theBlock.attr("data-pico-status");
        // console.log(theAttr);
        if (theAttr === 'paying') {
          // alert("Pico Loaded - paying customer");
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
      // Fallback, present link.
      setTimeout(function() {
        theBlock.html('<div><a href="' + drupalSettings.toknizdUrl + '">Click here to load the Digital Edition</a></div>');
      }, 10000);
    }
  };
})(jQuery, Drupal, drupalSettings);
