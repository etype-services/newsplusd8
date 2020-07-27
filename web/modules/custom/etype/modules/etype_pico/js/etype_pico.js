(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.picoBehavior = {
        attach: function (context, settings) {
            $('.user-menu a[href="/login"]').addClass('PicoRule PicoSignal');
            $('.user-menu a[href="/subscribe"]').addClass('PicoPlan PicoSignal');

            $('a[href="/e-edition"]')once("picoBehavior").on('click', function () {
                console.log($(this).attr('href'));
                console.log(window.Pico.user);
            });
        }
    };
})(jQuery, Drupal);