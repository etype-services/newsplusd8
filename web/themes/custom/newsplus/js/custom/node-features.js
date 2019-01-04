  /**
  * Add Javascript - Node features JS
  */
jQuery(document).ready(function($) {
  if (jQuery("#affix").length>0) {

    var affixBottom = $("#footer").outerHeight(true)
    + $("#subfooter").outerHeight(true)
    + $("#main-content").outerHeight(true)
    - $(".block-system-main-block").outerHeight(true),
    affixTop = $("#affix").offset().top;

    //The admin overlay menu height
    var headerHeight = $("#header").outerHeight(true);
    var adminHeight = parseInt($('body').css('paddingTop'));
    var topValue = adminHeight + headerHeight + 15;

    //We select the highest of the 2 adminHeight OR fixedheaderHeight to use
    if (headerHeight > adminHeight) {
      fixedAffixTop = headerHeight;
    } else {
      fixedAffixTop = adminHeight;
    }

    function initializeAffix(topAffix) {
      if ($(".fixed-header-enabled").length>0) {
        affixBottom = affixBottom + headerHeight - fixedAffixTop - adminHeight + 15;
        initAffixTop = topAffix - adminHeight - headerHeight - 15; //The fixedAffixTop is added as padding on the page so we need to remove it from affixTop
      } else {
        affixBottom = affixBottom;
        initAffixTop = topAffix - adminHeight - 15; // The adminHeight is added as padding on the page so we need to remove it from affixTop
      }
      $("#affix").affix({
        offset: {
          top: initAffixTop,
          bottom: affixBottom
        }
      });
    }

    //The internal banner element is rendered after it is ready so initially it does not have height that can calculated
    //Therefore we manually add the height when we know it or we wait a few seconds to when its height is not known
    if (jQuery(".view-mt-internal-banner").length>0) {
      var pageWidth = $("#page>.container").outerWidth();
      if (pageWidth == 1170) {
        affixTop = affixTop + 610;
        initializeAffix(affixTop);
      } else if (pageWidth == 970) {
        affixTop = affixTop + 506;
        initializeAffix(affixTop);
      } else if (pageWidth == 750) {
        affixTop = affixTop + 491;
        initializeAffix(affixTop);
      } else {
        setTimeout(function() {
          affixTop = $("#affix").offset().top;
          initializeAffix(affixTop);
        }, 2000);
      }
    } else {
      initializeAffix(affixTop);
    }

    $("#affix").on("affixed.bs.affix", function () {
      //We set through JS the inline style top position
      if ($(".fixed-header-enabled").length>0) {
        $("#affix").css("top", (headerHeight+adminHeight)+"px");
      } else {
        $("#affix").css("top", (adminHeight)+"px");
      }
    });

  }
});

  /**
  * Add Javascript - Node progress bar
  */
jQuery(document).ready(function($) {

  if ($(".post-progress").length>0){
    var s = $(window).scrollTop() || 0,
    c = $(window).height() || 0,
    d = $(".node-content").outerHeight(true) || 0,
    e = $(".field--type-comment").outerHeight(true) || 0,
    g = $(".node-content").offset().top || 0;

    if (jQuery(".view-mt-internal-banner").length>0) {
      var pageWidth = $("#page>.container").outerWidth();
      if (pageWidth == 1170) {
        g = g+610;
      } else {
        g = g+506;
      }
    }

    var scrollPercent = (s / (d+g-c-e)) * 100;
    scrollPercent = Math.round(scrollPercent);

    if (c >= (d+g-e)) {
      scrollPercent = 100;
    } else if (scrollPercent < 0) {
      scrollPercent = 0;
    } else if (scrollPercent > 100) {
      scrollPercent = 100;
    }

    $(".post-progressbar").css("width", scrollPercent + "%");
    $(".post-progress-value").html(scrollPercent + "%");
  }

  $(window).scroll(function () {
    if ($(".post-progress").length>0){
      var s = $(window).scrollTop() || 0,
      c = $(window).height() || 0,
      d = $(".node-content").outerHeight(true) || 0,
      e = $(".field--type-comment").outerHeight(true) || 0,
      g = $(".node-content").offset().top || 0;

      var scrollPercent = (s / (d+g-c-e)) * 100;
      scrollPercent = Math.round(scrollPercent);

      if (c >= (d+g-e)) {
        scrollPercent = 100;
      }  else if (scrollPercent < 0) {
        scrollPercent = 0;
      } else if (scrollPercent > 100) {
        scrollPercent = 100;
      }

      $(".post-progressbar").css("width", scrollPercent + "%");
      $(".post-progress-value").html(scrollPercent + "%");
    }
  });

});


jQuery(document).ready(function($) {
  if ($("#incfont").length>0 || $("#decfont").length>0 ) {

    (function () {
      $.fn.fontResize = function(options) {

        var self = this;
        var increaseCount = 0;

        options.increaseBtn.on("click", function (e) {
          e.preventDefault();
          self.each(function(index, element){
            curSize= parseInt($(element).css("font-size")) + 1;
            $(element).css("font-size", curSize);
          });
          return false;
        });

        options.decreaseBtn.on("click", function (e) {
          e.preventDefault();
          self.each(function(index, element){
            curSize= parseInt($(element).css("font-size")) - 1;
            $(element).css("font-size", curSize);
          });
          return false;
        });

      }

    })();

    $(".node-content p, .node-content h1, .node-content h2, .node-content h3, .node-content h4, .node-content h5," +
    ".node-content h6, .node-content a, .node-content ul, .node-content ol, .node-content input, .comment .submitted," +
    ".node-content .node-info").fontResize({
      increaseBtn: $("#incfont"),
      decreaseBtn: $("#decfont")
    });
  }
});
