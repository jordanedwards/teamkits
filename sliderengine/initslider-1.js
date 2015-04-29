jQuery(document).ready(function(){

    var scripts = document.getElementsByTagName("script");

    var jsFolder = "";

    for (var i= 0; i< scripts.length; i++)

    {

        if( scripts[i].src && scripts[i].src.match(/initslider-1\.js/i))

            jsFolder = scripts[i].src.substr(0, scripts[i].src.lastIndexOf("/") + 1);

    }

    jQuery("#amazingslider-1").amazingslider({

        jsfolder:jsFolder,

        width:1200,

        height:800,

        skinsfoldername:"",

        loadimageondemand:false,

        watermarktextcss:"font:12px Arial,Tahoma,Helvetica,sans-serif;color:#333;padding:2px 4px;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;background-color:#fff;opacity:0.9;filter:alpha(opacity=90);",

        watermarkstyle:"text",

        enabletouchswipe:true,

        fullscreen:false,

        autoplayvideo:false,

        addmargin:false,

        watermarklinkcss:"text-decoration:none;font:12px Arial,Tahoma,Helvetica,sans-serif;color:#333;",

        watermarktext:"amazingslider.com",

        watermarklink:"http://amazingslider.com?source=watermark",

        randomplay:false,

        isresponsive:true,

        pauseonmouseover:false,

        playvideoonclickthumb:true,

        showwatermark:false,

        slideinterval:5000,

        watermarkpositioncss:"display:block;position:absolute;bottom:4px;right:4px;",

        watermarkimage:"",

        fullwidth:true,

        transitiononfirstslide:false,

        watermarktarget:"_blank",

        scalemode:"fill",

        loop:0,

        autoplay:true,

        navplayvideoimage:"play-32-32-1.png",

        navpreviewheight:60,

        timerheight:2,

        descriptioncssresponsive:"font-size:12px;",

        shownumbering:false,

        skin:"Vertical",

        textautohide:true,

        addgooglefonts:false,

        navshowplaypause:true,

        navshowplayvideo:false,

        navshowplaypausestandalonemarginx:8,

        navshowplaypausestandalonemarginy:8,

        navbuttonradius:0,

        navpreviewposition:"left",

        navmarginy:10,

        lightboxshownavigation:false,

        showshadow:false,

        navfeaturedarrowimagewidth:8,

        navpreviewwidth:120,

        googlefonts:"",

        textpositionmarginright:24,

        bordercolor:"#ffffff",

        lightboxdescriptionbottomcss:"{color:#333; font-size:12px; font-family:Arial,Helvetica,sans-serif; overflow:hidden; text-align:left; margin:4px 0px 0px; padding: 0px;}",

        lightboxthumbwidth:80,

        navthumbnavigationarrowimagewidth:32,

        navthumbtitlehovercss:"text-decoration:underline;",

        texteffectresponsivesize:600,

        arrowwidth:32,

        texteffecteasing:"easeOutCubic",

        texteffect:"slide",

        lightboxthumbheight:60,

        navspacing:10,

        playvideoimage:"playvideo-64-64-1.png",

        ribbonimage:"ribbon_topleft-0.png",

        navwidth:200,

        navheight:150,

        arrowimage:"arrows-32-32-0.png",

        timeropacity:0.6,

        titlecssresponsive:"font-size:12px;",

        navthumbnavigationarrowimage:"carouselarrows-32-32-1.png",

        navshowplaypausestandalone:false,

        texteffect1:"slide",

        navpreviewbordercolor:"#ffffff",

        ribbonposition:"topleft",

        navthumbdescriptioncss:"display:block;position:relative;padding:2px 4px;text-align:left;font:normal 12px Arial,Helvetica,sans-serif;color:#333;",

        lightboxtitlebottomcss:"{color:#333; font-size:14px; font-family:Armata,sans-serif,Arial; overflow:hidden; text-align:left;}",

        navborder:0,

        navthumbtitleheight:18,

        textpositionmargintop:24,

        texteffectdelay:500,

        navswitchonmouseover:false,

        navarrowimage:"navarrows-28-28-0.png",

        arrowtop:0,

        textstyle:"none",

        playvideoimageheight:64,

        navfonthighlightcolor:"#666666",

        showbackgroundimage:false,

        navpreviewborder:4,

        navopacity:0.7,

        shadowcolor:"#aaaaaa",

        navbuttonshowbgimage:true,

        navbuttonbgimage:"navbuttonbgimage-28-28-0.png",

        navthumbnavigationarrowimageheight:32,

        textbgcss:"display:none;",

        navpreviewarrowwidth:8,

        playvideoimagewidth:64,

        navshowpreviewontouch:false,

        bottomshadowimagewidth:110,

        showtimer:false,

        navradius:0,

        navshowpreview:false,

        navpreviewarrowheight:16,

        navmarginx:10,

        navfeaturedarrowimage:"featuredarrow-8-16-1.png",

        showribbon:false,

        navstyle:"thumbnails",

        textpositionmarginleft:24,

        descriptioncss:"display:inline; position:relative; font:12px \"Lucida Sans Unicode\",\"Lucida Grande\",sans-serif,Arial; color:#fff; white-space:nowrap;  background-color:#f7a020; margin-top:10px; padding:10px; ",

        navplaypauseimage:"navplaypause-28-28-0.png",

        backgroundimagetop:0,

        timercolor:"#ffffff",

        numberingformat:"%NUM/%TOTAL ",

        navfontsize:12,

        navhighlightcolor:"#333333",

        texteffectdelay1:1000,

        navimage:"bullet-24-24-5.png",

        texteffectduration1:600,

        navshowplaypausestandaloneautohide:false,

        navbuttoncolor:"#999999",

        navshowarrow:true,

        texteffectslidedirection:"left",

        navshowfeaturedarrow:true,

        lightboxbarheight:64,

        titlecss:"display:inline; position:relative; font:24px Georgia,serif,Arial; color:#f7a020; white-space:nowrap;",

        ribbonimagey:0,

        ribbonimagex:0,

        texteffectslidedistance1:120,

        texteffectresponsive:true,

        navshowplaypausestandaloneposition:"bottomright",

        shadowsize:5,

        lightboxthumbtopmargin:12,

        arrowhideonmouseleave:1000,

        navshowplaypausestandalonewidth:28,

        navfeaturedarrowimageheight:16,

        navshowplaypausestandaloneheight:28,

        backgroundimagewidth:100,

        navcolor:"#999999",

        navthumbtitlewidth:120,

        lightboxthumbbottommargin:8,

        texteffectseparate:true,

        arrowheight:32,

        arrowmargin:8,

        texteffectduration:600,

        bottomshadowimage:"bottomshadow-110-95-4.png",

        border:0,

        lightboxshowdescription:false,

        timerposition:"bottom",

        navfontcolor:"#333333",

        navthumbnavigationstyle:"auto",

        borderradius:0,

        navbuttonhighlightcolor:"#333333",

        textpositionstatic:"bottom",

        navthumbstyle:"imageonly",

        texteffecteasing1:"easeOutCubic",

        textcss:"display:block; padding:8px 16px; text-align:left;",

        navbordercolor:"#ffffff",

        navpreviewarrowimage:"previewarrow-8-16-0.png",

        showbottomshadow:false,

        texteffectslidedistance:30,

        navdirection:"vertical",

        textpositionmarginstatic:0,

        backgroundimage:"1.jpg",

        navposition:"left",

        texteffectslidedirection1:"right",

        arrowstyle:"none",

        textformat:"Yellow title",

        bottomshadowimagetop:95,

        textpositiondynamic:"bottomleft",

        navshowbuttons:false,

        navthumbtitlecss:"display:block;position:relative;padding:2px 4px;text-align:center;font:bold 12px Arial,Helvetica,sans-serif;color:#333;",

        textpositionmarginbottom:24,

        lightboxshowtitle:false,

        slide: {

            duration:1000,

            easing:"easeInOutExpo",

            checked:true,

            effectdirection:1

        },

        transition:"slide",

        isfullscreen:false,

        textformat: {



        }

    });

});