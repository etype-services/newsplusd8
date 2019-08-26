(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeloginBehavior = {
        attach: function (context, settings) {
            $("#edit-username").attr("tabindex", "20").focus();
            $("#edit-password").attr("tabindex", "21");

            $("#edit-state").once("etypeloginBehavior").change(function() {
                alert($(this).val());
            });
        }
    };
})(jQuery, Drupal);