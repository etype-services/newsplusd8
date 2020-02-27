(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeSoapLogin = {
        attach: function (context, settings) {
            $(".etype_logged_in").click(function (e) {
                e.preventDefault();
                var arr = $(this).attr("href").split("/");
                console.log(arr[4]);
            });
        }
    };
})(jQuery, Drupal);