(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposBehavior = {
        attach: function (context, settings) {

            /* Menu toggle. */
            $(".navbar-burger").once("tiemposBehavior").click(function () {
                $(".navbar-start").toggleClass("is-really-invisible");
            });

            /* Search form. (No ids because the form is repeated.) */
            $(".search-edit-submit").once("tiemposBehavior").click(function () {
                var keys = $('[data-drupal-selector="search-edit-keys"]');
                var reg = /[a-z]+/;
                // console.log("clicked");
                if (keys.hasClass("is-really-invisible")) {
                    keys.removeClass("is-really-invisible").focus();
                } else {
                    if (reg.test(keys.val())) {
                        $('[data-drupal-selector="search-block-form"]').submit();
                    } else {
                        keys.addClass("is-really-invisible");
                    }
                }
            });
        }
    };
})(jQuery, Drupal);