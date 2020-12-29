(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.etypeForm = {
    attach: function (context, settings) {
      $("select").addClass("select");
    }
  };
})(jQuery, Drupal);
