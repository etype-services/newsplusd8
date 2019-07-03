/*
 * Paywall for eType
 */
var newTotal;

var init = function () {
    "use strict";
    var total = Cookies.get("paywallViewed");
    // console.log(total);
    if (isNaN(total)) {
        newTotal = 1;
    } else {
        newTotal = parseInt(total) + 1;
    }
    // console.log(newTotal);
    Cookies.set("paywallViewed", newTotal, {expires: 30});
};

init();

(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.etypePaywallPageBehavior = {
        attach: function (context, settings) {
            if (newTotal > 4) {
                var subLink = drupalSettings.etype_paywall.etype_paywall.subLink;
                $(".field--name-body").once("etypePaywallPageBehavior").addClass("paywall_blocked").html("You‘ve read all your free articles for this month. Please <a href=\"" + subLink + "\">subscribe</a> to read more.");
            }
        }
    };
})(jQuery, Drupal);