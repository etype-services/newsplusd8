(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.etypeCommerce = {
    attach: function (context, settings) {
      /* Classes and text changes */
      $("#edit-login-register-mail, .form-email").addClass("input");
      $("#edit-login-returning-customer .fieldset-legend").html("Log in:");
      $("#edit-login-register .fieldset-legend").html("Create a new account:");
      $("select").addClass("select");

      /* Show or hide gift email field */
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
      /* More classes and text changes */
      $("#edit-login-register-field-address-0").removeAttr("open").attr("closed", 'closed');
      $("#edit-login-register-field-address-0 summary").html("Delivery Address (Print Subscriptions Only)").addClass("button");

      /* User name is hidden, set value from mail */
      $("#edit-login-register-register").once('etypeCommerce').click(function (event) {
        let mail = $("#edit-login-register-mail").val();
        $("#edit-login-register-name").val(mail);
      });
    }
  };
})(jQuery, Drupal);
