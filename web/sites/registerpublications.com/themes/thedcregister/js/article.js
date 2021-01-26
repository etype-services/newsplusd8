/* jshint esversion: 6 */
(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.dcRegisterArticle = {
    attach: function (context, settings) {
      $('.flexslider').flexslider({
        animation: "slide"
      });
    }
  };
})(jQuery, Drupal);
