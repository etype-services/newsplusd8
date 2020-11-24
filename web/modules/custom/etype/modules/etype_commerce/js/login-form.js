(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.etypeCommerceLogin = {
    attach: function (context, settings) {
      $("#edit-login-register-mail").addClass("input");
      $("#edit-login-returning-customer .fieldset-legend").html("Log in:");
      $("#edit-login-register .fieldset-legend").html("Create a new account:");
      $("select").addClass("select");
    }
  };
})(jQuery, Drupal);
