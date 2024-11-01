(function(a,b){function m(a,b,c){var d=h[b.type]||{};return a==null?c||!b.def?null:b.def:(a=d.floor?~~a:parseFloat(a),isNaN(a)?b.def:d.mod?(a+d.mod)%d.mod:0>a?0:d.max<a?d.max:a)}function n(b){var c=f(),d=c._rgba=[];return b=b.toLowerCase(),l(e,function(a,e){var f,h=e.re.exec(b),i=h&&e.parse(h),j=e.space||"rgba";if(i)return f=c[j](i),c[g[j].cache]=f[g[j].cache],d=c._rgba=f._rgba,!1}),d.length?(d.join()==="0,0,0,0"&&a.extend(d,k.transparent),c):k[b]}function o(a,b,c){return c=(c+1)%1,c*6<1?a+(b-a)*c*6:c*2<1?b:c*3<2?a+(b-a)*(2/3-c)*6:a}var c="backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",d=/^([\-+])=\s*(\d+\.?\d*)/,e=[{re:/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,parse:function(a){return[a[1],a[2],a[3],a[4]]}},{re:/rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,parse:function(a){return[a[1]*2.55,a[2]*2.55,a[3]*2.55,a[4]]}},{re:/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,parse:function(a){return[parseInt(a[1],16),parseInt(a[2],16),parseInt(a[3],16)]}},{re:/#([a-f0-9])([a-f0-9])([a-f0-9])/,parse:function(a){return[parseInt(a[1]+a[1],16),parseInt(a[2]+a[2],16),parseInt(a[3]+a[3],16)]}},{re:/hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,space:"hsla",parse:function(a){return[a[1],a[2]/100,a[3]/100,a[4]]}}],f=a.Color=function(b,c,d,e){return new a.Color.fn.parse(b,c,d,e)},g={rgba:{props:{red:{idx:0,type:"byte"},green:{idx:1,type:"byte"},blue:{idx:2,type:"byte"}}},hsla:{props:{hue:{idx:0,type:"degrees"},saturation:{idx:1,type:"percent"},lightness:{idx:2,type:"percent"}}}},h={"byte":{floor:!0,max:255},percent:{max:1},degrees:{mod:360,floor:!0}},i=f.support={},j=a("<p>")[0],k,l=a.each;j.style.cssText="background-color:rgba(1,1,1,.5)",i.rgba=j.style.backgroundColor.indexOf("rgba")>-1,l(g,function(a,b){b.cache="_"+a,b.props.alpha={idx:3,type:"percent",def:1}}),f.fn=a.extend(f.prototype,{parse:function(c,d,e,h){if(c===b)return this._rgba=[null,null,null,null],this;if(c.jquery||c.nodeType)c=a(c).css(d),d=b;var i=this,j=a.type(c),o=this._rgba=[],p;d!==b&&(c=[c,d,e,h],j="array");if(j==="string")return this.parse(n(c)||k._default);if(j==="array")return l(g.rgba.props,function(a,b){o[b.idx]=m(c[b.idx],b)}),this;if(j==="object")return c instanceof f?l(g,function(a,b){c[b.cache]&&(i[b.cache]=c[b.cache].slice())}):l(g,function(b,d){var e=d.cache;l(d.props,function(a,b){if(!i[e]&&d.to){if(a==="alpha"||c[a]==null)return;i[e]=d.to(i._rgba)}i[e][b.idx]=m(c[a],b,!0)}),i[e]&&a.inArray(null,i[e].slice(0,3))<0&&(i[e][3]=1,d.from&&(i._rgba=d.from(i[e])))}),this},is:function(a){var b=f(a),c=!0,d=this;return l(g,function(a,e){var f,g=b[e.cache];return g&&(f=d[e.cache]||e.to&&e.to(d._rgba)||[],l(e.props,function(a,b){if(g[b.idx]!=null)return c=g[b.idx]===f[b.idx],c})),c}),c},_space:function(){var a=[],b=this;return l(g,function(c,d){b[d.cache]&&a.push(c)}),a.pop()},transition:function(a,b){var c=f(a),d=c._space(),e=g[d],i=this.alpha()===0?f("transparent"):this,j=i[e.cache]||e.to(i._rgba),k=j.slice();return c=c[e.cache],l(e.props,function(a,d){var e=d.idx,f=j[e],g=c[e],i=h[d.type]||{};if(g===null)return;f===null?k[e]=g:(i.mod&&(g-f>i.mod/2?f+=i.mod:f-g>i.mod/2&&(f-=i.mod)),k[e]=m((g-f)*b+f,d))}),this[d](k)},blend:function(b){if(this._rgba[3]===1)return this;var c=this._rgba.slice(),d=c.pop(),e=f(b)._rgba;return f(a.map(c,function(a,b){return(1-d)*e[b]+d*a}))},toRgbaString:function(){var b="rgba(",c=a.map(this._rgba,function(a,b){return a==null?b>2?1:0:a});return c[3]===1&&(c.pop(),b="rgb("),b+c.join()+")"},toHslaString:function(){var b="hsla(",c=a.map(this.hsla(),function(a,b){return a==null&&(a=b>2?1:0),b&&b<3&&(a=Math.round(a*100)+"%"),a});return c[3]===1&&(c.pop(),b="hsl("),b+c.join()+")"},toHexString:function(b){var c=this._rgba.slice(),d=c.pop();return b&&c.push(~~(d*255)),"#"+a.map(c,function(a,b){return a=(a||0).toString(16),a.length===1?"0"+a:a}).join("")},toString:function(){return this._rgba[3]===0?"transparent":this.toRgbaString()}}),f.fn.parse.prototype=f.fn,g.hsla.to=function(a){if(a[0]==null||a[1]==null||a[2]==null)return[null,null,null,a[3]];var b=a[0]/255,c=a[1]/255,d=a[2]/255,e=a[3],f=Math.max(b,c,d),g=Math.min(b,c,d),h=f-g,i=f+g,j=i*.5,k,l;return g===f?k=0:b===f?k=60*(c-d)/h+360:c===f?k=60*(d-b)/h+120:k=60*(b-c)/h+240,j===0||j===1?l=j:j<=.5?l=h/i:l=h/(2-i),[Math.round(k)%360,l,j,e==null?1:e]},g.hsla.from=function(a){if(a[0]==null||a[1]==null||a[2]==null)return[null,null,null,a[3]];var b=a[0]/360,c=a[1],d=a[2],e=a[3],f=d<=.5?d*(1+c):d+c-d*c,g=2*d-f,h,i,j;return[Math.round(o(g,f,b+1/3)*255),Math.round(o(g,f,b)*255),Math.round(o(g,f,b-1/3)*255),e]},l(g,function(c,e){var g=e.props,h=e.cache,i=e.to,j=e.from;f.fn[c]=function(c){i&&!this[h]&&(this[h]=i(this._rgba));if(c===b)return this[h].slice();var d,e=a.type(c),k=e==="array"||e==="object"?c:arguments,n=this[h].slice();return l(g,function(a,b){var c=k[e==="object"?a:b.idx];c==null&&(c=n[b.idx]),n[b.idx]=m(c,b)}),j?(d=f(j(n)),d[h]=n,d):f(n)},l(g,function(b,e){if(f.fn[b])return;f.fn[b]=function(f){var g=a.type(f),h=b==="alpha"?this._hsla?"hsla":"rgba":c,i=this[h](),j=i[e.idx],k;return g==="undefined"?j:(g==="function"&&(f=f.call(this,j),g=a.type(f)),f==null&&e.empty?this:(g==="string"&&(k=d.exec(f),k&&(f=j+parseFloat(k[2])*(k[1]==="+"?1:-1))),i[e.idx]=f,this[h](i)))}})}),f.hook=function(b){var c=b.split(" ");l(c,function(b,c){a.cssHooks[c]={set:function(b,d){var e,g,h="";if(a.type(d)!=="string"||(e=n(d))){d=f(e||d);if(!i.rgba&&d._rgba[3]!==1){g=c==="backgroundColor"?b.parentNode:b;while((h===""||h==="transparent")&&g&&g.style)try{h=a.css(g,"backgroundColor"),g=g.parentNode}catch(j){}d=d.blend(h&&h!=="transparent"?h:"_default")}d=d.toRgbaString()}try{b.style[c]=d}catch(d){}}},a.fx.step[c]=function(b){b.colorInit||(b.start=f(b.elem,c),b.end=f(b.end),b.colorInit=!0),a.cssHooks[c].set(b.elem,b.start.transition(b.end,b.pos))}})},f.hook(c),a.cssHooks.borderColor={expand:function(a){var b={};return l(["Top","Right","Bottom","Left"],function(c,d){b["border"+d+"Color"]=a}),b}},k=a.Color.names={aqua:"#00ffff",black:"#000000",blue:"#0000ff",fuchsia:"#ff00ff",gray:"#808080",green:"#008000",lime:"#00ff00",maroon:"#800000",navy:"#000080",olive:"#808000",purple:"#800080",red:"#ff0000",silver:"#c0c0c0",teal:"#008080",white:"#ffffff",yellow:"#ffff00",transparent:[null,null,null,0],_default:"#ffffff"}})(jQuery);


jQuery(document).ready(function() {
  jQuery("body").append("<div id='divSmallBoxes'></div>");
  jQuery("body").append("<div id='divMiniIcons'></div><div id='divbigBoxes'></div>");
  jQuery(".OpenSideBar").pageslide({ direction: "left" });
});

function MetroUnLoading()
{

    $(".divMessageBox").fadeOut(300,function(){
        $(this).remove();
    });

    $(".LoadingBoxContainer").fadeOut(300,function(){
        $(this).remove();
    });
}

var SmallBoxes = 0;
var SmallCount = 0;
var SmallBoxesAnchos = 0;

(function ($) {
    $.smallBox = function (settings,callback)
    {
        var BoxSmall, content;
        settings = $.extend({
            title: "",
            content: "",
            img: undefined,
            icon: undefined,
            sound: false,
            color: undefined,
            timeout: undefined,
            colortime: 1500,
            colors:undefined
        }, settings);


        if(settings.sound===true)
        {
            if(isIE8orlower() == 0)
            {
                var audioElement = document.createElement('audio');

                if (navigator.userAgent.match('Firefox/'))
                    audioElement.setAttribute('src', '../sound/smallbox.ogg');
                else
                    audioElement.setAttribute('src', '../sound/smallbox.mp3');

                $.get();
                audioElement.addEventListener("load", function() {
                audioElement.play();
                }, true);
                audioElement.pause();
                audioElement.play();
            }
        }

        SmallBoxes = SmallBoxes + 1;

        BoxSmall = ""
        var IconSection ="";
        var CurrentIDSmallbox = "smallbox"+SmallBoxes;

        if(settings.icon == undefined)
        {
            IconSection = "<div class='miniIcono'></div>";
        }
        else
        {
            IconSection = "<div class='miniIcono'><img class='miniPic' src='"+ settings.icon +"'></div>";
        }

        if(settings.img == undefined)
        {
            BoxSmall = "<div id='smallbox"+ SmallBoxes +"' class='SmallBox wpa_animated fadeInRight fast'><div class='smallboxinner'><div class='textoFull'><span class='SmallBoxTitle'>"+ settings.title +"</span><p>"+ settings.content +"</p></div>"+ IconSection +"<div class='wpa_clear'></div></div>";
            BoxSmall = BoxSmall+"</div>";
        }
        else
        {
            BoxSmall = "<div id='smallbox"+ SmallBoxes +"' class='SmallBox wpa_animated fadeInRight fast'><div class='smallboxinner'><div class='foto'><img src='"+ settings.img +"'></div><div class='textoFoto'><span class='SmallBoxTitle'>"+ settings.title +"</span><p>"+ settings.content +"</p></div>"+ IconSection +"<div class='wpa_clear'></div></div>";
            BoxSmall = BoxSmall+"</div>";
        }

        if(SmallBoxes == 1)
        {
            $("#divSmallBoxes").append(BoxSmall);
            SmallBoxesAnchos = $("#smallbox"+SmallBoxes).height() + 40;
        }
        else
        {
            var MetroExist = $(".SmallBox").length;
            if(MetroExist==0)
            {
                $("#divSmallBoxes").append(BoxSmall);
                SmallBoxesAnchos = $("#smallbox"+SmallBoxes).height() + 40;
            }
            else
            {
                $("#divSmallBoxes").append(BoxSmall);
                $("#smallbox"+SmallBoxes).css("top", SmallBoxesAnchos );
                SmallBoxesAnchos = SmallBoxesAnchos + $("#smallbox"+ SmallBoxes).height() + 20;

                $(".SmallBox").each(function( index )
                {

                    if(index == 0)
                    {
                        $(this).css("top", 20 );
                        heightPrev = $(this).height() + 40;
                        SmallBoxesAnchos = $(this).height() + 40;
                    }
                    else
                    {
                        $(this).css("top", heightPrev );
                        heightPrev = heightPrev + $(this).height() + 20;
                        SmallBoxesAnchos = SmallBoxesAnchos + $(this).height() + 20;
                    }

                });

            }
        }

        var ThisSmallBox = $("#smallbox"+SmallBoxes);

        if(settings.color == undefined)
        {
            ThisSmallBox.css("background-color", "#004d60" );
        }
        else
        {
            ThisSmallBox.css("background-color", settings.color );
        }


        var ColorTimeInterval;
        if(settings.colors !=undefined && settings.colors.length>0){
            ThisSmallBox.attr("colorcount","0");

            ColorTimeInterval = setInterval(function(){

                var ColorIndex = ThisSmallBox.attr("colorcount");

                ThisSmallBox.animate({
                    backgroundColor: settings.colors[ColorIndex].color,
                });

                if(ColorIndex < settings.colors.length-1){
                    ThisSmallBox.attr("colorcount",((ColorIndex*1)+1));
                }else{
                    ThisSmallBox.attr("colorcount",0);
                }

            },settings.colortime);
        }

        if(settings.timeout != undefined)
        {

            setTimeout(function()
            {
                clearInterval(ColorTimeInterval);
                var ThisHeight = $(this).height() + 20;
                var ID = CurrentIDSmallbox;
                var ThisTop = $("#"+CurrentIDSmallbox).css('top');

                if ($("#"+CurrentIDSmallbox+":hover").length != 0) {
                    $("#"+CurrentIDSmallbox).on("mouseleave",function(){
                        SmallBoxesAnchos = SmallBoxesAnchos - ThisHeight;
                        $("#"+CurrentIDSmallbox).remove();
                        if (typeof callback == "function")
                        {
                            if(callback) callback();
                        }

                        var Primero = 1;
                        var heightPrev = 0;
                        $(".SmallBox").each(function( index )
                        {

                            if(index == 0)
                            {
                                $(this).animate({
                                    top: 20
                                },300);

                                heightPrev = $(this).height() + 40;
                                SmallBoxesAnchos = $(this).height() + 40;
                            }
                            else
                            {
                                $(this).animate({
                                    top: heightPrev
                                },350);

                                heightPrev = heightPrev + $(this).height() + 20;
                                SmallBoxesAnchos = SmallBoxesAnchos + $(this).height() + 20;
                            }

                        });
                    });
                }
                else
                {
                    clearInterval(ColorTimeInterval);
                    SmallBoxesAnchos = SmallBoxesAnchos - ThisHeight;

                    if (typeof callback == "function")
                    {
                        if(callback) callback();
                    }

                    $("#"+CurrentIDSmallbox).removeClass().addClass("SmallBox").animate({
                        opacity: 0
                    },300, function(){

                        $(this).remove();

                         var Primero = 1;
                            var heightPrev = 0;
                            $(".SmallBox").each(function( index )
                            {

                                if(index == 0)
                                {
                                    $(this).animate({
                                        top: 20
                                    },300);

                                    heightPrev = $(this).height() + 40;
                                    SmallBoxesAnchos = $(this).height() + 40;
                                }
                                else
                                {
                                    $(this).animate({
                                        top: heightPrev
                                    });

                                    heightPrev = heightPrev + $(this).height() + 20;
                                    SmallBoxesAnchos = SmallBoxesAnchos + $(this).height() + 20;
                                }

                            });
                    })
                }

            }, settings.timeout);
        }

         $("#smallbox"+SmallBoxes+" .smallboxinner").bind('click', function()
         {
            clearInterval(ColorTimeInterval);
            if (typeof callback == "function")
            {
                if(callback) callback();
            }

            var ThisHeight = $(this).parent().height() + 20;
            var ID = $(this).parent().attr('id');
            var ThisTop = $(this).parent().css('top');

            SmallBoxesAnchos = SmallBoxesAnchos - ThisHeight;

            $(this).parent().removeClass().addClass("SmallBox").animate({
                opacity: 0
            },300,function(){
                $(this).remove();

                var Primero = 1;
                var heightPrev = 0;

                $(".SmallBox").each(function( index )
                {
                    if(index == 0)
                    {
                        $(this).animate({
                            top: 20,
                        },300);
                        heightPrev = $(this).height() + 40;
                        SmallBoxesAnchos = $(this).height() + 40;
                    }
                    else
                    {
                        $(this).animate({
                            top:heightPrev
                        },350);
                        heightPrev = heightPrev + $(this).height() + 20;
                        SmallBoxesAnchos = SmallBoxesAnchos + $(this).height() + 20;
                    }
                });
            })
         });
    }
})(jQuery);

function CloseSide()
{
    $.pageslide.close();
}

/*
 * jQuery pageSlide
 * Version 2.0
 * http://srobbin.com/jquery-pageslide/
 *
 * jQuery Javascript plugin which slides a webpage over to reveal an additional interaction pane.
 *
 * Copyright (c) 2011 Scott Robbin (srobbin.com)
 * Dual licensed under the MIT and GPL licenses.
*/

;(function($){
    var $body, $pageslide;

    $(function(){
        $body = $('body'), $pageslide = $('#pageslide');

        if( $pageslide.length == 0 ) {
             $pageslide = $('<div />').attr( 'id', 'pageslide' )
                                      .css( 'display', 'none' )
                                      .appendTo( $('body') );
        }

        $pageslide.click(function(e) {
            e.stopPropagation();
        });

        $(document).bind('click keyup', function(e) {

            if( e.type == "keyup" && e.keyCode != 27) return;

            if( $pageslide.is( ':visible' ) && !$pageslide.data( 'modal' ) ) {
                $.pageslide.close();
            }
        });
    });

    var _sliding = false,   // Mutex to assist closing only once
        _lastCaller;        // Used to keep track of last element to trigger pageslide

    function _load( url, useIframe ) {
        if ( url.indexOf("#") === 0 ) {
            $(url).clone(true).appendTo( $pageslide.empty() ).show();
        } else {
            if( useIframe ) {
                var iframe = $("<iframe />").attr({
                                                src: url,
                                                frameborder: 0,
                                                hspace: 0
                                            })
                                            .css({
                                                width: "100%",
                                                height: "100%"
                                            });

                $pageslide.html( iframe );
            } else {
                $pageslide.load( url );
            }

            $pageslide.data( 'localEl', false );

        }
    }

    function _start( direction, speed ) {
        var slideWidth = $pageslide.outerWidth( true ),
            bodyAnimateIn = {},
            slideAnimateIn = {};

        if( $pageslide.is(':visible') || _sliding ) return;
        _sliding = true;

        switch( direction ) {
            case 'left':
                $pageslide.css({ left: 'auto', right: '-' + slideWidth + 'px' });
                bodyAnimateIn['margin-left'] = '-=' + slideWidth;
                slideAnimateIn['right'] = '+=' + slideWidth;
                break;
            default:
                $pageslide.css({ left: '-' + slideWidth + 'px', right: 'auto' });
                bodyAnimateIn['margin-left'] = '+=' + slideWidth;
                slideAnimateIn['left'] = '+=' + slideWidth;
                break;
        }

        $body.animate(bodyAnimateIn, speed);
        $pageslide.show()
                  .animate(slideAnimateIn, speed, function() {
                      _sliding = false;
                  });
    }

    $.fn.pageslide = function(options) {
        var $elements = this;

        $elements.click( function(e) {
            var $self = $(this),
                settings = $.extend({ href: $self.attr('href') }, options);

            e.preventDefault();
            e.stopPropagation();

            if ( $pageslide.is(':visible') && $self[0] == _lastCaller ) {
                $.pageslide.close();
            } else {
                $.pageslide( settings );

                _lastCaller = $self[0];
            }
        });
    };

    $.fn.pageslide.defaults = {
        speed:      200,        // Accepts standard jQuery effects speeds (i.e. fast, normal or milliseconds)
        direction:  'right',    // Accepts 'left' or 'right'
        modal:      false,      // If set to true, you must explicitly close pageslide using $.pageslide.close();
        iframe:     true,       // By default, linked pages are loaded into an iframe. Set this to false if you don't want an iframe.
        href:       null        // Override the source of the content. Optional in most cases, but required when opening pageslide programmatically.
    };

    /*
     * Public methods
     */

    $.pageslide = function( options ) {
        var settings = $.extend({}, $.fn.pageslide.defaults, options);

        if( $pageslide.is(':visible') && $pageslide.data( 'direction' ) != settings.direction) {
            $.pageslide.close(function(){
                _load( settings.href, settings.iframe );
                _start( settings.direction, settings.speed );
            });
        } else {
            _load( settings.href, settings.iframe );
            if( $pageslide.is(':hidden') ) {
                _start( settings.direction, settings.speed );
            }
        }

        $pageslide.data( settings );
    }

    $.pageslide.close = function( callback ) {
        var $pageslide = $('#pageslide'),
            slideWidth = $pageslide.outerWidth( true ),
            speed = $pageslide.data( 'speed' ),
            bodyAnimateIn = {},
            slideAnimateIn = {}

        if( $pageslide.is(':hidden') || _sliding ) return;
        _sliding = true;

        switch( $pageslide.data( 'direction' ) ) {
            case 'left':
                bodyAnimateIn['margin-left'] = '+=' + slideWidth;
                slideAnimateIn['right'] = '-=' + slideWidth;
                break;
            default:
                bodyAnimateIn['margin-left'] = '-=' + slideWidth;
                slideAnimateIn['left'] = '-=' + slideWidth;
                break;
        }

        $pageslide.animate(slideAnimateIn, speed);
        $body.animate(bodyAnimateIn, speed, function() {
            $pageslide.hide();
            _sliding = false;
            if( typeof callback != 'undefined' ) callback();
        });
    }
})(jQuery);

function getInternetExplorerVersion() {
    var rv = -1; // Return value assumes failure.
    if (navigator.appName == 'Microsoft Internet Explorer') {
        var ua = navigator.userAgent;
        var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
        if (re.exec(ua) != null)
            rv = parseFloat(RegExp.$1);
    }
    return rv;
}
function checkVersion() {
    var msg = "You're not using Windows Internet Explorer.";
    var ver = getInternetExplorerVersion();
    if (ver > -1) {
        if (ver >= 8.0)
            msg = "You're using a recent copy of Windows Internet Explorer."
        else
            msg = "You should upgrade your copy of Windows Internet Explorer.";
    }
    alert(msg);
}

function isIE8orlower() {
    var msg = "0";
    var ver = getInternetExplorerVersion();
    if (ver > -1) {
        if (ver >= 9.0)
            msg = 0
        else
            msg = 1;
    }
    return msg;
    // alert(msg);
}