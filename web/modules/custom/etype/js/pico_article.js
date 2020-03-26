(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.picoArticle = {
        attach: function (context, settings) {
            var picoPublisherId = drupalSettings.picoPublisherId;
            console.log(picoPublisherId);
            (function (p, i, c, o) {
                var n = new Event('pico-init');
                i[p] = i[p] ||
                    function () {(i[p].queue = i[p].queue || []).push(arguments)}, i.document.addEventListener(
                    'pico-init', function (e) {
                        var t = i.Pico.getInstance(e, {publisherId: o, picoInit: n}, i)
                        t.handleQueueItems(i[p].queue), i[p] = function () {return t.handleQueueItems([arguments])}
                    }, !1);
                var e = i.document.createElement('script'), t = i.document.getElementsByTagName('script')[0];
                e.async = 1, e.src = c, e.onload = function (e) {
                    return i.Pico.getInstance(e, {publisherId: o, picoInit: n}, i)
                }, t.parentNode.insertBefore(e, t)
            })('pico', window, "https://widget.pico.tools/wrapper.min.js", picoPublisherId);

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
