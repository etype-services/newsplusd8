(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.pico = {
        attach: function (context, settings) {
            var nid = settings.nid;
            var picoCategories = settings.picoCategories;
            var pageInfo = {
                article: true,
                post_id: nid,
                post_type: 'article',
                taxonomies: {
                //    tags: [...],
                    categories: picoCategories,
                },
                url: window.location.href
            };
            pico('visit', pageInfo);
        },
    };
})(jQuery, Drupal, drupalSettings);
