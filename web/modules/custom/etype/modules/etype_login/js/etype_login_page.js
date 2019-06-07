(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeloginPageBehavior = {
        attach: function (context, settings) {
            function setCookie(name, value) {
                var d = new Date();
                d.setTime(d.getTime() + (60*1000));
                document.cookie = name + "=" + value + ";path='/';expires=" + d.toUTCString();
            }
            $('a[href="/etype-login"]').click(function () {
                setCookie("redirectDestination", window.location.pathname);
            });
        }
    };
})(jQuery, Drupal);