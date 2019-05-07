(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeloginBehavior = {
        attach: function (context, settings) {
            var author = drupalSettings.etype.author;
            if (author.length > 0) {
                $("#edit-uid-0-target-id").val(author);
            }
        }
    };
})(jQuery, Drupal);