(function ($, Drupal, drupalSettings) {
    "use strict";
    Drupal.behaviors.picoAuthBehavior = {
        attach: function (context, settings){
            // console.log(window.Pico.user.verified);
            alert(drupalSettings.toknizdUrl);
            alert('yes');
            //window.addEventListener('pico.loaded', function() {
                //if (window.Pico.user.verified == true) {
                //    window.location.replace(redirect);
                //}
            //});
        }
    };
})(jQuery, Drupal, drupalSettings);