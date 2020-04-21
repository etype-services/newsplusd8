(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.picoBehavior = {
        attach: function (context, settings) {
            $('.user-menu a[href="/"]').addClass('PicoRule PicoSignal');
            $('.user-menu a[data-pico-status="registered"]').addClass('is-really-invisible');
        }
    };
})(jQuery, Drupal);