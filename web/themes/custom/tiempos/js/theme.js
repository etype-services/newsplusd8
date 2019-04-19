(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposBehavior = {
        attach: function () {

            /* Menu toggle. */
            $(".navbar-burger").once("tiemposBehavior").click(function () {
                $(".navbar-start").toggleClass("is-really-invisible");
            });

            /* Search form. */
            $('[data-drupal-selector="search-edit-submit"]').once("tiemposBehavior").click(function () {
                var keys = $('[data-drupal-selector="search-edit-keys"]');
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
            $(".user-menu .button").once("tiemposBehavior").hover(function () {
                $(this).removeClass("has-background-grey").addClass("has-background-link");
            }, function () {
                $(this).removeClass("has-background-link").addClass("has-background-grey");
            });

        }
    };
})(jQuery, Drupal);