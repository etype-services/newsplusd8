(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypeloginBehavior = {
        attach: function (context, settings) {
            var author = drupalSettings.etype.author;
            $("#edit-uid-0-target-id").val(author);
        }
    };
})(jQuery, Drupal);