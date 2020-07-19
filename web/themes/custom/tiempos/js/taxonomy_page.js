/* jshint esversion: 6 */
(function ($, Drupal) {
    'use strict'
    Drupal.behaviors.tiemposTaxonomyPage = {
        attach: function (context, settings) {

            let showImages = function () {
                $('.image-container').css({
                    background: 'none',
                })
                $('.image-container img').css({
                    visibility: 'visible',
                })
            }

            let lastWidth = $(window).width()

            let imageFixer = function () {
                $('.view-content').each(function () {
                    let obj = this
                    let height
                    let heights = []
                    let maxHeight
                    $('img', obj).each(function () {
                        height = $(this).height()
                        // console.log('height: ' + height);
                        heights.push(parseInt(height))
                    })
                    // console.log(heights);
                    maxHeight = Math.min.apply(Math, heights)
                    // console.log('maxHeight: ' + maxHeight);
                    if (maxHeight > 0) {
                        $('.image-container img', obj).css({
                            height: maxHeight, visibility: 'visible',
                        })
                    }
                    $('.image-container').css({
                        background: 'none',
                    })
                })
            }
            $('.view-content').imagesLoaded().done(function (instance) {
                // console.log('All images successfully loaded');
                imageFixer()
            })

            /* See https://stackoverflow.com/questions/10750603/detect-a-window-width-change-but-not-a-height-change */
            $(window).resize(function () {
                if ($(window).width() !== lastWidth) {
                    window.location.reload(false)
                    lastWidth = $(window).width()
                }
            })

            // Make sure images show, as sometimes buggy ad scripts seem to prevent the script completing.
            setTimeout(showImages, 1500)
        },
    }
})(jQuery, Drupal)
