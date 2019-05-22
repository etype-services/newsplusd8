(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeAddVideo = {
        attach: function (context, settings) {
            $("#edit-field-video-embed-0-value").blur(function (){
                var res = $("#edit-field-video-embed-0-value").val().split("=");
                $("#edit-field-mt-bg-video-youtube-0-value").val(res[0]);
            });
        }
    };
})(jQuery, Drupal);