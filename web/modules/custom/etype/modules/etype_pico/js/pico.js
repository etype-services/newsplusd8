(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.picoBehavior = {
        attach: function (context, settings) {
            $('.user-menu a[href="/"]').addClass('PicoPlan PicoSignal');
        }
    };
})(jQuery, Drupal);