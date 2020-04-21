(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.picoBehavior = {
        attach: function (context, settings) {
            $('.user-menu a[href="/login"]').addClass('PicoRule PicoSignal');
            $('.user-menu a[href="/subscribe"]').addClass('PicoPlan PicoSignal');
        }
    };
})(jQuery, Drupal);