function getIEVersion() {
    var sAgent = window.navigator.userAgent;
    var Idx = sAgent.indexOf("MSIE");
    // If IE, return version number.
    if (Idx > 0) {
        return parseInt(sAgent.substring(Idx + 5, sAgent.indexOf(".", Idx)));
    } else if (!!navigator.userAgent.match(/Trident\/7\./)) {
        // If IE 11 then look for Updated user agent string.
        return 11;
    } else {
        return 0; // It is not IE
    }
}

(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.newsplusTheme = {
        attach: function (context, settings) {
            var text;
            // Does not add attribute.
            $("#superfish-secondary-menu-toggle, #superfish-main-toggle").attr("aria-label", "Menu Toggle");
            // Hacky workaround for Ponca City News
            text = $("#secondary-menu-userlogout > a").attr("href");
            if (text === "/user/login") {
                $("#secondary-menu-userlogout > a").attr("href", "/etype-login");
            }

            if (getIEVersion() > 0) {
                $("head").once("newsplusTheme").append('<link rel="stylesheet" type="text/css" href="/themes/custom/newsplus/css/components/ie.css">');
            }
        }
    };
})(jQuery, Drupal);