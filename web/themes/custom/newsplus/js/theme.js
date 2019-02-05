(function ($, Drupal) {
  Drupal.behaviors.newsplusFixes = {
    attach: function (context, settings) {
      alert("es");
      $(".flex-direction-nav .flex-nav-next a").html("<i class=\"fas fa-arrow-left\"></i>");
      $(".flex-direction-nav .flex-nav-prev a").html("<i class=\"fas fa-arrow-right\"></i>");
    }
  };
})(jQuery, Drupal);