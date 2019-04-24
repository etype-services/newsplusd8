(function ($, Drupal) {
    "use strict";
    Drupal.behaviors.tiemposBehavior = {
        attach: function (context, settings) {
            var img = $(".page-node-type-article article .field--name-field-image img");
            var text = img.attr("alt");
            var caption = $(".page-node-type-featured .is-three-quarters img").attr("alt");
            var article_caption = '<p class="caption is-serif is-size-7">' + text + '</p>';

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

            /* Add footer menu to user-menu */
            $(".footer-menu li > a").once("tiemposBehavior").clone().addClass("is-hidden-desktop").appendTo($(".user-menu"));

            /* Add arrows to section header. */
            $(".section-tag-heading").once("tiemposBehavior").append("&nbsp;<i class=\"fas fa-angle-right\"></i>");

            /* Image captions */
            $(".main-caption").html(caption);
            $(".page-node-type-article .field--name-field-image").once("tiemposBehavior").append(article_caption);

        }
    };
})(jQuery, Drupal);