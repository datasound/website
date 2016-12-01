/******************************************
        -   PREPARE PLACEHOLDER FOR SLIDER  -
    ******************************************/

var setREVStartSize = function() {
    try {
        var e = new Object,
            i = jQuery(window).width(),
            t = 9999,
            r = 0,
            n = 0,
            l = 0,
            f = 0,
            s = 0,
            h = 0;
        e.c = jQuery('#rev_slider_7_1');
        e.responsiveLevels = [1240, 1024, 778, 480];
        e.gridwidth = [800, 640, 480, 480];
        e.gridheight = [720, 720, 480, 360];

        e.sliderLayout = "fullwidth";
        if (e.responsiveLevels && (jQuery.each(e.responsiveLevels, function(e, f) {
                f > i && (t = r = f, l = e), i > f && f > r && (r = f, n = e)
            }), t > r && (l = n)), f = e.gridheight[l] || e.gridheight[0] || e.gridheight, s = e.gridwidth[l] || e.gridwidth[0] || e.gridwidth, h = i / s, h = h > 1 ? 1 : h, f = Math.round(h * f), "fullscreen" == e.sliderLayout) {
            var u = (e.c.width(), jQuery(window).height());
            if (void 0 != e.fullScreenOffsetContainer) {
                var c = e.fullScreenOffsetContainer.split(",");
                if (c) jQuery.each(c, function(e, i) {
                    u = jQuery(i).length > 0 ? u - jQuery(i).outerHeight(!0) : u
                }), e.fullScreenOffset.split("%").length > 1 && void 0 != e.fullScreenOffset && e.fullScreenOffset.length > 0 ? u -= jQuery(window).height() * parseInt(e.fullScreenOffset, 0) / 100 : void 0 != e.fullScreenOffset && e.fullScreenOffset.length > 0 && (u -= parseInt(e.fullScreenOffset, 0))
            }
            f = u
        } else void 0 != e.minHeight && f < e.minHeight && (f = e.minHeight);
        e.c.closest(".rev_slider_wrapper").css({
            height: f
        })

    } catch (d) {
        console.log("Failure at Presize of Slider:" + d)
    }
};


setREVStartSize();

function revslider_showDoubleJqueryError(sliderID) {
    var errorMessage = "Revolution Slider Error: You have some jquery.js library include that comes after the revolution files js include.";
    errorMessage += "<br> This includes make eliminates the revolution slider libraries, and make it not work.";
    errorMessage += "<br><br> To fix it you can:<br>&nbsp;&nbsp;&nbsp; 1. In the Slider Settings -> Troubleshooting set option:  <strong><b>Put JS Includes To Body</b></strong> option to true.";
    errorMessage += "<br>&nbsp;&nbsp;&nbsp; 2. Find the double jquery.js include and remove it.";
    errorMessage = "<span style='font-size:16px;color:#BC0C06;'>" + errorMessage + "</span>";
    jQuery(sliderID).show().html(errorMessage);
}
var tpj = jQuery;

var revapi7;
tpj(document).ready(function() {
    if (tpj("#rev_slider_7_1").revolution == undefined) {
        revslider_showDoubleJqueryError("#rev_slider_7_1");
    } else {
        revapi7 = tpj("#rev_slider_7_1").show().revolution({
            sliderType: "carousel",
            jsFileLocation: "//monalisa.alenastudio.com/real/wp-content/plugins/revslider/public/assets/js/",
            sliderLayout: "fullwidth",
            dottedOverlay: "none",
            delay: 9000,
            navigation: {
                onHoverStop: "off",
            },
            carousel: {
                horizontal_align: "center",
                vertical_align: "center",
                fadeout: "on",
                vary_fade: "on",
                maxVisibleItems: 3,
                infinity: "on",
                space: 0,
                stretch: "off"
            },
            responsiveLevels: [1240, 1024, 778, 480],
            visibilityLevels: [1240, 1024, 778, 480],
            gridwidth: [800, 640, 480, 480],
            gridheight: [720, 720, 480, 360],
            lazyType: "none",
            parallax: {
                type: "scroll",
                origo: "enterpoint",
                speed: 400,
                levels: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 46, 47, 48, 49, 50, 55],
                type: "scroll",
            },
            shadow: 0,
            spinner: "off",
            stopLoop: "on",
            stopAfterLoops: 0,
            stopAtSlide: 1,
            shuffle: "off",
            autoHeight: "off",
            disableProgressBar: "on",
            hideThumbsOnMobile: "off",
            hideSliderAtLimit: 0,
            hideCaptionAtLimit: 0,
            hideAllCaptionAtLilmit: 0,
            debugMode: false,
            fallbacks: {
                simplifyAll: "off",
                nextSlideOnWindowFocus: "off",
                disableFocusListener: false,
            }
        });
    }
}); /*ready*/
