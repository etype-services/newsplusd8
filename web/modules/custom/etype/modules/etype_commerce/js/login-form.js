(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.etypeCommerce = {
    attach: function (context, settings) {
      $("#edit-login-register-mail").addClass("input");
      $("#edit-login-returning-customer .fieldset-legend").html("Log in:");
      $("#edit-login-register .fieldset-legend").html("Create a new account:");
      $("select").addClass("select");
      let sel = $('.form-item-purchased-entity-0-attributes-attribute-gift select option:selected').text();
      if (sel === 'Yes') {
        $(".form-item-email").css({
          'display': 'block',
          'visibility': 'visible',
        });
      } else {
        $(".form-item-email").css({
          'display': 'none',
          'visibility': 'hidden',
        });
      }
    }
  };
})(jQuery, Drupal);
