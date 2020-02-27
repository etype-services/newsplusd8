(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeSoapLogin = {
        attach: function (context, settings) {
            $(".etype_logged_in").click(function (e) {
                e.preventDefault();
                // var arr = $(this).attr("href").split("/");
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