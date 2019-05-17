(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposBehavior = {
        attach: function (context, settings) {
            var caption = $(".page-node-type-feature .is-three-quarters img").attr("alt");
            var w = $("#main-nav > .navbar > .navbar-end").width();

            /* Menu toggle. */
            $(".navbar-burger").once("tiemposBehavior").click(function () {
                $(".navbar-start").toggleClass("is-really-invisible");
            });

            /* Search form. (No ids because the form is repeated.) */
            $(".search-edit-submit").once("tiemposBehavior").click(function () {
                var keys = $('[data-drupal-selector="search-edit-keys"]');
                var reg = /[a-z]+/;
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

            /* Main nav margin*/
            $("#main-navbar-menu").css("margin-left", w);

            /* Add footer menu to user-menu */
            $(".footer-menu li > a").once("tiemposBehavior").clone().addClass("is-hidden-desktop").appendTo($(".user-menu"));

            /* Add arrows to section header. */
            $(".section-tag-heading").once("tiemposBehavior").append("&nbsp;<i class=\"fas fa-angle-right\" aria-hidden=\"true\"></i>");

            /* Add down arrows to dropdowns */
            $(".dropdown-trigger > a").once("tiemposBehavior").append("&nbsp;<i class=\"fas fa-angle-down\" aria-hidden=\"true\"></i>");

            /* Image captions for Feature */
            $(".main-caption").html(caption);
        }
    };
})(jQuery, Drupal);