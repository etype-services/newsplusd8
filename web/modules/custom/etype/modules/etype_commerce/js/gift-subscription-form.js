(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.etypeGiftSubscription = {
    attach: function (context, settings) {
      $("select").addClass("select");
    }
  };
})(jQuery, Drupal);
