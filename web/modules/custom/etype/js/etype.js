(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeAuthorBehavior = {
        attach: function (context, settings) {
            let author = drupalSettings.etype.author;
            if (author.length > 0) {
                $("#edit-uid-0-target-id").val(author);
            }
        }
    };
})(jQuery, Drupal);