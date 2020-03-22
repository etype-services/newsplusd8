(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.pico = {
        attach: function (context, settings) {
            var pageInfo = {
                article: true,
                post_id: num,
                post_type: 'article',
                url: window.location.href
            };

            window.pico('visit', pageInfo);
        },
    };
})(jQuery, Drupal, drupalSettings);
