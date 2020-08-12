(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.picoBehavior = {
        attach: function (context, settings) {
            $('.user-menu a[href="/pico-login"]').addClass('PicoRule PicoSignal');
            $('.user-menu a[href="/pico-subscribe"]').addClass('PicoPlan PicoSignal');
        }
    };
})(jQuery, Drupal);