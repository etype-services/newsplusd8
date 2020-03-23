(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.pico = {
        attach: function (context, settings) {
            var nid = drupalSettings.nid;
            var pageInfo = {
                article: true,
                post_id: nid,
                post_type: 'article',
                //taxonomies: {
                //    tags: [...],
                //    categories: [...],
                //},
                url: window.location.href
            };

            window.pico('visit', pageInfo);
        },
    };
})(jQuery, Drupal, drupalSettings);
