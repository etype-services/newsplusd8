(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mtSliderRevolutionBoxedWidth = {
    attach: function (context, settings) {
      $(context).find('#slider-revolution-main .slider-revolution').once('mtSliderRevolutionBoxedWidthInit').revolution({
        sliderType:"standard",
        sliderLayout:"auto",
        gridwidth: [1140,970,750,450],
        gridheight: drupalSettings.newsplus.sliderRevolutionBoxedInit.sliderRevolutionSlideshowBoxedInitialHeight,
        autoHeight: "on",
        delay: drupalSettings.newsplus.sliderRevolutionBoxedInit.sliderRevolutionSlideshowBoxedEffectTime,
        disableProgressBar:'on',
        responsiveLevels:[1199,991,767,480],
        navigation: {
          onHoverStop:"off",
          arrows:{
            enable:true,
            left:{
              h_align:"right",
              v_align:"top",
              h_offset:40,
              v_offset:0
            },
            right:{
              h_align:"right",
              v_align:"top",
              h_offset:0,
              v_offset:0
            }
          },
          bullets:{
            style:"",
            enable:true,
            direction:"horizontal",
            space:6,
            h_align:"left",
            v_align:"top",
            h_offset:12,
            v_offset:15,
            tmp:"",
          },
          touch:{
            touchenabled:"on",
            swipe_treshold:75,
            swipe_min_touches:1,
            drag_block_vertical:false,
            swipe_direction:"horizontal"
          }
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
