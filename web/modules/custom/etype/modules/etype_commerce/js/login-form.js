(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.etypeCommerce = {
    attach: function (context, settings) {
      function handleSelectChange(event) {
        let sel = event.target;
        let val = sel.options[sel.selectedIndex].text;
        alert(val);
      }
      $("#edit-login-register-mail").addClass("input");
      $("#edit-login-returning-customer .fieldset-legend").html("Log in:");
      $("#edit-login-register .fieldset-legend").html("Create a new account:");
      $("select").addClass("select").once("etypeCommerce").change(function (event) {
        handleSelectChange(event);
      });
    }
  };
})(jQuery, Drupal);
