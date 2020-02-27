(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeSoapLogin = {
        attach: function (context, settings) {
            $(".etype_logged_in").click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "/etype-verify-account",
                })
                .done(function(result) {
                    alert( result );
                });
            });
        }
    };
})(jQuery, Drupal);