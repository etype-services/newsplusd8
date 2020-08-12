(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.picoBehavior = {
        attach: function (context, settings) {
            $('.user-menu a[href="/pico-login"]').parent('li').addClass('PicoRule PicoSignal');
            $('.user-menu a[href="/pico-subscribe"]').parent('li').addClass('PicoPlan PicoSignal');
            $('.user-menu a[href="/pico-login"], .user-menu a[href="/pico-subscribe"]').on('click', function (event){
                event.preventDefault();
            });
        }
    };
})(jQuery, Drupal);