/**
 * @file
 *
 * See http://flexslider.woothemes.com/dynamic-carousel-min-max.html
 */

(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.mtflexsliderBusinessSlider = {
        attach: function (context, settings) {
            $(context).find("#business-directory").once("mtflexsliderBusinessSliderInit").each(function () {

                // store the slider in a local variable
                var $window = $(window),
                    flexslider = { vars:{} };

                // tiny helper function to add breakpoints
                function getGridSize() {
                    return (window.innerWidth < 600) ? 2 : (window.innerWidth < 900) ? 3 : 4;
                }

                $(function() {
                    SyntaxHighlighter.all();
                });

                $(this).flexslider({
                    animation: "slide",
                    slideshowSpeed: 5000,
                    prevText: "",
                    nextText: "",
                    pauseOnAction: false,
                    useCSS: false,
                    controlNav: false,
                    directionNav: false,
                    itemWidth: 210,
                    itemMargin: 5,
                    minItems: getGridSize(), // use function to pull in initial value
                    maxItems: getGridSize() // use function to pull in initial value
                });
                $(this).fadeIn("slow");

                // check grid size on resize event
                $window.resize(function() {
                    var gridSize = getGridSize();
                    flexslider.vars.minItems = gridSize;
                    flexslider.vars.maxItems = gridSize;
                });
            });
        }
    };
})(jQuery, Drupal, drupalSettings);