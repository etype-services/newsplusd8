/*
 * Paywall for eType
 */
let newTotal;

let etype_paywall_init = function () {
    let total = Cookies.get("paywallViewed");
    if (isNaN(total)) {
        newTotal = 1;
    } else {
        newTotal = parseInt(total) + 1;
    }
};

etype_paywall_init();

(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypePaywallPageBehavior = {
        attach: function (context, settings) {
            let expiresNumber = parseInt(drupalSettings.etype_paywall.etype_paywall.expiresNumber);
            let freeNumber = parseInt(drupalSettings.etype_paywall.etype_paywall.freeNumber);
            let subLink = drupalSettings.etype_paywall.etype_paywall.subLink;
            if (newTotal > freeNumber) {
                $(".node__content .field--name-body").once("etypePaywallPageBehavior").addClass("paywall_blocked").html("You‘ve read all your free articles in this " + expiresNumber + " day period. Please <a href=\"" + subLink + "\">subscribe</a> to read more.");
                $("#block-paywallblock").html("<p>You have read all your free articles.</p>");
            } else {
                $("#block-paywallblock").html("<p>You have read " + newTotal + " of " + freeNumber + " free articles available in this " + expiresNumber + " day period.</p>");
            }
            Cookies.set("paywallViewed", newTotal, {expires: expiresNumber});
        }
    };
})(jQuery, Drupal);
