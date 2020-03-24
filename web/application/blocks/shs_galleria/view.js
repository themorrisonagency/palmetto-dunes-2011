/* Galleria 1.4.2 */
!function(t,e,i,n){var a=e.document,o=t(a),r=t(e),s=Array.prototype,l=1.41,c=!0,h=3e4,u=!1,d=navigator.userAgent.toLowerCase(),p=e.location.hash.replace(/#\//,""),g=e.location.protocol,f=Math,m=function(){},v=function(){return!1},y=function(){var t=3,e=a.createElement("div"),i=e.getElementsByTagName("i");do e.innerHTML="<!--[if gt IE "+ ++t+"]><i></i><![endif]-->";while(i[0]);return t>4?t:a.documentMode||n}(),_=function(){return{html:a.documentElement,body:a.body,head:a.getElementsByTagName("head")[0],title:a.title}},b=e.parent!==e.self,w="data ready thumbnail loadstart loadfinish image play pause progress fullscreen_enter fullscreen_exit idle_enter idle_exit rescale lightbox_open lightbox_close lightbox_image",x=function(){var e=[];return t.each(w.split(" "),function(t,i){e.push(i),/_/.test(i)&&e.push(i.replace(/_/g,""))}),e}(),T=function(e){var i;return"object"!=typeof e?e:(t.each(e,function(n,a){/^[a-z]+_/.test(n)&&(i="",t.each(n.split("_"),function(t,e){i+=t>0?e.substr(0,1).toUpperCase()+e.substr(1):e}),e[i]=a,delete e[n])}),e)},k=function(e){return t.inArray(e,x)>-1?i[e.toUpperCase()]:e},C={youtube:{reg:/https?:\/\/(?:[a-zA_Z]{2,3}.)?(?:youtube\.com\/watch\?)((?:[\w\d\-\_\=]+&amp;(?:amp;)?)*v(?:&lt;[A-Z]+&gt;)?=([0-9a-zA-Z\-\_]+))/i,embed:function(){return"https://www.youtube.com/embed/"+this.id},getUrl:function(){return"https://www.googleapis.com/youtube/v3/videos?id="+this.id+"&part=snippet&key=AIzaSyC08Y7Un4Zg4yjVXoYla14WJj5TzU2X-EQ&callback=?"},get_thumb:function(t){return g+"//img.youtube.com/vi/"+this.id+"/default.jpg"},get_image:function(t){return t.items[0].snippet.thumbnails.maxres?g+"//img.youtube.com/vi/"+this.id+"/maxresdefault.jpg":t.items[0].snippet.thumbnails.high?g+"//img.youtube.com/vi/"+this.id+"/sddefault.jpg":g+"//img.youtube.com/vi/"+this.id+"/hqdefault.jpg"}},vimeo:{reg:/https?:\/\/(?:www\.)?(vimeo\.com)\/(?:hd#)?([0-9]+)/i,embed:function(){return"http://player.vimeo.com/video/"+this.id},getUrl:function(){return g+"//vimeo.com/api/v2/video/"+this.id+".json?callback=?"},get_thumb:function(t){return t[0].thumbnail_medium},get_image:function(t){return t[0].thumbnail_large}},dailymotion:{reg:/https?:\/\/(?:www\.)?(dailymotion\.com)\/video\/([^_]+)/,embed:function(){return g+"//www.dailymotion.com/embed/video/"+this.id},getUrl:function(){return"https://api.dailymotion.com/video/"+this.id+"?fields=thumbnail_240_url,thumbnail_720_url&callback=?"},get_thumb:function(t){return t.thumbnail_240_url},get_image:function(t){return t.thumbnail_720_url}},_inst:[]},I=function(e,i){for(var n=0;n<C._inst.length;n++)if(C._inst[n].id===i&&C._inst[n].type==e)return C._inst[n];this.type=e,this.id=i,this.readys=[],C._inst.push(this);var a=this;t.extend(this,C[e]),t.getJSON(this.getUrl(),function(e){a.data=e,t.each(a.readys,function(t,e){e(a.data)}),a.readys=[]}),this.getMedia=function(t,e,i){i=i||m;var n=this,a=function(i){e(n["get_"+t](i))};try{n.data?a(n.data):n.readys.push(a)}catch(o){i()}}},S=function(t){var e;for(var i in C)if(e=t&&C[i].reg&&t.match(C[i].reg),e&&e.length)return{id:e[2],provider:i};return!1},A={support:function(){var t=_().html;return!b&&(t.requestFullscreen||t.msRequestFullscreen||t.mozRequestFullScreen||t.webkitRequestFullScreen)}(),callback:m,enter:function(t,e,i){this.instance=t,this.callback=e||m,i=i||_().html,i.requestFullscreen?i.requestFullscreen():i.msRequestFullscreen?i.msRequestFullscreen():i.mozRequestFullScreen?i.mozRequestFullScreen():i.webkitRequestFullScreen&&i.webkitRequestFullScreen()},exit:function(t){this.callback=t||m,a.exitFullscreen?a.exitFullscreen():a.msExitFullscreen?a.msExitFullscreen():a.mozCancelFullScreen?a.mozCancelFullScreen():a.webkitCancelFullScreen&&a.webkitCancelFullScreen()},instance:null,listen:function(){if(this.support){var t=function(){if(A.instance){var t=A.instance._fullscreen;a.fullscreen||a.mozFullScreen||a.webkitIsFullScreen||a.msFullscreenElement&&null!==a.msFullscreenElement?t._enter(A.callback):t._exit(A.callback)}};a.addEventListener("fullscreenchange",t,!1),a.addEventListener("MSFullscreenChange",t,!1),a.addEventListener("mozfullscreenchange",t,!1),a.addEventListener("webkitfullscreenchange",t,!1)}}},E=[],D=[],L=!1,$=!1,z=[],P=[],F=function(e){P.push(e),t.each(z,function(t,i){(i._options.theme==e.name||!i._initialized&&!i._options.theme)&&(i.theme=e,i._init.call(i))})},H=function(){return{clearTimer:function(e){t.each(i.get(),function(){this.clearTimer(e)})},addTimer:function(e){t.each(i.get(),function(){this.addTimer(e)})},array:function(t){return s.slice.call(t,0)},create:function(t,e){e=e||"div";var i=a.createElement(e);return i.className=t,i},removeFromArray:function(e,i){return t.each(e,function(t,n){return n==i?(e.splice(t,1),!1):void 0}),e},getScriptPath:function(e){e=e||t("script:last").attr("src");var i=e.split("/");return 1==i.length?"":(i.pop(),i.join("/")+"/")},animate:function(){var n,o,r,s,l,c,h,u=function(t){var i,n="transition WebkitTransition MozTransition OTransition".split(" ");if(e.opera)return!1;for(i=0;n[i];i++)if("undefined"!=typeof t[n[i]])return n[i];return!1}((a.body||a.documentElement).style),d={MozTransition:"transitionend",OTransition:"oTransitionEnd",WebkitTransition:"webkitTransitionEnd",transition:"transitionend"}[u],p={_default:[.25,.1,.25,1],galleria:[.645,.045,.355,1],galleriaIn:[.55,.085,.68,.53],galleriaOut:[.25,.46,.45,.94],ease:[.25,0,.25,1],linear:[.25,.25,.75,.75],"ease-in":[.42,0,1,1],"ease-out":[0,0,.58,1],"ease-in-out":[.42,0,.58,1]},g=function(e,i,n){var a={};n=n||"transition",t.each("webkit moz ms o".split(" "),function(){a["-"+this+"-"+n]=i}),e.css(a)},f=function(t){g(t,"none","transition"),i.WEBKIT&&i.TOUCH&&(g(t,"translate3d(0,0,0)","transform"),t.data("revert")&&(t.css(t.data("revert")),t.data("revert",null)))};return function(a,v,y){return y=t.extend({duration:400,complete:m,stop:!1},y),a=t(a),y.duration?u?(y.stop&&(a.off(d),f(a)),n=!1,t.each(v,function(t,e){h=a.css(t),H.parseValue(h)!=H.parseValue(e)&&(n=!0),a.css(t,h)}),n?(o=[],r=y.easing in p?p[y.easing]:p._default,s=" "+y.duration+"ms cubic-bezier("+r.join(",")+")",void e.setTimeout(function(e,n,a,r){return function(){e.one(n,function(t){return function(){f(t),y.complete.call(t[0])}}(e)),i.WEBKIT&&i.TOUCH&&(l={},c=[0,0,0],t.each(["left","top"],function(t,i){i in a&&(c[t]=H.parseValue(a[i])-H.parseValue(e.css(i))+"px",l[i]=a[i],delete a[i])}),(c[0]||c[1])&&(e.data("revert",l),o.push("-webkit-transform"+r),g(e,"translate3d("+c.join(",")+")","transform"))),t.each(a,function(t,e){o.push(t+r)}),g(e,o.join(",")),e.css(a)}}(a,d,v,s),2)):void e.setTimeout(function(){y.complete.call(a[0])},y.duration)):void a.animate(v,y):(a.css(v),void y.complete.call(a[0]))}}(),removeAlpha:function(t){if(t instanceof jQuery&&(t=t[0]),9>y&&t){var e=t.style,i=t.currentStyle,n=i&&i.filter||e.filter||"";/alpha/.test(n)&&(e.filter=n.replace(/alpha\([^)]*\)/i,""))}},forceStyles:function(e,i){e=t(e),e.attr("style")&&e.data("styles",e.attr("style")).removeAttr("style"),e.css(i)},revertStyles:function(){t.each(H.array(arguments),function(e,i){i=t(i),i.removeAttr("style"),i.attr("style",""),i.data("styles")&&i.attr("style",i.data("styles")).data("styles",null)})},moveOut:function(t){H.forceStyles(t,{position:"absolute",left:-1e4})},moveIn:function(){H.revertStyles.apply(H,H.array(arguments))},hide:function(e,i,n){n=n||m;var a=t(e);e=a[0],a.data("opacity")||a.data("opacity",a.css("opacity"));var o={opacity:0};if(i){var r=9>y&&e?function(){H.removeAlpha(e),e.style.visibility="hidden",n.call(e)}:n;H.animate(e,o,{duration:i,complete:r,stop:!0})}else 9>y&&e?(H.removeAlpha(e),e.style.visibility="hidden"):a.css(o)},show:function(e,i,n){n=n||m;var a=t(e);e=a[0];var o=parseFloat(a.data("opacity"))||1,r={opacity:o};if(i){9>y&&(a.css("opacity",0),e.style.visibility="visible");var s=9>y&&e?function(){1==r.opacity&&H.removeAlpha(e),n.call(e)}:n;H.animate(e,r,{duration:i,complete:s,stop:!0})}else 9>y&&1==r.opacity&&e?(H.removeAlpha(e),e.style.visibility="visible"):a.css(r)},wait:function(n){i._waiters=i._waiters||[],n=t.extend({until:v,success:m,error:function(){i.raise("Could not complete wait function.")},timeout:3e3},n);var a,o,r,s=H.timestamp(),l=function(){return o=H.timestamp(),a=o-s,H.removeFromArray(i._waiters,r),n.until(a)?(n.success(),!1):"number"==typeof n.timeout&&o>=s+n.timeout?(n.error(),!1):void i._waiters.push(r=e.setTimeout(l,10))};i._waiters.push(r=e.setTimeout(l,10))},toggleQuality:function(t,e){7!==y&&8!==y||!t||"IMG"!=t.nodeName.toUpperCase()||("undefined"==typeof e&&(e="nearest-neighbor"===t.style.msInterpolationMode),t.style.msInterpolationMode=e?"bicubic":"nearest-neighbor")},insertStyleTag:function(e,i){if(!i||!t("#"+i).length){var n=a.createElement("style");if(i&&(n.id=i),_().head.appendChild(n),n.styleSheet)n.styleSheet.cssText=e;else{var o=a.createTextNode(e);n.appendChild(o)}}},loadScript:function(e,i){var n=!1,a=t("<script>").attr({src:e,async:!0}).get(0);a.onload=a.onreadystatechange=function(){n||this.readyState&&"loaded"!==this.readyState&&"complete"!==this.readyState||(n=!0,a.onload=a.onreadystatechange=null,"function"==typeof i&&i.call(this,this))},_().head.appendChild(a)},parseValue:function(t){if("number"==typeof t)return t;if("string"==typeof t){var e=t.match(/\-?\d|\./g);return e&&e.constructor===Array?1*e.join(""):0}return 0},timestamp:function(){return(new Date).getTime()},loadCSS:function(e,o,r){var s,l;if(t("link[rel=stylesheet]").each(function(){return new RegExp(e).test(this.href)?(s=this,!1):void 0}),"function"==typeof o&&(r=o,o=n),r=r||m,s)return r.call(s,s),s;if(l=a.styleSheets.length,t("#"+o).length)t("#"+o).attr("href",e),l--;else{s=t("<link>").attr({rel:"stylesheet",href:e,id:o}).get(0);var c=t('link[rel="stylesheet"], style');if(c.length?c.get(0).parentNode.insertBefore(s,c[0]):_().head.appendChild(s),y&&l>=31)return void i.raise("You have reached the browser stylesheet limit (31)",!0)}if("function"==typeof r){var h=t("<s>").attr("id","galleria-loader").hide().appendTo(_().body);H.wait({until:function(){return 1==h.height()},success:function(){h.remove(),r.call(s,s)},error:function(){h.remove(),i.raise("Theme CSS could not load after 20 sec. "+(i.QUIRK?"Your browser is in Quirks Mode, please add a correct doctype.":"Please download the latest theme at http://galleria.io/customer/."),!0)},timeout:5e3})}return s}}}(),O=function(e){var i=".galleria-videoicon{width:60px;height:60px;position:absolute;top:50%;left:50%;z-index:1;margin:-30px 0 0 -30px;cursor:pointer;background:#000;background:rgba(0,0,0,.8);border-radius:3px;-webkit-transition:all 150ms}.galleria-videoicon i{width:0px;height:0px;border-style:solid;border-width:10px 0 10px 16px;display:block;border-color:transparent transparent transparent #ffffff;margin:20px 0 0 22px}.galleria-image:hover .galleria-videoicon{background:#000}";return H.insertStyleTag(i,"galleria-videoicon"),t(H.create("galleria-videoicon")).html("<i></i>").appendTo(e).click(function(){t(this).siblings("img").mouseup()})},M=function(){var e=function(e,i,n,a){var o=this.getOptions("easing"),r=this.getStageWidth(),s={left:r*(e.rewind?-1:1)},l={left:0};n?(s.opacity=0,l.opacity=1):s.opacity=1,t(e.next).css(s),H.animate(e.next,l,{duration:e.speed,complete:function(t){return function(){i(),t.css({left:0})}}(t(e.next).add(e.prev)),queue:!1,easing:o}),a&&(e.rewind=!e.rewind),e.prev&&(s={left:0},l={left:r*(e.rewind?1:-1)},n&&(s.opacity=1,l.opacity=0),t(e.prev).css(s),H.animate(e.prev,l,{duration:e.speed,queue:!1,easing:o,complete:function(){t(this).css("opacity",0)}}))};return{active:!1,init:function(t,e,i){M.effects.hasOwnProperty(t)&&M.effects[t].call(this,e,i)},effects:{fade:function(e,i){t(e.next).css({opacity:0,left:0}),H.animate(e.next,{opacity:1},{duration:e.speed,complete:i}),e.prev&&(t(e.prev).css("opacity",1).show(),H.animate(e.prev,{opacity:0},{duration:e.speed}))},flash:function(e,i){t(e.next).css({opacity:0,left:0}),e.prev?H.animate(e.prev,{opacity:0},{duration:e.speed/2,complete:function(){H.animate(e.next,{opacity:1},{duration:e.speed,complete:i})}}):H.animate(e.next,{opacity:1},{duration:e.speed,complete:i})},pulse:function(e,i){e.prev&&t(e.prev).hide(),t(e.next).css({opacity:0,left:0}).show(),H.animate(e.next,{opacity:1},{duration:e.speed,complete:i})},slide:function(t,i){e.apply(this,H.array(arguments))},fadeslide:function(t,i){e.apply(this,H.array(arguments).concat([!0]))},doorslide:function(t,i){e.apply(this,H.array(arguments).concat([!1,!0]))}}}}();A.listen(),t.event.special["click:fast"]={propagate:!0,add:function(i){var n=function(t){if(t.touches&&t.touches.length){var e=t.touches[0];return{x:e.pageX,y:e.pageY}}},a={touched:!1,touchdown:!1,coords:{x:0,y:0},evObj:{}};t(this).data({clickstate:a,timer:0}).on("touchstart.fast",function(i){e.clearTimeout(t(this).data("timer")),t(this).data("clickstate",{touched:!0,touchdown:!0,coords:n(i.originalEvent),evObj:i})}).on("touchmove.fast",function(e){var i=n(e.originalEvent),a=t(this).data("clickstate"),o=Math.max(Math.abs(a.coords.x-i.x),Math.abs(a.coords.y-i.y));o>6&&t(this).data("clickstate",t.extend(a,{touchdown:!1}))}).on("touchend.fast",function(n){var o=t(this),r=o.data("clickstate");r.touchdown&&i.handler.call(this,n),o.data("timer",e.setTimeout(function(){o.data("clickstate",a)},400))}).on("click.fast",function(e){var n=t(this).data("clickstate");return n.touched?!1:(t(this).data("clickstate",a),void i.handler.call(this,e))})},remove:function(){t(this).off("touchstart.fast touchmove.fast touchend.fast click.fast")}},r.on("orientationchange",function(){t(this).resize()}),i=function(){var s=this;this._options={},this._playing=!1,this._playtime=5e3,this._active=null,this._queue={length:0},this._data=[],this._dom={},this._thumbnails=[],this._layers=[],this._initialized=!1,this._firstrun=!1,this._stageWidth=0,this._stageHeight=0,this._target=n,this._binds=[],this._id=parseInt(1e4*f.random(),10);var l="container stage images image-nav image-nav-left image-nav-right info info-text info-title info-description thumbnails thumbnails-list thumbnails-container thumb-nav-left thumb-nav-right loader counter tooltip",c="current total";t.each(l.split(" "),function(t,e){s._dom[e]=H.create("galleria-"+e)}),t.each(c.split(" "),function(t,e){s._dom[e]=H.create("galleria-"+e,"span")});var h=this._keyboard={keys:{UP:38,DOWN:40,LEFT:37,RIGHT:39,RETURN:13,ESCAPE:27,BACKSPACE:8,SPACE:32},map:{},bound:!1,press:function(t){var e=t.keyCode||t.which;e in h.map&&"function"==typeof h.map[e]&&h.map[e].call(s,t)},attach:function(t){var e,i;for(e in t)t.hasOwnProperty(e)&&(i=e.toUpperCase(),i in h.keys?h.map[h.keys[i]]=t[e]:h.map[i]=t[e]);h.bound||(h.bound=!0,o.on("keydown",h.press))},detach:function(){h.bound=!1,h.map={},o.off("keydown",h.press)}},u=this._controls={0:n,1:n,active:0,swap:function(){u.active=u.active?0:1},getActive:function(){return s._options.swipe?u.slides[s._active]:u[u.active]},getNext:function(){return s._options.swipe?u.slides[s.getNext(s._active)]:u[1-u.active]},slides:[],frames:[],layers:[]},p=this._carousel={next:s.$("thumb-nav-right"),prev:s.$("thumb-nav-left"),width:0,current:0,max:0,hooks:[],update:function(){var e=0,i=0,n=[0];t.each(s._thumbnails,function(a,o){if(o.ready){e+=o.outerWidth||t(o.container).outerWidth(!0);var r=t(o.container).width();e+=r-f.floor(r),n[a+1]=e,i=f.max(i,o.outerHeight||t(o.container).outerHeight(!0))}}),s.$("thumbnails").css({width:e,height:i}),p.max=e,p.hooks=n,p.width=s.$("thumbnails-list").width(),p.setClasses(),s.$("thumbnails-container").toggleClass("galleria-carousel",e>p.width),p.width=s.$("thumbnails-list").width()},bindControls:function(){var t;p.next.on("click:fast",function(e){if(e.preventDefault(),"auto"===s._options.carouselSteps){for(t=p.current;t<p.hooks.length;t++)if(p.hooks[t]-p.hooks[p.current]>p.width){p.set(t-2);break}}else p.set(p.current+s._options.carouselSteps)}),p.prev.on("click:fast",function(e){if(e.preventDefault(),"auto"===s._options.carouselSteps)for(t=p.current;t>=0;t--){if(p.hooks[p.current]-p.hooks[t]>p.width){p.set(t+2);break}if(0===t){p.set(0);break}}else p.set(p.current-s._options.carouselSteps)})},set:function(t){for(t=f.max(t,0);p.hooks[t-1]+p.width>=p.max&&t>=0;)t--;p.current=t,p.animate()},getLast:function(t){return(t||p.current)-1},follow:function(t){if(0===t||t===p.hooks.length-2)return void p.set(t);for(var e=p.current;p.hooks[e]-p.hooks[p.current]<p.width&&e<=p.hooks.length;)e++;t-1<p.current?p.set(t-1):t+2>e&&p.set(t-e+p.current+2)},setClasses:function(){p.prev.toggleClass("disabled",!p.current),p.next.toggleClass("disabled",p.hooks[p.current]+p.width>=p.max)},animate:function(e){p.setClasses();var i=-1*p.hooks[p.current];isNaN(i)||(s.$("thumbnails").css("left",function(){return t(this).css("left")}),H.animate(s.get("thumbnails"),{left:i},{duration:s._options.carouselSpeed,easing:s._options.easing,queue:!1}))}},g=this._tooltip={initialized:!1,open:!1,timer:"tooltip"+s._id,swapTimer:"swap"+s._id,init:function(){g.initialized=!0;var t=".galleria-tooltip{padding:3px 8px;max-width:50%;background:#ffe;color:#000;z-index:3;position:absolute;font-size:11px;line-height:1.3;opacity:0;box-shadow:0 0 2px rgba(0,0,0,.4);-moz-box-shadow:0 0 2px rgba(0,0,0,.4);-webkit-box-shadow:0 0 2px rgba(0,0,0,.4);}";H.insertStyleTag(t,"galleria-tooltip"),s.$("tooltip").css({opacity:.8,visibility:"visible",display:"none"})},move:function(t){var e=s.getMousePosition(t).x,i=s.getMousePosition(t).y,n=s.$("tooltip"),a=e,o=i,r=n.outerHeight(!0)+1,l=n.outerWidth(!0),c=r+15,h=s.$("container").width()-l-2,u=s.$("container").height()-r-2;isNaN(a)||isNaN(o)||(a+=10,o-=r+8,a=f.max(0,f.min(h,a)),o=f.max(0,f.min(u,o)),c>i&&(o=c),n.css({left:a,top:o}))},bind:function(e,n){if(!i.TOUCH){g.initialized||g.init();var a=function(){s.$("container").off("mousemove",g.move),s.clearTimer(g.timer),s.$("tooltip").stop().animate({opacity:0},200,function(){s.$("tooltip").hide(),s.addTimer(g.swapTimer,function(){g.open=!1},1e3)})},o=function(e,i){g.define(e,i),t(e).hover(function(){s.clearTimer(g.swapTimer),s.$("container").off("mousemove",g.move).on("mousemove",g.move).trigger("mousemove"),g.show(e),s.addTimer(g.timer,function(){s.$("tooltip").stop().show().animate({opacity:1}),g.open=!0},g.open?0:500)},a).click(a)};"string"==typeof n?o(e in s._dom?s.get(e):e,n):t.each(e,function(t,e){o(s.get(t),e)})}},show:function(i){i=t(i in s._dom?s.get(i):i);var n=i.data("tt"),a=function(t){e.setTimeout(function(t){return function(){g.move(t)}}(t),10),i.off("mouseup",a)};n="function"==typeof n?n():n,n&&(s.$("tooltip").html(n.replace(/\s/,"&#160;")),i.on("mouseup",a))},define:function(e,i){if("function"!=typeof i){var n=i;i=function(){return n}}e=t(e in s._dom?s.get(e):e).data("tt",i),g.show(e)}},m=this._fullscreen={scrolled:0,crop:n,active:!1,prev:t(),beforeEnter:function(t){t()},beforeExit:function(t){t()},keymap:s._keyboard.map,parseCallback:function(e,i){return M.active?function(){"function"==typeof e&&e.call(s);var n=s._controls.getActive(),a=s._controls.getNext();s._scaleImage(a),s._scaleImage(n),i&&s._options.trueFullscreen&&t(n.container).add(a.container).trigger("transitionend")}:e},enter:function(t){m.beforeEnter(function(){t=m.parseCallback(t,!0),s._options.trueFullscreen&&A.support?(m.active=!0,H.forceStyles(s.get("container"),{width:"100%",height:"100%"}),s.rescale(),i.MAC?i.SAFARI&&/version\/[1-5]/.test(d)?(s.$("stage").css("opacity",0),e.setTimeout(function(){m.scale(),s.$("stage").css("opacity",1)},4)):(s.$("container").css("opacity",0).addClass("fullscreen"),e.setTimeout(function(){m.scale(),s.$("container").css("opacity",1)},50)):s.$("container").addClass("fullscreen"),r.resize(m.scale),A.enter(s,t,s.get("container"))):(m.scrolled=r.scrollTop(),i.TOUCH||e.scrollTo(0,0),m._enter(t))})},_enter:function(o){m.active=!0,b&&(m.iframe=function(){var n,o=a.referrer,r=a.createElement("a"),s=e.location;return r.href=o,r.protocol!=s.protocol||r.hostname!=s.hostname||r.port!=s.port?(i.raise("Parent fullscreen not available. Iframe protocol, domains and ports must match."),!1):(m.pd=e.parent.document,t(m.pd).find("iframe").each(function(){var t=this.contentDocument||this.contentWindow.document;return t===a?(n=this,!1):void 0}),n)}()),H.hide(s.getActiveImage()),b&&m.iframe&&(m.iframe.scrolled=t(e.parent).scrollTop(),e.parent.scrollTo(0,0));var l=s.getData(),c=s._options,h=!s._options.trueFullscreen||!A.support,u={height:"100%",overflow:"hidden",margin:0,padding:0};if(h&&(s.$("container").addClass("fullscreen"),m.prev=s.$("container").prev(),m.prev.length||(m.parent=s.$("container").parent()),s.$("container").appendTo("body"),H.forceStyles(s.get("container"),{position:i.TOUCH?"absolute":"fixed",top:0,left:0,width:"100%",height:"100%",zIndex:1e4}),H.forceStyles(_().html,u),H.forceStyles(_().body,u)),b&&m.iframe&&(H.forceStyles(m.pd.documentElement,u),H.forceStyles(m.pd.body,u),H.forceStyles(m.iframe,t.extend(u,{width:"100%",height:"100%",top:0,left:0,position:"fixed",zIndex:1e4,border:"none"}))),m.keymap=t.extend({},s._keyboard.map),s.attachKeyboard({escape:s.exitFullscreen,right:s.next,left:s.prev}),m.crop=c.imageCrop,c.fullscreenCrop!=n&&(c.imageCrop=c.fullscreenCrop),l&&l.big&&l.image!==l.big){var d=new i.Picture,p=d.isCached(l.big),g=s.getIndex(),f=s._thumbnails[g];s.trigger({type:i.LOADSTART,cached:p,rewind:!1,index:g,imageTarget:s.getActiveImage(),thumbTarget:f,galleriaData:l}),d.load(l.big,function(e){s._scaleImage(e,{complete:function(e){s.trigger({type:i.LOADFINISH,cached:p,index:g,rewind:!1,imageTarget:e.image,thumbTarget:f});var n=s._controls.getActive().image;n&&t(n).width(e.image.width).height(e.image.height).attr("style",t(e.image).attr("style")).attr("src",e.image.src)}})});var v=s.getNext(g),y=new i.Picture,w=s.getData(v);y.preload(s.isFullscreen()&&w.big?w.big:w.image)}s.rescale(function(){s.addTimer(!1,function(){h&&H.show(s.getActiveImage()),"function"==typeof o&&o.call(s),s.rescale()},100),s.trigger(i.FULLSCREEN_ENTER)}),h?r.resize(m.scale):H.show(s.getActiveImage())},scale:function(){s.rescale()},exit:function(t){m.beforeExit(function(){t=m.parseCallback(t),s._options.trueFullscreen&&A.support?A.exit(t):m._exit(t)})},_exit:function(t){m.active=!1;var n=!s._options.trueFullscreen||!A.support,a=s.$("container").removeClass("fullscreen");if(m.parent?m.parent.prepend(a):a.insertAfter(m.prev),n){H.hide(s.getActiveImage()),H.revertStyles(s.get("container"),_().html,_().body),i.TOUCH||e.scrollTo(0,m.scrolled);var o=s._controls.frames[s._controls.active];o&&o.image&&(o.image.src=o.image.src)}b&&m.iframe&&(H.revertStyles(m.pd.documentElement,m.pd.body,m.iframe),m.iframe.scrolled&&e.parent.scrollTo(0,m.iframe.scrolled)),s.detachKeyboard(),s.attachKeyboard(m.keymap),s._options.imageCrop=m.crop;var l=s.getData().big,c=s._controls.getActive().image;!s.getData().iframe&&c&&l&&l==c.src&&e.setTimeout(function(t){return function(){c.src=t}}(s.getData().image),1),s.rescale(function(){s.addTimer(!1,function(){n&&H.show(s.getActiveImage()),"function"==typeof t&&t.call(s),r.trigger("resize")},50),s.trigger(i.FULLSCREEN_EXIT)}),r.off("resize",m.scale)}},v=this._idle={trunk:[],bound:!1,active:!1,add:function(e,n,a,o){if(e&&!i.TOUCH){v.bound||v.addEvent(),e=t(e),"boolean"==typeof a&&(o=a,a={}),a=a||{};var r,s={};for(r in n)n.hasOwnProperty(r)&&(s[r]=e.css(r));e.data("idle",{from:t.extend(s,a),to:n,complete:!0,busy:!1}),o?e.css(n):v.addTimer(),v.trunk.push(e)}},remove:function(e){e=t(e),t.each(v.trunk,function(t,i){i&&i.length&&!i.not(e).length&&(e.css(e.data("idle").from),v.trunk.splice(t,1))}),v.trunk.length||(v.removeEvent(),s.clearTimer(v.timer))},addEvent:function(){v.bound=!0,s.$("container").on("mousemove click",v.showAll),"hover"==s._options.idleMode&&s.$("container").on("mouseleave",v.hide)},removeEvent:function(){v.bound=!1,s.$("container").on("mousemove click",v.showAll),"hover"==s._options.idleMode&&s.$("container").off("mouseleave",v.hide)},addTimer:function(){"hover"!=s._options.idleMode&&s.addTimer("idle",function(){v.hide()},s._options.idleTime)},hide:function(){if(s._options.idleMode&&s.getIndex()!==!1){s.trigger(i.IDLE_ENTER);var e=v.trunk.length;t.each(v.trunk,function(t,i){var n=i.data("idle");n&&(i.data("idle").complete=!1,H.animate(i,n.to,{duration:s._options.idleSpeed,complete:function(){t==e-1&&(v.active=!1)}}))})}},showAll:function(){s.clearTimer("idle"),t.each(v.trunk,function(t,e){v.show(e)})},show:function(e){var n=e.data("idle");v.active&&(n.busy||n.complete)||(n.busy=!0,s.trigger(i.IDLE_EXIT),s.clearTimer("idle"),H.animate(e,n.from,{duration:s._options.idleSpeed/2,complete:function(){v.active=!0,t(e).data("idle").busy=!1,t(e).data("idle").complete=!0}})),v.addTimer()}},w=this._lightbox={width:0,height:0,initialized:!1,active:null,image:null,elems:{},keymap:!1,init:function(){if(!w.initialized){w.initialized=!0;var e="overlay box content shadow title info close prevholder prev nextholder next counter image",n={},a=s._options,o="",r="position:absolute;",l="lightbox-",c={overlay:"position:fixed;display:none;opacity:"+a.overlayOpacity+";filter:alpha(opacity="+100*a.overlayOpacity+");top:0;left:0;width:100%;height:100%;background:"+a.overlayBackground+";z-index:99990",box:"position:fixed;display:none;width:400px;height:400px;top:50%;left:50%;margin-top:-200px;margin-left:-200px;z-index:99991",shadow:r+"background:#000;width:100%;height:100%;",content:r+"background-color:#fff;top:10px;left:10px;right:10px;bottom:10px;overflow:hidden",info:r+"bottom:10px;left:10px;right:10px;color:#444;font:11px/13px arial,sans-serif;height:13px",close:r+"top:10px;right:10px;height:20px;width:20px;background:#fff;text-align:center;cursor:pointer;color:#444;font:16px/22px arial,sans-serif;z-index:99999",image:r+"top:10px;left:10px;right:10px;bottom:30px;overflow:hidden;display:block;",prevholder:r+"width:50%;top:0;bottom:40px;cursor:pointer;",nextholder:r+"width:50%;top:0;bottom:40px;right:-1px;cursor:pointer;",prev:r+"top:50%;margin-top:-20px;height:40px;width:30px;background:#fff;left:20px;display:none;text-align:center;color:#000;font:bold 16px/36px arial,sans-serif",next:r+"top:50%;margin-top:-20px;height:40px;width:30px;background:#fff;right:20px;left:auto;display:none;font:bold 16px/36px arial,sans-serif;text-align:center;color:#000",title:"float:left",counter:"float:right;margin-left:8px;"},h=function(e){return e.hover(function(){t(this).css("color","#bbb")},function(){t(this).css("color","#444")})},u={},d="";d=y>7?9>y?"background:#000;filter:alpha(opacity=0);":"background:rgba(0,0,0,0);":"z-index:99999",c.nextholder+=d,c.prevholder+=d,t.each(c,function(t,e){o+=".galleria-"+l+t+"{"+e+"}"}),o+=".galleria-"+l+"box.iframe .galleria-"+l+"prevholder,.galleria-"+l+"box.iframe .galleria-"+l+"nextholder{width:100px;height:100px;top:50%;margin-top:-70px}",H.insertStyleTag(o,"galleria-lightbox"),t.each(e.split(" "),function(t,e){s.addElement("lightbox-"+e),n[e]=w.elems[e]=s.get("lightbox-"+e)}),w.image=new i.Picture,t.each({box:"shadow content close prevholder nextholder",info:"title counter",content:"info image",prevholder:"prev",nextholder:"next"},function(e,i){var n=[];t.each(i.split(" "),function(t,e){n.push(l+e)}),u[l+e]=n}),s.append(u),t(n.image).append(w.image.container),t(_().body).append(n.overlay,n.box),h(t(n.close).on("click:fast",w.hide).html("&#215;")),t.each(["Prev","Next"],function(e,a){var o=t(n[a.toLowerCase()]).html(/v/.test(a)?"&#8249;&#160;":"&#160;&#8250;"),r=t(n[a.toLowerCase()+"holder"]);return r.on("click:fast",function(){w["show"+a]()}),8>y||i.TOUCH?void o.show():void r.hover(function(){o.show()},function(t){o.stop().fadeOut(200)})}),t(n.overlay).on("click:fast",w.hide),i.IPAD&&(s._options.lightboxTransitionSpeed=0)}},rescale:function(e){var n=f.min(r.width()-40,w.width),a=f.min(r.height()-60,w.height),o=f.min(n/w.width,a/w.height),l=f.round(w.width*o)+40,c=f.round(w.height*o)+60,h={width:l,height:c,"margin-top":-1*f.ceil(c/2),"margin-left":-1*f.ceil(l/2)};e?t(w.elems.box).css(h):t(w.elems.box).animate(h,{duration:s._options.lightboxTransitionSpeed,easing:s._options.easing,complete:function(){var e=w.image,n=s._options.lightboxFadeSpeed;s.trigger({type:i.LIGHTBOX_IMAGE,imageTarget:e.image}),t(e.container).show(),t(e.image).animate({opacity:1},n),H.show(w.elems.info,n)}})},hide:function(){w.image.image=null,r.off("resize",w.rescale),t(w.elems.box).hide().find("iframe").remove(),H.hide(w.elems.info),s.detachKeyboard(),s.attachKeyboard(w.keymap),w.keymap=!1,H.hide(w.elems.overlay,200,function(){t(this).hide().css("opacity",s._options.overlayOpacity),s.trigger(i.LIGHTBOX_CLOSE)})},showNext:function(){w.show(s.getNext(w.active))},showPrev:function(){w.show(s.getPrev(w.active))},show:function(n){w.active=n="number"==typeof n?n:s.getIndex()||0,w.initialized||w.init(),s.trigger(i.LIGHTBOX_OPEN),w.keymap||(w.keymap=t.extend({},s._keyboard.map),s.attachKeyboard({escape:w.hide,right:w.showNext,left:w.showPrev})),r.off("resize",w.rescale);var a,o,l,c=s.getData(n),h=s.getDataLength(),u=s.getNext(n);H.hide(w.elems.info);try{for(l=s._options.preload;l>0;l--)o=new i.Picture,a=s.getData(u),o.preload(a.big?a.big:a.image),u=s.getNext(u)}catch(d){}w.image.isIframe=c.iframe&&!c.image,t(w.elems.box).toggleClass("iframe",w.image.isIframe),t(w.image.container).find(".galleria-videoicon").remove(),w.image.load(c.big||c.image||c.iframe,function(i){if(i.isIframe){var a=t(e).width(),o=t(e).height();if(i.video&&s._options.maxVideoSize){var l=f.min(s._options.maxVideoSize/a,s._options.maxVideoSize/o);1>l&&(a*=l,o*=l)}w.width=a,w.height=o}else w.width=i.original.width,w.height=i.original.height;if(t(i.image).css({width:i.isIframe?"100%":"100.1%",height:i.isIframe?"100%":"100.1%",top:0,bottom:0,zIndex:99998,opacity:0,visibility:"visible"}).parent().height("100%"),w.elems.title.innerHTML=c.title||"",w.elems.counter.innerHTML=n+1+" / "+h,r.resize(w.rescale),w.rescale(),c.image&&c.iframe){if(t(w.elems.box).addClass("iframe"),c.video){var u=O(i.container).hide();e.setTimeout(function(){u.fadeIn(200)},200)}t(i.image).css("cursor","pointer").mouseup(function(e,i){return function(n){t(w.image.container).find(".galleria-videoicon").remove(),n.preventDefault(),i.isIframe=!0,i.load(e.iframe+(e.video?"&autoplay=1":""),{width:"100%",height:8>y?t(w.image.container).height():"100%"})}}(c,i))}}),t(w.elems.overlay).show().css("visibility","visible"),t(w.elems.box).show()}},x=this._timer={trunk:{},add:function(t,i,n,a){if(t=t||(new Date).getTime(),a=a||!1,this.clear(t),a){var o=i;i=function(){o(),x.add(t,i,n)}}this.trunk[t]=e.setTimeout(i,n)},clear:function(t){var i,n=function(t){e.clearTimeout(this.trunk[t]),delete this.trunk[t]};if(t&&t in this.trunk)n.call(this,t);else if("undefined"==typeof t)for(i in this.trunk)this.trunk.hasOwnProperty(i)&&n.call(this,i)}};return this},i.prototype={constructor:i,init:function(e,a){if(a=T(a),this._original={target:e,options:a,data:null},this._target=this._dom.target=e.nodeName?e:t(e).get(0),this._original.html=this._target.innerHTML,D.push(this),!this._target)return void i.raise("Target not found",!0);if(this._options={autoplay:!1,carousel:!0,carouselFollow:!0,carouselSpeed:400,carouselSteps:"auto",clicknext:!1,dailymotion:{foreground:"%23EEEEEE",highlight:"%235BCEC5",background:"%23222222",logo:0,hideInfos:1},dataConfig:function(t){return{}},dataSelector:"img",dataSort:!1,dataSource:this._target,debug:n,dummy:n,easing:"galleria",extend:function(t){},fullscreenCrop:n,fullscreenDoubleTap:!0,fullscreenTransition:n,height:0,idleMode:!0,idleTime:3e3,idleSpeed:200,imageCrop:!1,imageMargin:0,imagePan:!1,imagePanSmoothness:12,imagePosition:"50%",imageTimeout:n,initialTransition:n,keepSource:!1,layerFollow:!0,lightbox:!1,lightboxFadeSpeed:200,lightboxTransitionSpeed:200,linkSourceImages:!0,maxScaleRatio:n,maxVideoSize:n,minScaleRatio:n,overlayOpacity:.85,overlayBackground:"#0b0b0b",pauseOnInteraction:!0,popupLinks:!1,preload:2,queue:!0,responsive:!0,show:0,showInfo:!0,showCounter:!0,showImagenav:!0,swipe:"auto",theme:null,thumbCrop:!0,thumbEventType:"click:fast",thumbMargin:0,thumbQuality:"auto",thumbDisplayOrder:!0,thumbPosition:"50%",thumbnails:!0,touchTransition:n,transition:"fade",transitionInitial:n,
transitionSpeed:400,trueFullscreen:!0,useCanvas:!1,variation:"",videoPoster:!0,vimeo:{title:0,byline:0,portrait:0,color:"aaaaaa"},wait:5e3,width:"auto",youtube:{modestbranding:1,autohide:1,color:"white",hd:1,rel:0,showinfo:0}},this._options.initialTransition=this._options.initialTransition||this._options.transitionInitial,a&&(a.debug===!1&&(c=!1),"number"==typeof a.imageTimeout&&(h=a.imageTimeout),"string"==typeof a.dummy&&(u=a.dummy),"string"==typeof a.theme&&(this._options.theme=a.theme)),t(this._target).children().hide(),i.QUIRK&&i.raise("Your page is in Quirks mode, Galleria may not render correctly. Please validate your HTML and add a correct doctype."),P.length)if(this._options.theme){for(var o=0;o<P.length;o++)if(this._options.theme===P[o].name){this.theme=P[o];break}}else this.theme=P[0];return"object"==typeof this.theme?this._init():z.push(this),this},_init:function(){var o=this,s=this._options;if(this._initialized)return i.raise("Init failed: Gallery instance already initialized."),this;if(this._initialized=!0,!this.theme)return i.raise("Init failed: No theme found.",!0),this;if(t.extend(!0,s,this.theme.defaults,this._original.options,i.configure.options),s.swipe=function(t){return"enforced"==t?!0:t===!1||"disabled"==t?!1:!!i.TOUCH}(s.swipe),s.swipe&&(s.clicknext=!1,s.imagePan=!1),function(t){return"getContext"in t?void($=$||{elem:t,context:t.getContext("2d"),cache:{},length:0}):void(t=null)}(a.createElement("canvas")),this.bind(i.DATA,function(){e.screen&&e.screen.width&&Array.prototype.forEach&&this._data.forEach(function(t){var i="devicePixelRatio"in e?e.devicePixelRatio:1,n=f.max(e.screen.width,e.screen.height);1024>n*i&&(t.big=t.image)}),this._original.data=this._data,this.get("total").innerHTML=this.getDataLength();var t=this.$("container");o._options.height<2&&(o._userRatio=o._ratio=o._options.height);var n={width:0,height:0},a=function(){return o.$("stage").height()};H.wait({until:function(){return n=o._getWH(),t.width(n.width).height(n.height),a()&&n.width&&n.height>50},success:function(){o._width=n.width,o._height=n.height,o._ratio=o._ratio||n.height/n.width,i.WEBKIT?e.setTimeout(function(){o._run()},1):o._run()},error:function(){a()?i.raise("Could not extract sufficient width/height of the gallery container. Traced measures: width:"+n.width+"px, height: "+n.height+"px.",!0):i.raise("Could not extract a stage height from the CSS. Traced height: "+a()+"px.",!0)},timeout:"number"==typeof this._options.wait?this._options.wait:!1})}),this.append({"info-text":["info-title","info-description"],info:["info-text"],"image-nav":["image-nav-right","image-nav-left"],stage:["images","loader","counter","image-nav"],"thumbnails-list":["thumbnails"],"thumbnails-container":["thumb-nav-left","thumbnails-list","thumb-nav-right"],container:["stage","thumbnails-container","info","tooltip"]}),H.hide(this.$("counter").append(this.get("current"),a.createTextNode(" / "),this.get("total"))),this.setCounter("&#8211;"),H.hide(o.get("tooltip")),this.$("container").addClass([i.TOUCH?"touch":"notouch",this._options.variation,"galleria-theme-"+this.theme.name].join(" ")),this._options.swipe||t.each(new Array(2),function(e){var n=new i.Picture;t(n.container).css({position:"absolute",top:0,left:0}).prepend(o._layers[e]=t(H.create("galleria-layer")).css({position:"absolute",top:0,left:0,right:0,bottom:0,zIndex:2})[0]),o.$("images").append(n.container),o._controls[e]=n;var a=new i.Picture;a.isIframe=!0,t(a.container).attr("class","galleria-frame").css({position:"absolute",top:0,left:0,zIndex:4,background:"#000",display:"none"}).appendTo(n.container),o._controls.frames[e]=a}),this.$("images").css({position:"relative",top:0,left:0,width:"100%",height:"100%"}),s.swipe&&(this.$("images").css({position:"absolute",top:0,left:0,width:0,height:"100%"}),this.finger=new i.Finger(this.get("stage"),{onchange:function(t){o.pause().show(t)},oncomplete:function(e){var i=f.max(0,f.min(parseInt(e,10),o.getDataLength()-1)),n=o.getData(i);t(o._thumbnails[i].container).addClass("active").siblings(".active").removeClass("active"),n&&(o.$("images").find(".galleria-frame").css("opacity",0).hide().find("iframe").remove(),o._options.carousel&&o._options.carouselFollow&&o._carousel.follow(i))}}),this.bind(i.RESCALE,function(){this.finger.setup()}),this.$("stage").on("click",function(i){var a=o.getData();if(a){if(a.iframe){o.isPlaying()&&o.pause();var r=o._controls.frames[o._active],s=o._stageWidth,l=o._stageHeight;if(t(r.container).find("iframe").length)return;return t(r.container).css({width:s,height:l,opacity:0}).show().animate({opacity:1},200),void e.setTimeout(function(){r.load(a.iframe+(a.video?"&autoplay=1":""),{width:s,height:l},function(t){o.$("container").addClass("videoplay"),t.scale({width:o._stageWidth,height:o._stageHeight,iframelimit:a.video?o._options.maxVideoSize:n})})},100)}if(a.link)if(o._options.popupLinks){e.open(a.link,"_blank")}else e.location.href=a.link;else;}}),this.bind(i.IMAGE,function(e){o.setCounter(e.index),o.setInfo(e.index);var i=this.getNext(),n=this.getPrev(),a=[n,i];a.push(this.getNext(i),this.getPrev(n),o._controls.slides.length-1);var r=[];t.each(a,function(e,i){-1==t.inArray(i,r)&&r.push(i)}),t.each(r,function(e,i){var n=o.getData(i),a=o._controls.slides[i],r=o.isFullscreen()&&n.big?n.big:n.image||n.iframe;n.iframe&&!n.image&&(a.isIframe=!0),a.ready||o._controls.slides[i].load(r,function(e){e.isIframe||t(e.image).css("visibility","hidden"),o._scaleImage(e,{complete:function(e){e.isIframe||t(e.image).css({opacity:0,visibility:"visible"}).animate({opacity:1},200)}})})})})),this.$("thumbnails, thumbnails-list").css({overflow:"hidden",position:"relative"}),this.$("image-nav-right, image-nav-left").on("click:fast",function(t){s.pauseOnInteraction&&o.pause();var e=/right/.test(this.className)?"next":"prev";o[e]()}).on("click",function(t){t.preventDefault(),(s.clicknext||s.swipe)&&t.stopPropagation()}),t.each(["info","counter","image-nav"],function(t,e){s["show"+e.substr(0,1).toUpperCase()+e.substr(1).replace(/-/,"")]===!1&&H.moveOut(o.get(e.toLowerCase()))}),this.load(),s.keepSource||y||(this._target.innerHTML=""),this.get("errors")&&this.appendChild("target","errors"),this.appendChild("target","container"),s.carousel){var l=0,c=s.show;this.bind(i.THUMBNAIL,function(){this.updateCarousel(),++l==this.getDataLength()&&"number"==typeof c&&c>0&&this._carousel.follow(c)})}return s.responsive&&r.on("resize",function(){o.isFullscreen()||o.resize()}),s.fullscreenDoubleTap&&this.$("stage").on("touchstart",function(){var t,e,i,n,a,r,s=function(t){return t.originalEvent.touches?t.originalEvent.touches[0]:t};return o.$("stage").on("touchmove",function(){t=0}),function(l){if(!/(-left|-right)/.test(l.target.className)){if(r=H.timestamp(),e=s(l).pageX,i=s(l).pageY,l.originalEvent.touches.length<2&&300>r-t&&20>e-n&&20>i-a)return o.toggleFullscreen(),void l.preventDefault();t=r,n=e,a=i}}}()),t.each(i.on.binds,function(e,i){-1==t.inArray(i.hash,o._binds)&&o.bind(i.type,i.callback)}),this},addTimer:function(){return this._timer.add.apply(this._timer,H.array(arguments)),this},clearTimer:function(){return this._timer.clear.apply(this._timer,H.array(arguments)),this},_getWH:function(){var e,i=this.$("container"),n=this.$("target"),a=this,o={};return t.each(["width","height"],function(t,r){a._options[r]&&"number"==typeof a._options[r]?o[r]=a._options[r]:(e=[H.parseValue(i.css(r)),H.parseValue(n.css(r)),i[r](),n[r]()],a["_"+r]||e.splice(e.length,H.parseValue(i.css("min-"+r)),H.parseValue(n.css("min-"+r))),o[r]=f.max.apply(f,e))}),a._userRatio&&(o.height=o.width*a._userRatio),o},_createThumbnails:function(n){this.get("total").innerHTML=this.getDataLength();var o,r,s,l,c=this,h=this._options,u=n?this._data.length-n.length:0,d=u,p=[],g=0,f=8>y?"http://upload.wikimedia.org/wikipedia/commons/c/c0/Blank.gif":"data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw%3D%3D",m=function(){var t=c.$("thumbnails").find(".active");return t.length?t.find("img").attr("src"):!1}(),v="string"==typeof h.thumbnails?h.thumbnails.toLowerCase():null,_=function(t){return a.defaultView&&a.defaultView.getComputedStyle?a.defaultView.getComputedStyle(r.container,null)[t]:l.css(t)},b=function(e,n,a){return function(){t(a).append(e),c.trigger({type:i.THUMBNAIL,thumbTarget:e,index:n,galleriaData:c.getData(n)})}},w=function(e){h.pauseOnInteraction&&c.pause();var i=t(e.currentTarget).data("index");c.getIndex()!==i&&c.show(i),e.preventDefault()},x=function(e,n){t(e.container).css("visibility","visible"),c.trigger({type:i.THUMBNAIL,thumbTarget:e.image,index:e.data.order,galleriaData:c.getData(e.data.order)}),"function"==typeof n&&n.call(c,e)},T=function(e,i){e.scale({width:e.data.width,height:e.data.height,crop:h.thumbCrop,margin:h.thumbMargin,canvas:h.useCanvas,position:h.thumbPosition,complete:function(e){var n,a,o=["left","top"],r=["Width","Height"];c.getData(e.index);t.each(r,function(i,r){n=r.toLowerCase(),(h.thumbCrop!==!0||h.thumbCrop===n)&&(a={},a[n]=e[n],t(e.container).css(a),a={},a[o[i]]=0,t(e.image).css(a)),e["outer"+r]=t(e.container)["outer"+r](!0)}),H.toggleQuality(e.image,h.thumbQuality===!0||"auto"===h.thumbQuality&&e.original.width<3*e.width),h.thumbDisplayOrder&&!e.lazy?t.each(p,function(t,e){return t===g&&e.ready&&!e.displayed?(g++,e.displayed=!0,void x(e,i)):void 0}):x(e,i)}})};for(n||(this._thumbnails=[],this.$("thumbnails").empty());this._data[u];u++)s=this._data[u],o=s.thumb||s.image,h.thumbnails!==!0&&"lazy"!=v||!s.thumb&&!s.image?s.iframe&&null!==v||"empty"===v||"numbers"===v?(r={container:H.create("galleria-image"),image:H.create("img","span"),ready:!0,data:{order:u}},"numbers"===v&&t(r.image).text(u+1),s.iframe&&t(r.image).addClass("iframe"),this.$("thumbnails").append(r.container),e.setTimeout(b(r.image,u,r.container),50+20*u)):r={container:null,image:null}:(r=new i.Picture(u),r.index=u,r.displayed=!1,r.lazy=!1,r.video=!1,this.$("thumbnails").append(r.container),l=t(r.container),l.css("visibility","hidden"),r.data={width:H.parseValue(_("width")),height:H.parseValue(_("height")),order:u,src:o},h.thumbCrop!==!0?l.css({width:"auto",height:"auto"}):l.css({width:r.data.width,height:r.data.height}),"lazy"==v?(l.addClass("lazy"),r.lazy=!0,r.load(f,{height:r.data.height,width:r.data.width})):r.load(o,T),"all"===h.preload&&r.preload(s.image)),t(r.container).add(h.keepSource&&h.linkSourceImages?s.original:null).data("index",u).on(h.thumbEventType,w).data("thumbload",T),m===o&&t(r.container).addClass("active"),this._thumbnails.push(r);return p=this._thumbnails.slice(d),this},lazyLoad:function(e,i){var n=e.constructor==Array?e:[e],a=this,o=0;return t.each(n,function(e,r){if(!(r>a._thumbnails.length-1)){var s=a._thumbnails[r],l=s.data,c=function(){++o==n.length&&"function"==typeof i&&i.call(a)},h=t(s.container).data("thumbload");s.video?h.call(a,s,c):s.load(l.src,function(t){h.call(a,t,c)})}}),this},lazyLoadChunks:function(t,i){var n=this.getDataLength(),a=0,o=0,r=[],s=[],l=this;for(i=i||0;n>a;a++)s.push(a),(++o==t||a==n-1)&&(r.push(s),o=0,s=[]);var c=function(t){var n=r.shift();n&&e.setTimeout(function(){l.lazyLoad(n,function(){c(!0)})},i&&t?i:0)};return c(!1),this},_run:function(){var a=this;a._createThumbnails(),H.wait({timeout:1e4,until:function(){return i.OPERA&&a.$("stage").css("display","inline-block"),a._stageWidth=a.$("stage").width(),a._stageHeight=a.$("stage").height(),a._stageWidth&&a._stageHeight>50},success:function(){if(E.push(a),a._options.swipe){var o=a.$("images").width(a.getDataLength()*a._stageWidth);t.each(new Array(a.getDataLength()),function(e){var n=new i.Picture,r=a.getData(e);t(n.container).css({position:"absolute",top:0,left:a._stageWidth*e}).prepend(a._layers[e]=t(H.create("galleria-layer")).css({position:"absolute",top:0,left:0,right:0,bottom:0,zIndex:2})[0]).appendTo(o),r.video&&O(n.container),a._controls.slides.push(n);var s=new i.Picture;s.isIframe=!0,t(s.container).attr("class","galleria-frame").css({position:"absolute",top:0,left:0,zIndex:4,background:"#000",display:"none"}).appendTo(n.container),a._controls.frames.push(s)}),a.finger.setup()}return H.show(a.get("counter")),a._options.carousel&&a._carousel.bindControls(),a._options.autoplay&&(a.pause(),"number"==typeof a._options.autoplay&&(a._playtime=a._options.autoplay),a._playing=!0),a._firstrun?(a._options.autoplay&&a.trigger(i.PLAY),void("number"==typeof a._options.show&&a.show(a._options.show))):(a._firstrun=!0,i.History&&i.History.change(function(t){isNaN(t)?e.history.go(-1):a.show(t,n,!0)}),a.trigger(i.READY),a.theme.init.call(a,a._options),t.each(i.ready.callbacks,function(t,e){"function"==typeof e&&e.call(a,a._options)}),a._options.extend.call(a,a._options),/^[0-9]{1,4}$/.test(p)&&i.History?a.show(p,n,!0):a._data[a._options.show]&&a.show(a._options.show),void(a._options.autoplay&&a.trigger(i.PLAY)))},error:function(){i.raise("Stage width or height is too small to show the gallery. Traced measures: width:"+a._stageWidth+"px, height: "+a._stageHeight+"px.",!0)}})},load:function(e,n,a){var o=this,r=this._options;return this._data=[],this._thumbnails=[],this.$("thumbnails").empty(),"function"==typeof n&&(a=n,n=null),e=e||r.dataSource,n=n||r.dataSelector,a=a||r.dataConfig,t.isPlainObject(e)&&(e=[e]),t.isArray(e)?this.validate(e)?this._data=e:i.raise("Load failed: JSON Array not valid."):(n+=",.video,.iframe",t(e).find(n).each(function(e,i){i=t(i);var n={},r=i.parent(),s=r.attr("href"),l=r.attr("rel");s&&("IMG"==i[0].nodeName||i.hasClass("video"))&&S(s)?n.video=s:s&&i.hasClass("iframe")?n.iframe=s:n.image=n.big=s,l&&(n.big=l),t.each("big title description link layer image".split(" "),function(t,e){i.data(e)&&(n[e]=i.data(e).toString())}),n.big||(n.big=n.image),o._data.push(t.extend({title:i.attr("title")||"",thumb:i.attr("src"),image:i.attr("src"),big:i.attr("src"),description:i.attr("alt")||"",link:i.attr("longdesc"),original:i.get(0)},n,a(i)))})),"function"==typeof r.dataSort?s.sort.call(this._data,r.dataSort):"random"==r.dataSort&&this._data.sort(function(){return f.round(f.random())-.5}),this.getDataLength()&&this._parseData(function(){this.trigger(i.DATA)}),this},_parseData:function(e){var i,a=this,o=!1,r=function(){var i=!0;t.each(a._data,function(t,e){return e.loading?(i=!1,!1):void 0}),i&&!o&&(o=!0,e.call(a))};return t.each(this._data,function(e,o){if(i=a._data[e],"thumb"in o==!1&&(i.thumb=o.image),o.big||(i.big=o.image),"video"in o){var s=S(o.video);s&&(i.iframe=new I(s.provider,s.id).embed()+function(){if("object"==typeof a._options[s.provider]){var e="?",i=[];return t.each(a._options[s.provider],function(t,e){i.push(t+"="+e)}),"youtube"==s.provider&&(i=["wmode=opaque"].concat(i)),e+i.join("&")}return""}(),i.thumb&&i.image||t.each(["thumb","image"],function(t,e){if("image"==e&&!a._options.videoPoster)return void(i.image=n);var o=new I(s.provider,s.id);i[e]||(i.loading=!0,o.getMedia(e,function(t,e){return function(i){t[e]=i,"image"!=e||t.big||(t.big=t.image),delete t.loading,r()}}(i,e)))}))}}),r(),this},destroy:function(){return this.$("target").data("galleria",null),this.$("container").off("galleria"),this.get("target").innerHTML=this._original.html,this.clearTimer(),H.removeFromArray(D,this),H.removeFromArray(E,this),i._waiters.length&&t.each(i._waiters,function(t,i){i&&e.clearTimeout(i)}),this},splice:function(){var t=this,i=H.array(arguments);return e.setTimeout(function(){s.splice.apply(t._data,i),t._parseData(function(){t._createThumbnails()})},2),t},push:function(){var n=this,a=H.array(arguments);return 1==a.length&&a[0].constructor==Array&&(a=a[0]),e.setTimeout(function(){if(s.push.apply(n._data,a),n._parseData(function(){n._createThumbnails(a)}),n._options.swipe){var e=n.getDataLength()-1,o=n.$("images").width(e*n._stageWidth),r=new i.Picture,l=n.getData(e);t(r.container).css({position:"absolute",top:0,left:n._stageWidth*e}).prepend(n._layers[e]=t(H.create("galleria-layer")).css({position:"absolute",top:0,left:0,right:0,bottom:0,zIndex:2})[0]).appendTo(o),l.video&&O(r.container),n._controls.slides.push(r);var c=new i.Picture;c.isIframe=!0,t(c.container).attr("class","galleria-frame").css({position:"absolute",top:0,left:0,zIndex:4,background:"#000",display:"none"}).appendTo(r.container),n._controls.frames.push(c),n.finger.setup()}},2),n},_getActive:function(){return this._controls.getActive()},validate:function(t){return!0},bind:function(t,e){return t=k(t),this.$("container").on(t,this.proxy(e)),this},unbind:function(t){return t=k(t),this.$("container").off(t),this},trigger:function(e){return e="object"==typeof e?t.extend(e,{scope:this}):{type:k(e),scope:this},this.$("container").trigger(e),this},addIdleState:function(t,e,i,n){return this._idle.add.apply(this._idle,H.array(arguments)),this},removeIdleState:function(t){return this._idle.remove.apply(this._idle,H.array(arguments)),this},enterIdleMode:function(){return this._idle.hide(),this},exitIdleMode:function(){return this._idle.showAll(),this},enterFullscreen:function(t){return this._fullscreen.enter.apply(this,H.array(arguments)),this},exitFullscreen:function(t){return this._fullscreen.exit.apply(this,H.array(arguments)),this},toggleFullscreen:function(t){return this._fullscreen[this.isFullscreen()?"exit":"enter"].apply(this,H.array(arguments)),this},bindTooltip:function(t,e){return this._tooltip.bind.apply(this._tooltip,H.array(arguments)),this},defineTooltip:function(t,e){return this._tooltip.define.apply(this._tooltip,H.array(arguments)),this},refreshTooltip:function(t){return this._tooltip.show.apply(this._tooltip,H.array(arguments)),this},openLightbox:function(){return this._lightbox.show.apply(this._lightbox,H.array(arguments)),this},closeLightbox:function(){return this._lightbox.hide.apply(this._lightbox,H.array(arguments)),this},hasVariation:function(e){return t.inArray(e,this._options.variation.split(/\s+/))>-1},getActiveImage:function(){var t=this._getActive();return t?t.image:n},getActiveThumb:function(){return this._thumbnails[this._active].image||n},getMousePosition:function(t){return{x:t.pageX-this.$("container").offset().left,y:t.pageY-this.$("container").offset().top}},addPan:function(e){if(this._options.imageCrop!==!1){e=t(e||this.getActiveImage());var i=this,n=e.width()/2,a=e.height()/2,o=parseInt(e.css("left"),10),r=parseInt(e.css("top"),10),s=o||0,l=r||0,c=0,h=0,u=!1,d=H.timestamp(),p=0,g=0,m=function(t,i,n){if(t>0&&(g=f.round(f.max(-1*t,f.min(0,i))),p!==g))if(p=g,8===y)e.parent()["scroll"+n](-1*g);else{var a={};a[n.toLowerCase()]=g,e.css(a)}},v=function(t){H.timestamp()-d<50||(u=!0,n=i.getMousePosition(t).x,a=i.getMousePosition(t).y)},_=function(t){u&&(c=e.width()-i._stageWidth,h=e.height()-i._stageHeight,o=n/i._stageWidth*c*-1,r=a/i._stageHeight*h*-1,s+=(o-s)/i._options.imagePanSmoothness,l+=(r-l)/i._options.imagePanSmoothness,m(h,l,"Top"),m(c,s,"Left"))};return 8===y&&(e.parent().scrollTop(-1*l).scrollLeft(-1*s),e.css({top:0,left:0})),this.$("stage").off("mousemove",v).on("mousemove",v),this.addTimer("pan"+i._id,_,50,!0),this}},proxy:function(t,e){return"function"!=typeof t?m:(e=e||this,function(){return t.apply(e,H.array(arguments))})},getThemeName:function(){return this.theme.name},removePan:function(){return this.$("stage").off("mousemove"),this.clearTimer("pan"+this._id),this},addElement:function(e){var i=this._dom;return t.each(H.array(arguments),function(t,e){i[e]=H.create("galleria-"+e)}),this},attachKeyboard:function(t){return this._keyboard.attach.apply(this._keyboard,H.array(arguments)),this},detachKeyboard:function(){return this._keyboard.detach.apply(this._keyboard,H.array(arguments)),this},appendChild:function(t,e){return this.$(t).append(this.get(e)||e),this},prependChild:function(t,e){return this.$(t).prepend(this.get(e)||e),this},remove:function(t){return this.$(H.array(arguments).join(",")).remove(),this},append:function(t){var e,i;for(e in t)if(t.hasOwnProperty(e))if(t[e].constructor===Array)for(i=0;t[e][i];i++)this.appendChild(e,t[e][i]);else this.appendChild(e,t[e]);return this},_scaleImage:function(e,i){if(e=e||this._controls.getActive()){var n,a=function(e){t(e.container).children(":first").css({top:f.max(0,H.parseValue(e.image.style.top)),left:f.max(0,H.parseValue(e.image.style.left)),width:H.parseValue(e.image.width),height:H.parseValue(e.image.height)})};return i=t.extend({width:this._stageWidth,height:this._stageHeight,crop:this._options.imageCrop,max:this._options.maxScaleRatio,min:this._options.minScaleRatio,margin:this._options.imageMargin,position:this._options.imagePosition,iframelimit:this._options.maxVideoSize},i),this._options.layerFollow&&this._options.imageCrop!==!0?"function"==typeof i.complete?(n=i.complete,i.complete=function(){n.call(e,e),a(e)}):i.complete=a:t(e.container).children(":first").css({top:0,left:0}),e.scale(i),this}},updateCarousel:function(){return this._carousel.update(),this},resize:function(e,i){"function"==typeof e&&(i=e,e=n),e=t.extend({width:0,height:0},e);var a=this,o=this.$("container");return t.each(e,function(t,i){i||(o[t]("auto"),e[t]=a._getWH()[t])}),t.each(e,function(t,e){o[t](e)}),this.rescale(i)},rescale:function(e,a,o){var r=this;"function"==typeof e&&(o=e,e=n);var s=function(){r._stageWidth=e||r.$("stage").width(),r._stageHeight=a||r.$("stage").height(),r._options.swipe?(t.each(r._controls.slides,function(e,i){r._scaleImage(i),t(i.container).css("left",r._stageWidth*e)}),r.$("images").css("width",r._stageWidth*r.getDataLength())):r._scaleImage(),r._options.carousel&&r.updateCarousel();var n=r._controls.frames[r._controls.active];n&&r._controls.frames[r._controls.active].scale({width:r._stageWidth,height:r._stageHeight,iframelimit:r._options.maxVideoSize}),r.trigger(i.RESCALE),"function"==typeof o&&o.call(r)};return s.call(r),this},refreshImage:function(){return this._scaleImage(),this._options.imagePan&&this.addPan(),this},_preload:function(){if(this._options.preload){var t,e,n,a=this.getNext();try{for(e=this._options.preload;e>0;e--)t=new i.Picture,n=this.getData(a),t.preload(this.isFullscreen()&&n.big?n.big:n.image),a=this.getNext(a)}catch(o){}}},show:function(n,a,o){var r=this._options.swipe;if(r||!(this._queue.length>3||n===!1||!this._options.queue&&this._queue.stalled)){if(n=f.max(0,f.min(parseInt(n,10),this.getDataLength()-1)),a="undefined"!=typeof a?!!a:n<this.getIndex(),o=o||!1,!o&&i.History)return void i.History.set(n.toString());if(this.finger&&n!==this._active&&(this.finger.to=-(n*this.finger.width),this.finger.index=n),this._active=n,r){var l=this.getData(n),c=this;if(!l)return;var h=this.isFullscreen()&&l.big?l.big:l.image||l.iframe,u=this._controls.slides[n],d=u.isCached(h),p=this._thumbnails[n],g={cached:d,index:n,rewind:a,imageTarget:u.image,thumbTarget:p.image,galleriaData:l};this.trigger(t.extend(g,{type:i.LOADSTART})),c.$("container").removeClass("videoplay");var m=function(){c._layers[n].innerHTML=c.getData().layer||"",c.trigger(t.extend(g,{type:i.LOADFINISH})),c._playCheck()};c._preload(),e.setTimeout(function(){u.ready&&t(u.image).attr("src")==h?(c.trigger(t.extend(g,{type:i.IMAGE})),m()):(l.iframe&&!l.image&&(u.isIframe=!0),u.load(h,function(e){g.imageTarget=e.image,c._scaleImage(e,m).trigger(t.extend(g,{type:i.IMAGE})),m()}))},100)}else s.push.call(this._queue,{index:n,rewind:a}),this._queue.stalled||this._show();return this}},_show:function(){var a=this,o=this._queue[0],r=this.getData(o.index);if(r){var l=this.isFullscreen()&&r.big?r.big:r.image||r.iframe,c=this._controls.getActive(),h=this._controls.getNext(),u=h.isCached(l),d=this._thumbnails[o.index],p=function(){t(h.image).trigger("mouseup")};a.$("container").toggleClass("iframe",!!r.isIframe).removeClass("videoplay");var g=function(o,r,l,c,h){return function(){var u;M.active=!1,H.toggleQuality(r.image,a._options.imageQuality),a._layers[a._controls.active].innerHTML="",t(l.container).css({zIndex:0,opacity:0}).show(),t(l.container).find("iframe, .galleria-videoicon").remove(),t(a._controls.frames[a._controls.active].container).hide(),t(r.container).css({zIndex:1,left:0,top:0}).show(),a._controls.swap(),a._options.imagePan&&a.addPan(r.image),(o.iframe&&o.image||o.link||a._options.lightbox||a._options.clicknext)&&t(r.image).css({cursor:"pointer"}).on("mouseup",function(r){if(!("number"==typeof r.which&&r.which>1)){if(o.iframe){a.isPlaying()&&a.pause();var s=a._controls.frames[a._controls.active],l=a._stageWidth,c=a._stageHeight;return t(s.container).css({width:l,height:c,opacity:0}).show().animate({opacity:1},200),void e.setTimeout(function(){s.load(o.iframe+(o.video?"&autoplay=1":""),{width:l,height:c},function(t){a.$("container").addClass("videoplay"),t.scale({width:a._stageWidth,height:a._stageHeight,iframelimit:o.video?a._options.maxVideoSize:n})})},100)}return a._options.clicknext&&!i.TOUCH?(a._options.pauseOnInteraction&&a.pause(),void a.next()):o.link?void(a._options.popupLinks?u=e.open(o.link,"_blank"):e.location.href=o.link):void(a._options.lightbox&&a.openLightbox())}}),a._playCheck(),a.trigger({type:i.IMAGE,index:c.index,imageTarget:r.image,thumbTarget:h.image,galleriaData:o}),s.shift.call(a._queue),a._queue.stalled=!1,a._queue.length&&a._show()}}(r,h,c,o,d);this._options.carousel&&this._options.carouselFollow&&this._carousel.follow(o.index),a._preload(),H.show(h.container),h.isIframe=r.iframe&&!r.image,t(a._thumbnails[o.index].container).addClass("active").siblings(".active").removeClass("active"),a.trigger({type:i.LOADSTART,cached:u,index:o.index,rewind:o.rewind,imageTarget:h.image,thumbTarget:d.image,galleriaData:r}),a._queue.stalled=!0,h.load(l,function(e){var s=t(a._layers[1-a._controls.active]).html(r.layer||"").hide();a._scaleImage(e,{complete:function(e){"image"in c&&H.toggleQuality(c.image,!1),H.toggleQuality(e.image,!1),a.removePan(),a.setInfo(o.index),a.setCounter(o.index),r.layer&&(s.show(),(r.iframe&&r.image||r.link||a._options.lightbox||a._options.clicknext)&&s.css("cursor","pointer").off("mouseup").mouseup(p)),r.video&&r.image&&O(e.container);var l=a._options.transition;if(t.each({initial:null===c.image,touch:i.TOUCH,fullscreen:a.isFullscreen()},function(t,e){return e&&a._options[t+"Transition"]!==n?(l=a._options[t+"Transition"],!1):void 0}),l in M.effects==!1)g();else{var h={prev:c.container,next:e.container,rewind:o.rewind,speed:a._options.transitionSpeed||400};M.active=!0,M.init.call(a,l,h,g)}a.trigger({type:i.LOADFINISH,cached:u,index:o.index,rewind:o.rewind,imageTarget:e.image,thumbTarget:a._thumbnails[o.index].image,galleriaData:a.getData(o.index)})}})})}},getNext:function(t){return t="number"==typeof t?t:this.getIndex(),t===this.getDataLength()-1?0:t+1},getPrev:function(t){return t="number"==typeof t?t:this.getIndex(),0===t?this.getDataLength()-1:t-1},next:function(){return this.getDataLength()>1&&this.show(this.getNext(),!1),this},prev:function(){return this.getDataLength()>1&&this.show(this.getPrev(),!0),this},get:function(t){return t in this._dom?this._dom[t]:null},getData:function(t){return t in this._data?this._data[t]:this._data[this._active]},getDataLength:function(){return this._data.length},getIndex:function(){return"number"==typeof this._active?this._active:!1},getStageHeight:function(){return this._stageHeight},getStageWidth:function(){return this._stageWidth},getOptions:function(t){return"undefined"==typeof t?this._options:this._options[t]},setOptions:function(e,i){return"object"==typeof e?t.extend(this._options,e):this._options[e]=i,this},play:function(t){return this._playing=!0,this._playtime=t||this._playtime,this._playCheck(),this.trigger(i.PLAY),this},pause:function(){return this._playing=!1,this.trigger(i.PAUSE),this},playToggle:function(t){return this._playing?this.pause():this.play(t)},isPlaying:function(){return this._playing},isFullscreen:function(){return this._fullscreen.active},_playCheck:function(){var t=this,e=0,n=20,a=H.timestamp(),o="play"+this._id;if(this._playing){this.clearTimer(o);var r=function(){return e=H.timestamp()-a,e>=t._playtime&&t._playing?(t.clearTimer(o),void t.next()):void(t._playing&&(t.trigger({type:i.PROGRESS,percent:f.ceil(e/t._playtime*100),seconds:f.floor(e/1e3),milliseconds:e}),t.addTimer(o,r,n)))};t.addTimer(o,r,n)}},setPlaytime:function(t){return this._playtime=t,this},setIndex:function(t){return this._active=t,this},setCounter:function(t){if("number"==typeof t?t++:"undefined"==typeof t&&(t=this.getIndex()+1),this.get("current").innerHTML=t,y){var e=this.$("counter"),i=e.css("opacity");1===parseInt(i,10)?H.removeAlpha(e[0]):this.$("counter").css("opacity",i)}return this},setInfo:function(e){var i=this,n=this.getData(e);return t.each(["title","description"],function(t,e){var a=i.$("info-"+e);n[e]?a[n[e].length?"show":"hide"]().html(n[e]):a.empty().hide()}),this},hasInfo:function(t){var e,i="title description".split(" ");for(e=0;i[e];e++)if(this.getData(t)[i[e]])return!0;return!1},jQuery:function(e){var i=this,n=[];t.each(e.split(","),function(e,a){a=t.trim(a),i.get(a)&&n.push(a)});var a=t(i.get(n.shift()));return t.each(n,function(t,e){a=a.add(i.get(e))}),a},$:function(t){return this.jQuery.apply(this,H.array(arguments))}},t.each(x,function(t,e){var n=/_/.test(e)?e.replace(/_/g,""):e;i[e.toUpperCase()]="galleria."+n}),t.extend(i,{IE9:9===y,IE8:8===y,IE7:7===y,IE6:6===y,IE:y,WEBKIT:/webkit/.test(d),CHROME:/chrome/.test(d),SAFARI:/safari/.test(d)&&!/chrome/.test(d),QUIRK:y&&a.compatMode&&"BackCompat"===a.compatMode,MAC:/mac/.test(navigator.platform.toLowerCase()),OPERA:!!e.opera,IPHONE:/iphone/.test(d),IPAD:/ipad/.test(d),ANDROID:/android/.test(d),TOUCH:"ontouchstart"in a}),i.addTheme=function(n){n.name||i.raise("No theme name specified"),"object"!=typeof n.defaults?n.defaults={}:n.defaults=T(n.defaults);var a,o=!1;return"string"==typeof n.css?(t("link").each(function(t,e){return a=new RegExp(n.css),a.test(e.href)?(o=!0,F(n),!1):void 0}),o||t(function(){var r=0,s=function(){t("script").each(function(t,i){a=new RegExp("galleria\\."+n.name.toLowerCase()+"\\."),a.test(i.src)&&(o=i.src.replace(/[^\/]*$/,"")+n.css,e.setTimeout(function(){H.loadCSS(o,"galleria-theme-"+n.name,function(){F(n)})},1))}),o||(r++>5?i.raise("No theme CSS loaded"):e.setTimeout(s,500))};s()})):F(n),n},i.loadTheme=function(n,a){if(!t("script").filter(function(){return t(this).attr("src")==n}).length){var o,r=!1;return t(e).load(function(){r||(o=e.setTimeout(function(){r||i.raise("Galleria had problems loading theme at "+n+". Please check theme path or load manually.",!0)},2e4))}),H.loadScript(n,function(){r=!0,e.clearTimeout(o)}),i}},i.get=function(t){return D[t]?D[t]:"number"!=typeof t?D:void i.raise("Gallery index "+t+" not found")},i.configure=function(e,n){var a={};return"string"==typeof e&&n?(a[e]=n,e=a):t.extend(a,e),i.configure.options=a,t.each(i.get(),function(t,e){e.setOptions(a)}),i},i.configure.options={},i.on=function(e,n){if(e){n=n||m;var a=e+n.toString().replace(/\s/g,"")+H.timestamp();return t.each(i.get(),function(t,i){i._binds.push(a),i.bind(e,n)}),i.on.binds.push({type:e,callback:n,hash:a}),i}},i.on.binds=[],i.run=function(e,n){return t.isFunction(n)&&(n={extend:n}),t(e||"#galleria").galleria(n),i},i.addTransition=function(t,e){return M.effects[t]=e,i},i.utils=H,i.log=function(){var i=H.array(arguments);if(!("console"in e&&"log"in e.console))return e.alert(i.join("<br>"));try{return e.console.log.apply(e.console,i)}catch(n){t.each(i,function(){e.console.log(this)})}},i.ready=function(e){return"function"!=typeof e?i:(t.each(E,function(t,i){e.call(i,i._options)}),i.ready.callbacks.push(e),i)},i.ready.callbacks=[],i.raise=function(e,i){var n=i?"Fatal error":"Error",a={color:"#fff",position:"absolute",top:0,left:0,zIndex:1e5},o=function(e){var o='<div style="padding:4px;margin:0 0 2px;background:#'+(i?"811":"222")+';">'+(i?"<strong>"+n+": </strong>":"")+e+"</div>";t.each(D,function(){var t=this.$("errors"),e=this.$("target");t.length||(e.css("position","relative"),t=this.addElement("errors").appendChild("target","errors").$("errors").css(a)),t.append(o)}),D.length||t("<div>").css(t.extend(a,{position:"fixed"})).append(o).appendTo(_().body)};if(c){if(o(e),i)throw new Error(n+": "+e)}else if(i){if(L)return;L=!0,i=!1,o("Gallery could not load.");
}},i.version=l,i.getLoadedThemes=function(){return t.map(P,function(t){return t.name})},i.requires=function(t,e){return e=e||"You need to upgrade Galleria to version "+t+" to use one or more components.",i.version<t&&i.raise(e,!0),i},i.Picture=function(e){this.id=e||null,this.image=null,this.container=H.create("galleria-image"),t(this.container).css({overflow:"hidden",position:"relative"}),this.original={width:0,height:0},this.ready=!1,this.isIframe=!1},i.Picture.prototype={cache:{},show:function(){H.show(this.image)},hide:function(){H.moveOut(this.image)},clear:function(){this.image=null},isCached:function(t){return!!this.cache[t]},preload:function(e){t(new Image).load(function(t,e){return function(){e[t]=t}}(e,this.cache)).attr("src",e)},load:function(n,a,o){if("function"==typeof a&&(o=a,a=null),this.isIframe){var r="if"+(new Date).getTime(),s=this.image=t("<iframe>",{src:n,frameborder:0,id:r,allowfullscreen:!0,css:{visibility:"hidden"}})[0];return a&&t(s).css(a),t(this.container).find("iframe,img").remove(),this.container.appendChild(this.image),t("#"+r).load(function(i,n){return function(){e.setTimeout(function(){t(i.image).css("visibility","visible"),"function"==typeof n&&n.call(i,i)},10)}}(this,o)),this.container}this.image=new Image,i.IE8&&t(this.image).css("filter","inherit"),i.IE||i.CHROME||i.SAFARI||t(this.image).css("image-rendering","optimizequality");var l=!1,c=!1,h=t(this.container),d=t(this.image),p=function(){l?u?t(this).attr("src",u):i.raise("Image not found: "+n):(l=!0,e.setTimeout(function(t,e){return function(){t.attr("src",e+(e.indexOf("?")>-1?"&":"?")+H.timestamp())}}(t(this),n),50))},g=function(n,o,r){return function(){var s=function(){t(this).off("load"),n.original=a||{height:this.height,width:this.width},i.HAS3D&&(this.style.MozTransform=this.style.webkitTransform="translate3d(0,0,0)"),h.append(this),n.cache[r]=r,"function"==typeof o&&e.setTimeout(function(){o.call(n,n)},1)};this.width&&this.height?s.call(this):!function(e){H.wait({until:function(){return e.width&&e.height},success:function(){s.call(e)},error:function(){c?i.raise("Could not extract width/height from image: "+e.src+". Traced measures: width:"+e.width+"px, height: "+e.height+"px."):(t(new Image).load(g).attr("src",e.src),c=!0)},timeout:100})}(this)}}(this,o,n);return h.find("iframe,img").remove(),d.css("display","block"),H.hide(this.image),t.each("minWidth minHeight maxWidth maxHeight".split(" "),function(t,e){d.css(e,/min/.test(e)?"0":"none")}),d.load(g).on("error",p).attr("src",n),this.container},scale:function(e){var a=this;if(e=t.extend({width:0,height:0,min:n,max:n,margin:0,complete:m,position:"center",crop:!1,canvas:!1,iframelimit:n},e),this.isIframe){var o,r,s=e.width,l=e.height;if(e.iframelimit){var c=f.min(e.iframelimit/s,e.iframelimit/l);1>c?(o=s*c,r=l*c,t(this.image).css({top:l/2-r/2,left:s/2-o/2,position:"absolute"})):t(this.image).css({top:0,left:0})}t(this.image).width(o||s).height(r||l).removeAttr("width").removeAttr("height"),t(this.container).width(s).height(l),e.complete.call(a,a);try{this.image.contentWindow&&t(this.image.contentWindow).trigger("resize")}catch(h){}return this.container}if(!this.image)return this.container;var u,d,p,g=t(a.container);return H.wait({until:function(){return u=e.width||g.width()||H.parseValue(g.css("width")),d=e.height||g.height()||H.parseValue(g.css("height")),u&&d},success:function(){var i=(u-2*e.margin)/a.original.width,n=(d-2*e.margin)/a.original.height,o=f.min(i,n),r=f.max(i,n),s={"true":r,width:i,height:n,"false":o,landscape:a.original.width>a.original.height?r:o,portrait:a.original.width<a.original.height?r:o},l=s[e.crop.toString()],c="";e.max&&(l=f.min(e.max,l)),e.min&&(l=f.max(e.min,l)),t.each(["width","height"],function(e,i){t(a.image)[i](a[i]=a.image[i]=f.round(a.original[i]*l))}),t(a.container).width(u).height(d),e.canvas&&$&&($.elem.width=a.width,$.elem.height=a.height,c=a.image.src+":"+a.width+"x"+a.height,a.image.src=$.cache[c]||function(t){$.context.drawImage(a.image,0,0,a.original.width*l,a.original.height*l);try{return p=$.elem.toDataURL(),$.length+=p.length,$.cache[t]=p,p}catch(e){return a.image.src}}(c));var h={},g={},m=function(e,i,n){var o=0;if(/\%/.test(e)){var r=parseInt(e,10)/100,s=a.image[i]||t(a.image)[i]();o=f.ceil(-1*s*r+n*r)}else o=H.parseValue(e);return o},v={top:{top:0},left:{left:0},right:{left:"100%"},bottom:{top:"100%"}};t.each(e.position.toLowerCase().split(" "),function(t,e){"center"===e&&(e="50%"),h[t?"top":"left"]=e}),t.each(h,function(e,i){v.hasOwnProperty(i)&&t.extend(g,v[i])}),h=h.top?t.extend(h,g):g,h=t.extend({top:"50%",left:"50%"},h),t(a.image).css({position:"absolute",top:m(h.top,"height",d),left:m(h.left,"width",u)}),a.show(),a.ready=!0,e.complete.call(a,a)},error:function(){i.raise("Could not scale image: "+a.image.src)},timeout:1e3}),this}},t.extend(t.easing,{galleria:function(t,e,i,n,a){return(e/=a/2)<1?n/2*e*e*e+i:n/2*((e-=2)*e*e+2)+i},galleriaIn:function(t,e,i,n,a){return n*(e/=a)*e+i},galleriaOut:function(t,e,i,n,a){return-n*(e/=a)*(e-2)+i}}),i.Finger=function(){var n=(f.abs,i.HAS3D=function(){var e,i,n=a.createElement("p"),o=["webkit","O","ms","Moz",""],r=0,s="transform";for(_().html.insertBefore(n,null);o[r];r++)i=o[r]?o[r]+"Transform":s,void 0!==n.style[i]&&(n.style[i]="translate3d(1px,1px,1px)",e=t(n).css(o[r]?"-"+o[r].toLowerCase()+"-"+s:s));return _().html.removeChild(n),void 0!==e&&e.length>0&&"none"!==e}()),r=function(){var t="RequestAnimationFrame";return e.requestAnimationFrame||e["webkit"+t]||e["moz"+t]||e["o"+t]||e["ms"+t]||function(t){e.setTimeout(t,1e3/60)}}(),s=function(i,a){if(this.config={start:0,duration:500,onchange:function(){},oncomplete:function(){},easing:function(t,e,i,n,a){return-n*((e=e/a-1)*e*e*e-1)+i}},this.easeout=function(t,e,i,n,a){return n*((e=e/a-1)*e*e*e*e+1)+i},i.children.length){var o=this;t.extend(this.config,a),this.elem=i,this.child=i.children[0],this.to=this.pos=0,this.touching=!1,this.start={},this.index=this.config.start,this.anim=0,this.easing=this.config.easing,n||(this.child.style.position="absolute",this.elem.style.position="relative"),t.each(["ontouchstart","ontouchmove","ontouchend","setup"],function(t,e){o[e]=function(t){return function(){t.apply(o,arguments)}}(o[e])}),this.setX=function(){var t=o.child.style;return n?void(t.MozTransform=t.webkitTransform=t.transform="translate3d("+o.pos+"px,0,0)"):void(t.left=o.pos+"px")},t(i).on("touchstart",this.ontouchstart),t(e).on("resize",this.setup),t(e).on("orientationchange",this.setup),this.setup(),function s(){r(s),o.loop.call(o)}()}};return s.prototype={constructor:s,setup:function(){this.width=t(this.elem).width(),this.length=f.ceil(t(this.child).width()/this.width),0!==this.index&&(this.index=f.max(0,f.min(this.index,this.length-1)),this.pos=this.to=-this.width*this.index)},setPosition:function(t){this.pos=t,this.to=t},ontouchstart:function(t){var e=t.originalEvent.touches;this.start={pageX:e[0].pageX,pageY:e[0].pageY,time:+new Date},this.isScrolling=null,this.touching=!0,this.deltaX=0,o.on("touchmove",this.ontouchmove),o.on("touchend",this.ontouchend)},ontouchmove:function(t){var e=t.originalEvent.touches;e&&e.length>1||t.scale&&1!==t.scale||(this.deltaX=e[0].pageX-this.start.pageX,null===this.isScrolling&&(this.isScrolling=!!(this.isScrolling||f.abs(this.deltaX)<f.abs(e[0].pageY-this.start.pageY))),this.isScrolling||(t.preventDefault(),this.deltaX/=!this.index&&this.deltaX>0||this.index==this.length-1&&this.deltaX<0?f.abs(this.deltaX)/this.width+1.8:1,this.to=this.deltaX-this.index*this.width),t.stopPropagation())},ontouchend:function(t){this.touching=!1;var e=+new Date-this.start.time<250&&f.abs(this.deltaX)>40||f.abs(this.deltaX)>this.width/2,i=!this.index&&this.deltaX>0||this.index==this.length-1&&this.deltaX<0;this.isScrolling||this.show(this.index+(e&&!i?this.deltaX<0?1:-1:0)),o.off("touchmove",this.ontouchmove),o.off("touchend",this.ontouchend)},show:function(t){t!=this.index?this.config.onchange.call(this,t):this.to=-(t*this.width)},moveTo:function(t){t!=this.index&&(this.pos=this.to=-(t*this.width),this.index=t)},loop:function(){var t=this.to-this.pos,e=1;if(this.width&&t&&(e=f.max(.5,f.min(1.5,f.abs(t/this.width)))),this.touching||f.abs(t)<=1)this.pos=this.to,t=0,this.anim&&!this.touching&&this.config.oncomplete(this.index),this.anim=0,this.easing=this.config.easing;else{this.anim||(this.anim={start:this.pos,time:+new Date,distance:t,factor:e,destination:this.to});var i=+new Date-this.anim.time,n=this.config.duration*this.anim.factor;if(i>n||this.anim.destination!=this.to)return this.anim=0,void(this.easing=this.easeout);this.pos=this.easing(null,i,this.anim.start,this.anim.distance,n)}this.setX()}},s}(),t.fn.galleria=function(e){var n=this.selector;return t(this).length?this.each(function(){t.data(this,"galleria")&&(t.data(this,"galleria").destroy(),t(this).find("*").hide()),t.data(this,"galleria",(new i).init(this,e))}):(t(function(){t(n).length?t(n).galleria(e):i.utils.wait({until:function(){return t(n).length},success:function(){t(n).galleria(e)},error:function(){i.raise('Init failed: Galleria could not find the element "'+n+'".')},timeout:5e3})}),this)},"object"==typeof module&&module&&"object"==typeof module.exports?module.exports=i:(e.Galleria=i,"function"==typeof define&&define.amd&&define("galleria",["jquery"],function(){return i}))}(jQuery,this);

