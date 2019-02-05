(function ($, Drupal) {
  Drupal.behaviors.newsplusFixes = {
    attach: function (context, settings) {
      alert("es");
      $(".flex-direction-nav .flex-next").append("<i class=\"fas fa-arrow-left\"></i>");
      $(".flex-direction-nav .flex-prev").append("<i class=\"fas fa-arrow-right\"></i>");
    }
  };
})(jQuery, Drupal);