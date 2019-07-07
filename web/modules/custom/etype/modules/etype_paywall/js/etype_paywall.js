/*
 * Paywall for eType
 */

var newTotal;

var init = function () {
    "use strict";
    var total = Cookies.get("paywallViewed");
    if (isNaN(total)) {
        newTotal = 1;
    } else {
        newTotal = parseInt(total) + 1;
    }
};

init();

(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypePaywallPageBehavior = {
        attach: function (context, settings) {
            var expiresNumber = parseInt(drupalSettings.etype_paywall.etype_paywall.expiresNumber);
            var freeNumber = parseInt(drupalSettings.etype_paywall.etype_paywall.freeNumber);
            var subLink = drupalSettings.etype_paywall.etype_paywall.subLink;
            if (newTotal > freeNumber) {
                $(".field--name-body").once("etypePaywallPageBehavior").addClass("paywall_blocked").html("You‘ve read all your free articles in this day " + expiresNumber + " day period. Please <a href=\"" + subLink + "\">subscribe</a> to read more.");
                $("#block-paywallblock").html("<p>You have read all your free articles.</p>");
            } else {
                $("#block-paywallblock").html("<p>You have read " + newTotal + " of " + freeNumber + " free articles available in this day " + expiresNumber + " day period.</p>");
            }
            Cookies.set("paywallViewed", newTotal, {expires: expiresNumber});
        }
    };
})(jQuery, Drupal);