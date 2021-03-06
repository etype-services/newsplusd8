/* jshint esversion: 6 */
function getIEVersion() {
  "use strict";
  let sAgent = window.navigator.userAgent;
  let iDx = sAgent.indexOf("MSIE");
  let version;
  // If IE, return version number.
  if (iDx > 0) {
    version = parseInt(sAgent.substring(iDx + 5, sAgent.indexOf(".", iDx)));
  }
  else if (!!navigator.userAgent.match(/Trident\/7\./)) {
    // If IE 11 then look for Updated user agent string.
    version = '11';
  }
  else {
    version = 0; // It is not IE
  }
  return version;
}

(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.tiemposBehavior = {
    attach: function (context, settings) {
      let caption = $(".page-node-type-feature .is-three-quarters img").attr("alt");

      if (getIEVersion() > 0) {
        $("head").once("tiemposBehavior").append('<link rel="stylesheet" type="text/css" href="/themes/custom/tiempos/css/ie.css">');
      }

      /* Menu toggle. */
      $(".navbar-burger").once("tiemposBehavior").click(function () {
        $("header, .navbar-start").toggleClass("is-really-invisible");
        /* User menu might be in top-bar */
        $(".region-top-bar").toggleClass("z-index-fix");
        $(".region-navigation").toggleClass("border-bottom");
      });

      $(window).resize(function () {
        $("header").removeClass("is-really-invisible");
      });

      /* Search form. (No ids because the form is repeated.) */
      $(".search-edit-submit").once("tiemposBehavior").click(function () {
        let keys = $('[data-drupal-selector="search-edit-keys"]');
        let reg = /[a-z]+/;
        if (keys.hasClass("is-really-invisible")) {
          keys.removeClass("is-really-invisible").focus();
        }
        else {
          if (reg.test(keys.val())) {
            $('[data-drupal-selector="search-block-form"]').submit();
          }
          else {
            keys.addClass("is-really-invisible");
          }
        }
      });

      /* Add footer menu to user-menu */
      $(".footer-menu li > a").once("tiemposBehavior").clone().addClass("is-hidden-desktop").appendTo($(".user-menu"));

      /* Add down arrows to dropdowns */
      $(".dropdown-trigger > a").once("tiemposBehavior").append("&nbsp;<i class=\"fas fa-angle-down\" aria-hidden=\"true\"></i>");

      /* Image captions for Feature */
      $(".main-caption").html(caption);

      /* Add Bulma formatting to forms */
      $(".webform-submission-form .form-select").addClass('select');
      $("input.form-email").addClass('input');
    }
  };
})(jQuery, Drupal);
