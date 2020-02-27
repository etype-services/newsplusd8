(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeSoapLogin = {
        attach: function (context, settings) {
            $(".etype_logged_in").click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "/etype-verify-account",
                    data: []
                })
                    .done(function (url) {
                        window.open(url, '_blank');
                    });
            });
        }
    };
})(jQuery, Drupal);