
        var setREVStartSize=function(){
            try{var e=new Object,i=jQuery(window).width(),t=9999,r=0,n=0,l=0,f=0,s=0,h=0;
                e.c = jQuery('#rev_slider_9_1');
                e.responsiveLevels = [1240,1024,778,480];
                e.gridwidth = [1240,1024,778,480];
                e.gridheight = [868,768,960,720];

                e.sliderLayout = "fullscreen";
                e.fullScreenAutoWidth='off';
                e.fullScreenAlignForce='off';
                e.fullScreenOffsetContainer= '';
                e.fullScreenOffset='';
                if(e.responsiveLevels&&(jQuery.each(e.responsiveLevels,function(e,f){f>i&&(t=r=f,l=e),i>f&&f>r&&(r=f,n=e)}),t>r&&(l=n)),f=e.gridheight[l]||e.gridheight[0]||e.gridheight,s=e.gridwidth[l]||e.gridwidth[0]||e.gridwidth,h=i/s,h=h>1?1:h,f=Math.round(h*f),"fullscreen"==e.sliderLayout){var u=(e.c.width(),jQuery(window).height());if(void 0!=e.fullScreenOffsetContainer){var c=e.fullScreenOffsetContainer.split(",");if (c) jQuery.each(c,function(e,i){u=jQuery(i).length>0?u-jQuery(i).outerHeight(!0):u}),e.fullScreenOffset.split("%").length>1&&void 0!=e.fullScreenOffset&&e.fullScreenOffset.length>0?u-=jQuery(window).height()*parseInt(e.fullScreenOffset,0)/100:void 0!=e.fullScreenOffset&&e.fullScreenOffset.length>0&&(u-=parseInt(e.fullScreenOffset,0))}f=u}else void 0!=e.minHeight&&f<e.minHeight&&(f=e.minHeight);e.c.closest(".rev_slider_wrapper").css({height:f})

            }catch(d){console.log("Failure at Presize of Slider:"+d)}
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
                    var tpj=jQuery;

        var revapi9;
        tpj(document).ready(function() {
            if(tpj("#rev_slider_9_1").revolution == undefined){
                revslider_showDoubleJqueryError("#rev_slider_9_1");
            }else{
                revapi9 = tpj("#rev_slider_9_1").show().revolution({
                    sliderType:"standard",
jsFileLocation:"//monalisa.alenastudio.com/corporate/wp-content/plugins/revslider/public/assets/js/",
                    sliderLayout:"fullscreen",
                    dottedOverlay:"none",
                    delay:9000,
                    navigation: {
                        keyboardNavigation:"off",
                        keyboard_direction: "horizontal",
                        mouseScrollNavigation:"off",
                        onHoverStop:"off",
                        touch:{
                            touchenabled:"on",
                            swipe_threshold: 75,
                            swipe_min_touches: 1,
                            swipe_direction: "horizontal",
                            drag_block_vertical: false
                        }
                        ,
                        bullets: {
                            enable:true,
                            hide_onmobile:true,
                            hide_under:960,
                            style:"zeus",
                            hide_onleave:false,
                            direction:"horizontal",
                            h_align:"left",
                            v_align:"bottom",
                            h_offset:50,
                            v_offset:120,
                            space:5,
                            tmp:'<span class="tp-bullet-image"></span><span class="tp-bullet-imageoverlay"></span><span class="tp-bullet-title">{{title}}</span>'
                        }
                    },
                    responsiveLevels:[1240,1024,778,480],
                    visibilityLevels:[1240,1024,778,480],
                    gridwidth:[1240,1024,778,480],
                    gridheight:[868,768,960,720],
                    lazyType:"none",
                    parallax: {
                        type:"mouse",
                        origo:"slidercenter",
                        speed:2000,
                        levels:[2,3,4,5,6,7,12,16,10,50,47,48,49,50,51,55],
                        type:"mouse",
                        disable_onmobile:"on"
                    },
                    shadow:0,
                    spinner:"off",
                    stopLoop:"on",
                    stopAfterLoops:0,
                    stopAtSlide:1,
                    shuffle:"off",
                    autoHeight:"off",
                    fullScreenAutoWidth:"off",
                    fullScreenAlignForce:"off",
                    fullScreenOffsetContainer: "",
                    fullScreenOffset: "",
                    disableProgressBar:"on",
                    hideThumbsOnMobile:"off",
                    hideSliderAtLimit:0,
                    hideCaptionAtLimit:0,
                    hideAllCaptionAtLilmit:0,
                    debugMode:false,
                    fallbacks: {
                        simplifyAll:"off",
                        nextSlideOnWindowFocus:"off",
                        disableFocusListener:false,
                    }
                });
var newCall = new Object(),
cslide;

newCall.callback = function() {
    var proc = revapi9.revgetparallaxproc(),
        fade = 1+proc,
        scale = 1+(Math.abs(proc)/10);

    punchgs.TweenLite.set(revapi9.find('.slotholder, .rs-background-video-layer'),{opacity:fade,scale:scale});
}
newCall.inmodule = "parallax";
newCall.atposition = "start";

revapi9.bind("revolution.slide.onloaded",function (e) {
    revapi9.revaddcallback(newCall);
});             }
            }); /*ready*/
