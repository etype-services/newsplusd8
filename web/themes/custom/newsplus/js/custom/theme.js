function getIEVersion() {
  let sAgent = window.navigator.userAgent;
  let Idx = sAgent.indexOf("MSIE");
  // If IE, return version number.
  if (Idx > 0) {
    return parseInt(sAgent.substring(Idx + 5, sAgent.indexOf(".", Idx)));
  }
  else if (!!navigator.userAgent.match(/Trident\/7\./)) {
    // If IE 11 then look for Updated user agent string.
    return 11;
  }
  else {
    return 0; // It is not IE
  }
}

(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.newsplusTheme = {
    attach: function (context, settings) {
      let text;
      // Does not add attribute.
      $("#superfish-secondary-menu-toggle, #superfish-main-toggle").attr("aria-label", "Menu Toggle");
      // Hacky workaround for Ponca City News
      text = $("#secondary-menu-userlogout > a").attr("href");
      if (text === "/user/login") {
        $("#secondary-menu-userlogout > a").attr("href", "/etype-login");
      }

      let frontFixer = function () {
        $("#block-views-block-mt-hot-posts-block-1").each(function () {
          let titleHeight;
          let titleHeights = [];
          let titleMaxHeight;
          $("h2", obj).each(function () {
            titleHeight = $(this).height();
            titleHeights.push(parseInt(titleHeight));
          });
          titleMaxHeight = Math.max.apply(Math, titleHeights);
          // console.log(titleHeights);
          $("h2", obj).css({
            height: titleMaxHeight,
          });
        });
      };

      frontFixer();

      $(window).resize(function () {
        $(".front-page-sections h2").css({
          height: "unset",
        });
        frontFixer();
      });


      if (getIEVersion() > 0) {
        $("head").once("newsplusTheme").append('<link rel="stylesheet" type="text/css" href="/themes/custom/newsplus/css/components/ie.css">');
      }
    }
  };
})(jQuery, Drupal);
