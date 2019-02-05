(function ($, Drupal) {
  Drupal.behaviors.newsplusFixes = {
    attach: function (context, settings) {
      $(".flex-next").append("<i class=\"fas fa-arrow-left\"></i>");
      $(".flex-prev").append("<i class=\"fas fa-arrow-right\"></i>");
    }
  };
})(jQuery, Drupal);