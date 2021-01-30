(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeAddVideo = {
        attach: function (context, settings) {
            /* Autofill YouTube ID field */
            $("#edit-field-video-embed-0-value").blur(function () {
                let res = $("#edit-field-video-embed-0-value").val().split("=");
                $("#edit-field-mt-bg-video-youtube-0-value").val(res[1]);
            });
        }
    };
})(jQuery, Drupal);
