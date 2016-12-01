
var setREVStartSize=function(){
    try{var e=new Object,i=jQuery(window).width(),t=9999,r=0,n=0,l=0,f=0,s=0,h=0;
        e.c = jQuery('#rev_slider_2_1');
        e.responsiveLevels = [1240,1024,778,480];
        e.gridwidth = [1400,1240,778,480];
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

var revapi2;
tpj(document).ready(function() {
    if(tpj("#rev_slider_2_1").revolution == undefined){
        revslider_showDoubleJqueryError("#rev_slider_2_1");
    }else{
        revapi2 = tpj("#rev_slider_2_1").show().revolution({
            sliderType:"standard",
            jsFileLocation:"//monalisa.alenastudio.com/corporate/wp-content/plugins/revslider/public/assets/js/",
            sliderLayout:"fullscreen",
            dottedOverlay:"none",
            delay:9000,
            navigation: {
                keyboardNavigation:"on",
                keyboard_direction: "vertical",
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
                arrows: {
                    style:"zeus",
                    enable:true,
                    hide_onmobile:false,
                    hide_onleave:false,
                    tmp:'<div class="tp-title-wrap">    <div class="tp-arr-imgholder"></div> </div>',
                    left: {
                        h_align:"left",
                        v_align:"center",
                        h_offset:20,
                        v_offset:0
                    },
                    right: {
                        h_align:"right",
                        v_align:"center",
                        h_offset:20,
                        v_offset:0
                    }
                }
                ,
                bullets: {
                    enable:true,
                    hide_onmobile:true,
                    hide_under:1024,
                    style:"uranus",
                    hide_onleave:false,
                    direction:"horizontal",
                    h_align:"center",
                    v_align:"bottom",
                    h_offset:0,
                    v_offset:50,
                    space:5,
                    tmp:'<span class="tp-bullet-inner"></span>'
                }
            },
            responsiveLevels:[1240,1024,778,480],
            visibilityLevels:[1240,1024,778,480],
            gridwidth:[1400,1240,778,480],
            gridheight:[868,768,960,720],
            lazyType:"none",
            shadow:0,
            spinner:"spinner2",
            stopLoop:"off",
            stopAfterLoops:-1,
            stopAtSlide:-1,
            shuffle:"off",
            autoHeight:"off",
            fullScreenAutoWidth:"off",
            fullScreenAlignForce:"off",
            fullScreenOffsetContainer: "",
            fullScreenOffset: "",
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
    }
}); /*ready*/