var imagesJSON = [],
	categories = [],
	subcategories = [];
	subcatholder = '',
	currentGallery = '',
	videoid =  '',
	imageData = [],
	picked = '',
	whichgallery = '',
	currentGalleryTxt = '',
	gimmeVideos = false,
	onloadCat = '',
	mediatrigger = false,
	sabre_clientName = '',
	isHandheld = {
		Android_Mobile: function() {
			if(navigator.userAgent.match(/Android/i) && navigator.userAgent.match(/Mobile/i))
				return true;
			else
				return false;
		},
		Android_Tablet: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPod/i);
		},
		iPad: function() {
			return navigator.userAgent.match(/iPad/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		tablet: function() {
			return (isHandheld.iPad() || isHandheld.Android_Tablet);
		},
		mobile: function() {
			return (isHandheld.Android_Mobile() || isHandheld.BlackBerry() || isHandheld.iOS()  || isHandheld.iPad() || isHandheld.Opera() || isHandheld.Windows());
		},
		any: function() {
			return (isHandheld.Android_Mobile() || isHandheld.Android_Tablet() || isHandheld.BlackBerry() || isHandheld.iOS()  || isHandheld.iPad() || isHandheld.Opera() || isHandheld.Windows());
		}
	};


/**
*** @preserve Galleria Fozzie Theme 2012
**/

// defaults and globals
Galleria.requires(1.25, 'This version of Fozzie theme requires Galleria 1.2.5 or later');

(function($) {

Galleria.addTheme({
    name: 'Fozzie',
    author: 'Jimmy Morris',

    defaults: {
        transition: 'none',
        thumbCrop:  true,
		_client_name: 'Sabre Hospitality Solutions'

	},
    init: function(options) {
		var gallery = this;
		//console.log(isHandheld.any() .' '. isHandheld.Android_Mobile());

		if(isHandheld){
			this.removeIdleState( this.get('image-nav-left'));
			this.removeIdleState( this.get('image-nav-right'));
			this.addIdleState( this.get('image-nav-left'), { display: 'none' });
			this.addIdleState( this.get('image-nav-right'), { display: 'none' });
		} else {
			this.removeIdleState( this.get('image-nav-left'));
			this.removeIdleState( this.get('image-nav-right'));
			this.addIdleState( this.get('image-nav-left'), { left: -34 });
			this.addIdleState( this.get('image-nav-right'), { right:-34 });
		}
		if(isHandheld) {
			//$('body').addClass('mobile');
			// prevent the mobile devices to disable y-axis scrolling when photo-gallery is active/visiable.
			// if($('.galleria').parent().hasClass('fullscreen'))	{
			// 	$(window).bind('touchmove', function(e) {
			// 		var move = window.event;
			// 		if(move.touches[0].pageY > 0)
			// 			e.preventDefault();
			// 	});
			// }
		}

		// add some elements
		this.addElement('thumbnails-wrapper');
		this.appendChild('container', 'thumbnails-wrapper');
		this.appendChild('thumbnails-wrapper','thumbnails-container');

        this.addElement('thumbnails-tab');
        this.prependChild('thumbnails-wrapper','thumbnails-tab');
		this.appendChild('thumbnails-tab','<em class=alt>More Photos</em>');

		//cache some stuff
		var thumbstab = $('.galleria-thumbnails-tab'),
			thumbsholder = $('.galleria-thumbnails-container'),
			next_prev = $('.galleria-thumb-nav-left, .galleria-thumb-nav-right'),
			info = this.$('info'),
            touch = Galleria.TOUCH,
            click = touch ? 'touchstart' : 'click',
			crop;

		thumbstab.click(function(e){
			e.preventDefault();
			if (thumbstab.hasClass('active')) {
				$(thumbstab).removeClass('active');
				$(next_prev).animate({top: '0'});
				$(thumbsholder).animate({ 'margin-top': '0px' });
				//console.log('info2::'+info)
				$(info).animate({ bottom: '100px' });
			} else {
				e.preventDefault();
				$(thumbstab).addClass('active'); //33px
				$(next_prev).animate({top: '72px'});
				$(thumbsholder).animate({ 'margin-top': '72px' });
				//console.log('info::'+info)
				$(info).animate({ bottom: '28px' });
			}
		});

		// show loader & counter with opacity
        this.$('loader,counter').show().css('opacity', 0.3);

        // bind some stuff
		/*this.attachKeyboard({
			right: this.next,
			left: this.prev,
			up: function() {
				//console.log( 'up key pressed' )
			}
		});*/


		this.bind('image', function(e) {
			if($('.media-gallery').hasClass('first-load')) {
				this.show(onloadIndex);
				$('.media-gallery').removeClass('first-load');
			}

			if(gallery._stageWidth < 768 || gallery._stageHeight < 320 || isHandheld.mobile()){
				landscape = e.imageTarget.width >= e.imageTarget.height ? 'width' : 'height';
				gallery.setOptions( 'imageCrop', landscape ).refreshImage();
			} else {
				var imageW = e.imageTarget.width,
					imageH = e.imageTarget.height;

				if (imageH > imageW) {
					crop = 'height'; // for portrait
				} else if (imageH < imageW) {
					crop = 'true'; // for landscape
				}
				gallery.setOptions({ imageCrop: crop }).refreshImage();
			}

			//console.log('images image');
		});

		this.bind('thumbnail', function(e) {

            if (! touch ) {
                // fade thumbnails
                $(e.thumbTarget).parent().hover(function() {
                    $(this).not('.active').children('img').stop().fadeTo(100, 1);
                }, function() {
                    $(this).not('.active').children('img').stop().fadeTo(400, 0.7);
                });
            } else {
                $(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.7);
            }

			this.updateCarousel();

			/*
			var desc = this.getData( e.index ).title + '<br />' + this.getData( e.index ).description;
			if (desc) {
				this.bindTooltip( e.thumbTarget, '<p>'+desc+'</p>' );
			}*/
			//console.log('images thumbnail');
        });

		this.bind('rescale', function () {
			var cssSet = {
				'width' : Math.min( this.$('stage').width() ),
				'height' : Math.min( this.$('stage').height() )
			};

			var thumbnailSet = {
				'width' : Math.min( this.$('stage').width() ),
				'bottom' : '28px'
			};
			this.$('container').css(cssSet);

			$(this).find('img').each(function(){
				var current = gallery.getData(gallery.getIndex())
				if($(this).attr('src') == current.image) {
					if(gallery._stageWidth < 768 || gallery._stageHeight < 320 || isHandheld.mobile()){
						landscape = $(this).width() >= $(this).height() ? 'width' : 'height';
						gallery.setOptions( 'imageCrop', landscape ).refreshImage();
					} else {
						var imageW = $(this).width(),
							imageH = $(this).height();

						if (imageH > imageW) {
							crop = 'height'; // for portrait
						} else if (imageH < imageW) {
							crop = 'true'; // for landscape
						}
						gallery.setOptions({ imageCrop: crop }).refreshImage();
					}
				}
			});
		});

        this.bind('loadstart', function(e) {
			if (!e.cached) {
                this.$('loader').show().fadeTo(200, 0.4);
            }
			$(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.7);

			var video_id = this.getData(e.index).video;
			//console.log(video_id);

			if(video_id != undefined && video_id.length > 0 ) {
				this.$('youtube').show();
			} else {
				this.$('youtube').hide();
			}

           //console.log('images loadstate');
        });

        this.bind('loadfinish', function(e) {
			this.$('loader').fadeOut(200);
			this.$('images').show();
			

            $(this).find('img').each(function(){
                var current = gallery.getData(gallery.getIndex())
                if($(this).attr('src') == current.image) {
                    if(gallery._stageWidth < 768 || gallery._stageHeight < 320 || isHandheld.mobile()){
                        landscape = $(this).width() >= $(this).height() ? 'width' : 'height';
                        gallery.setOptions( 'imageCrop', landscape ).refreshImage();
                    } else {
                        var imageW = $(this).width(),
                            imageH = $(this).height();

                        if (imageH > imageW) {
                            crop = 'height'; // for portrait
                        } else if (imageH < imageW) {
                            crop = 'true'; // for landscape
                        }
                        gallery.setOptions({ imageCrop: crop }).refreshImage();
                    }
                }
            });
            
			var current_title = encodeURIComponent(this.getData( e.index ).title);
				current_desc = encodeURIComponent(this.getData(e.index).description),
				full_desc = current_title + ' ' + current_desc + ' Via ' + options._client_name,
				current_url = encodeURIComponent(window.location),
				base_url = 'http://'+encodeURIComponent(window.location.hostname),
				video_id = this.getData(e.index).video,
				current_media = this.getData(e.index).image;
				// console.log(this.getData(e.index));

			if(video_id != undefined && video_id.length > 0 ) {
				this.$('youtube').show();
			}

			//build the share links

			if(video_id)
				$('.gallery-facebook').attr('href','http://www.facebook.com/sharer.php?s=100&m2w&v=1&p[title]='+encodeURIComponent(current_title)+'&p[url]=http://youtu.be/'+video_id+'&p[images][0]='+current_media+'&p[summary]='+full_desc+' ('+current_url+')');
			else
				$('.gallery-facebook').attr('href','http://www.facebook.com/sharer.php?s=100&m2w&v=1&p[images][0]='+base_url+current_media+'&p[title]='+current_title+'&p[url]='+base_url+current_media+'&p[summary]='+full_desc);
			$('.gallery-twitter').attr('href','http://twitter.com/share?text='+full_desc+'&url='+base_url+current_media);
			$('.gallery-pinterest').attr('href','http://pinterest.com/pin/create/button/?url='+base_url+current_media+'&media='+base_url+current_media+'&description='+full_desc);


			/*console.log(options._client_name);
			console.log(current_title);
			console.log(current_desc);
			console.log(full_desc);
			console.log(current_url);
			console.log(video_id);
			console.log(current_media);
			console.log('images loadfinish');*/

		});

 		$(window).resize(function() {
			if($('.fullscreen .galleria').data('galleria')) {
				$('.fullscreen .galleria').data('galleria').rescale();

			}
		});

    }
});

$(function(){

	$('.media-gallery .gallery-close').click(function(e){
		e.preventDefault();
		var el = $(this).closest('.fullscreen');
		$(window).unbind('touchmove');
		el.find('.photo-video').removeClass('videos').addClass('photos').hide()
		el.find('.galleria,.subgalleries dt,.choose-gallery,.gallery-back,.gallery-share').hide();
		el.find('.galleria').html('');
		el.hide();
		$('html, body').css('overflow', 'auto');
	});


	$('.dropdown dt a').click(function(e){
		e.preventDefault();
		if ($(this).parent().parent().hasClass('active')) {
			$(this).parent().parent().removeClass('active');
			$(this).parent().parent().find('dd ul').slideUp('fast');
			whichgallery = $(this).parent().parent().attr('rel');
		} else {
			$(this).parent().parent().addClass('active');
			$(this).parent().parent().find('dd ul').slideDown('fast');
			whichgallery = $(this).parent().parent().attr('rel');
		}
	});

	$('.dropdown').on("click", "dd a", function(e){
		e.preventDefault();
		currentGallery = $(this).attr('rel');
		var el = $(this),
			picked = el.text();
		el.parent().parent().slideUp('fast').parent().parent().find('dt a').text(picked).parent().parent().removeClass('active');
		el.parents('.dropdown dt a').unbind();
		el.parents('.dropdown dt a').click(function(e) {
			e.preventDefault();
			if ($(this).parent().parent().hasClass('active')) {
				$(this).parent().parent().removeClass('active');
				$(this).parent().parent().find('dd ul').slideUp('fast');
				whichgallery = $(this).parent().parent().attr('gallery-launch');
			} else {
				$(this).parent().parent().addClass('active');
				$(this).parent().parent().find('dd ul').slideDown('fast');
				whichgallery = $(this).parent().parent().attr('gallery-launch');
			}
		});
		$.loadGalleria(currentGallery, el.closest('.media-gallery').attr('id'));
	});

	$(".gallery-picker").on("click", "a", function(e){
		e.preventDefault();
		currentGallery = $(this).attr('rel');
		currentGalleryTxt = $(this).text();
		var el = $(this).closest('.media-gallery');
		el.find('.choose-gallery .galleries dt a').text(currentGalleryTxt);
		el.find('.gallery-picker').hide();
		el.find('.galleria').show();
		el.find('.choose-gallery').show();
		el.find('.media-gallery .menu-bar').show();
		//console.log(mediatrigger);
		if(mediatrigger)
			el.find('.photo-video').show().addClass('photos');
		else
			el.find('.photo-video').hide();
		el.find('.gallery-share').show();
		if($(window).width() < 768 || $(window).height() <= 320 || isHandheld.mobile() && !($('.gallery-picker').is('hidden'))) {
			el.find('.media-gallery .menu-bar').show();
		} else {
			el.find('.gallery-back').show();
		}
		$.loadGalleria(currentGallery, el.attr('id'));
	});

	$('.gallery-back').click(function(e){
		e.preventDefault();
		var el = $(this).closest('.media-gallery');
		el.find('.galleria-youtube').html('');
		el.find('.galleria').hide();
		el.find('.subgalleries dt').hide();
		el.find('.choose-gallery').hide();
		el.find('.photo-video').removeClass('videos').addClass('photos').hide();
		el.find('.gallery-share').hide();
		el.find('dl.galleries dd ul').hide();
		$(this).hide();
		if($(window).width() < 768 || $(window).height() <= 320 || isHandheld.mobile() && !($('.gallery-picker').is('hidden'))) {
			$('.gallery-footer').css({'bottom': '-47px'});
		}
		el.find('.gallery-picker').show();
		gimmeVideos = false;
	});

	$(document).on("click", ".view-videos", function(e){
		e.preventDefault();
		var el = $(this).closest('.media-gallery');
		if($(this).parent().hasClass('photos')){
			$(this).parent().removeClass('photos').addClass('videos');
			el.find('.galleria-youtube').html('');
		}
		gimmeVideos = true;
		$.loadGalleria(currentGallery, el.attr('id'));
	});

	$('.share-this-on').click(function(e){
		e.preventDefault();
		if ($(this)._clicked) {
			//hide
			$(this)._clicked = false;
			if($(window).width() < 768 || $(window).height() <= 320 || isHandheld.mobile()) {
				$(this).parent().parent().animate({bottom: -47}, 'fast');
			}
		} else {
			//show
			if($(window).width() < 768 || $(window).height() <= 320 || isHandheld.mobile()) {
				$(this).parent().parent().animate({bottom: 0}, 'fast');
			}
		}
	});

	$('.gallery-share .gallery-share-icon').click(function(e){
		var url = $(this).attr('href');
        e.preventDefault();
		window.open(url, 'share', 'width=652,height=352,scrollbars=1,toolbar=0,location=0,status=0');
	});

});

$(window).resize(function() {
	if($('.media-gallery .galleria').data('galleria')) {
		Galleria.get(0).rescale(this._stageWidth,this._stageHeight); //rescales the image only
	}
});

// Single Category, no video
$.onecat = function(cat, galleryid){
	mediatrigger = false;
	$('.media-gallery').show();
	$('.gallery-picker').hide();
	$('.gallery-footer .gallery-share').show();
	$('.galleria').show();
	$.loadGalleria(cat, galleryid);
	$('#'+galleryid).fullscreenMobile();
};

// Open gallery with specific category
$.specific_cat = function(cat, galleryid){
	var el = $('#'+galleryid),
		textlabel = '',
		currentGallery = cat;
	mediatrigger = false;
	el.find('.media-gallery').show();
	el.find('.gallery-picker').hide();
	el.find('.choose-gallery .galleries dd a').each(function(){
		if(cat.toLowerCase() == $(this).attr('rel')) {
			textlabel = $(this).text();
		}
	});
	el.find('.choose-gallery .galleries dt a').text(textlabel);
	el.find('.gallery-footer .gallery-share,.choose-gallery, .photo-video').show();
	if(isHandheld.mobile())
		el.find('.gallery-back').show();
	el.show().find('.galleria').show();
	$.loadGalleria(cat, galleryid);
	$('#'+galleryid).fullscreenMobile();
};

// Open gallery with specific category's videos
$.specific_cat_videos = function(cat, gotvideos, galleryid){
	var el = $('#'+galleryid),
		textlabel = '',
		currentGallery = cat;
	mediatrigger = false;
	el.find('.media-gallery').show();
	el.find('.gallery-picker').hide();
	el.find('.choose-gallery .galleries dd a').each(function(){
		if(cat.toLowerCase() == $(this).attr('rel')) {
			textlabel = $(this).text();
		}
	});
	el.find('.choose-gallery .galleries dt a').text(textlabel);
	el.find('.gallery-footer .gallery-share,.choose-gallery, .photo-video').show();
	if(isHandheld.mobile())
		el.find('.gallery-back').show();
	el.show().find('.galleria').show();
	gimmeVideos = true;
	$.loadGalleria(cat, galleryid);
	$('#'+galleryid).fullscreenMobile();
};

$.buildGalleryPicker = function(galleryid) {
	var fullGal = getGalleriaData(),
		el = $('#'+galleryid),
		cats = [],
		$picker = el.find('.choose-gallery ul'),
        starter_cat = '',
        catCounter = 0;

    if ($picker.find('li').length < 1) {
	    $.each(fullGal, function(idx, obj) {
			$.each(obj.category, function(idx, category) {
				$.each(category, function(key, val) {
					if (cats.indexOf(key) == -1) {
						if(catCounter == 0)
	                        starter_cat = key;
	                    var catLink = '<li><a href="#" class="'+key+'" rel="'+key+'">'+val+'</a></li>';

	                    cats.push(key);
						$picker.append(catLink);
	                    catCounter++;
					}
				});
			});
		});
	} else {
		starter_cat = $picker.find('li:eq(0) a').attr('rel');
	}

	$('.choose-gallery dt a').text($picker.find('li:eq(0) a').text());

    return starter_cat;
}

// Multi-category, no video
$.catlady = function(galleryid){
	var el = $('#'+galleryid);
	mediatrigger = false;
	if (el.find('.gallery-picker li').length < 1) {
		startWith = $.buildGalleryPicker(galleryid);
	}
    $.loadGalleria(startWith, galleryid);
	el.find('.gallery-footer .gallery-share,.choose-gallery, .photo-video').show();
    el.show().fullscreenMobile();
    $('.galleria').show();
};

$.fn.fullscreenMobile = function() {  //disables up and down scrolling on touch devices when lightbox version of the gallery is visible.
	var self = this;
	if((isHandheld.any() || isHandheld.Android_Mobile()) || this.hasClass('fullscreen')) {
		$('html, body').css('overflow', 'hidden');
	}
};

$.loadGalleria = function(cat, galleryid){
	var counter = 0,
		fullGal = getGalleriaData(),
		el = $('#'+galleryid),
		newData = [];

    $.each(fullGal, function(idx, obj) {
		var hasCat = false;
		$.each(obj.category, function(idx, obj) {
			$.each(obj, function(key, val) {
				if (key == cat) {
					hasCat = true;
					counter++;
					return;
				}
			});
		});
		if (hasCat) {
			newData.push(obj);
		}
	});

	if(counter > 0) {
		if($('.galleria-info, .galleria-thumbnails-wrapper, .galleria-image-nav').css('display', 'none')) {
			$('.galleria-info, .galleria-thumbnails-wrapper, .galleria-image-nav').show();
		}

		if(el != undefined){
			el.find('.galleria').galleria({
				dataSource: newData,
				thumbCrop: true,
				swipe: true,
				fullscreenDoubleTap: false,
				transition: 'fadeBlack',
				_client_name: sabre_clientName
			});
		}
	} else {
		el.find('.galleria').html('<div class="no-stuff"><p><span>Sorry</span><br />NO VISUALS</p></div>');
		el.find('.galleria-info, .galleria-thumbnails-wrapper, .galleria-image-nav').hide();
	}
	if(counter < 2) {
		$('.galleria-thumb-nav-left, .galleria-thumb-nav-right').hide();
	} else {
		$('.galleria-thumb-nav-left, .galleria-thumb-nav-right').show();
	}
};

}(jQuery));