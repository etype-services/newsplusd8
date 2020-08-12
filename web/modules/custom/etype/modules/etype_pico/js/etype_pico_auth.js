(function ($, Drupal, drupalSettings) {
    "use strict";
    Drupal.behaviors.picoAuthBehavior = {
        attach: function (context, settings){
            // console.log(window.Pico.user.verified);
            console.log(drupalSettings.toknizdUrl);
            //window.addEventListener('pico.loaded', function() {
                //if (window.Pico.user.verified === true) {
                    window.location.replace(drupalSettings.toknizdUrl);
                //}
            //});
        }
    }
})(jQuery, Drupal, drupalSettings);