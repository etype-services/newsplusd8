(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.picoArticle = {
        attach: function (context, settings) {
            var nid = settings.nid;
            var picoCategories = settings.picoCategories;
            var pageInfo = {
                article: true,
                post_id: nid,
                post_type: 'article',
                taxonomies: {
                    categories: picoCategories
                },
                url: window.location.href
            };
            window.pico('visit', pageInfo);
        },
    };
})(jQuery, Drupal, drupalSettings);
