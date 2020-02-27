(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeSoapLogin = {
        attach: function (context, settings) {
            $(".etype_logged_in").click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "/etype-verify-account",
                })
                .done(function(url) {
                    $(location).prop('href', url);
                })
                .fail(function() {
                    alert("Something went wrong, we are sorry.");
                });
            });
        }
    };
})(jQuery, Drupal);