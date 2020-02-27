(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeSoapLogin = {
        attach: function (context, settings) {
            $(".etype_logged_in").click(function(e) {
                e.preventDefault();
                alert("clicked");
            });
        }
    };
})(jQuery, Drupal);