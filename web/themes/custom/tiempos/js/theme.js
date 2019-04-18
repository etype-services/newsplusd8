(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposBehavior = {
        attach: function (context, settings) {
            /* Menu toggle. */
            $(".navbar-burger").once("tiemposBehavior").click(function () {
                $(".navbar-start").toggleClass("is-really-invisible");
            });

            /* Search form. */
            $("#search-block-form > #edit-submit").once("tiemposBehavior").click(function () {
                var keys = $("#search-block-form > #edit-keys");
                var reg = /[a-z]+/;
                if (keys.hasClass("is-really-invisible")) {
                    keys.removeClass("is-really-invisible");
                } else {
                    if (reg.test(keys.val())) {
                        $("#search-block-form").submit();
                    } else {
                        keys.addClass("is-really-invisible");
                    }
                }
            });

        }
    };
})(jQuery, Drupal);