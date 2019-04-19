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
                    keys.removeClass("is-really-invisible").focus();
                } else {
                    if (reg.test(keys.val())) {
                        $("#search-block-form").submit();
                    } else {
                        keys.addClass("is-really-invisible");
                    }
                }
            });

            /* User menu buttons. */
            $(".user-menu .button").hover(function () {
                $(this).removeClass("has-background-grey").addClass("has-background-link");
            }, function () {
                $(this).removeClass("has-background-link").addClass("has-background-grey");
            });
        }
    };
})(jQuery, Drupal);