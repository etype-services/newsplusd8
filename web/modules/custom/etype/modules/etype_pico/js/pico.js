(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.picoArticle = {
        attach: function (context, settings) {
            var nid = settings.nid;
            var picoCategories = settings.picoCategories;
            var picoOn = settings.picoOn;
            var picoType = settings.picoType;
            alert(picoOn);
            alert(picoType);
            var pageInfo = {
                article: picoOn,
                post_id: nid,
                post_type: picoType,
                taxonomies: {
                    categories: picoCategories
                },
                url: window.location.href
            };
            window.pico('visit', pageInfo);
        },
    };
})(jQuery, Drupal, drupalSettings);
