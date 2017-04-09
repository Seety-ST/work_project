define("pc-huipai:global/lazyload/lazyload",function(e,t,n){function r(e){var t=this,e=e||{};t.img_center=e.img_center||{},t.img_center_width=e.img_center.width||0,t.img_center_height=e.img_center.height||0,t.pre_load_165=e.pre_load_165||!1,t.flag=e.flag||"data-lazyload-url",t.distance=e.distance||0;var n=e.contain;if(!n)throw"尚未初始化化lazyload的父容器";return t.init(n,e),t}var i=e("pc-huipai:global/utility/index");r.prototype={init:function(e,t){var n=this;n.options=t,n.size=n.options.size,n.currentELE={},e?(this.container=e,n.freshELE(this.container),n.engine(),$(window).scroll(function(){n.engine()})):this.container=null},freshELE:function(e){var t=this;e=e||t.container;var n=$(e).find("["+t.flag+"]"),r=[];n.each(function(e,n){var a=$(n).attr(t.flag);r[e]={url:a,obj:n},t.pre_load_165&&(r[e]=$.extend(!0,{},r[e],{url_165:i.matching_img_size(a,165)}))}),t.currentELE=r},engine:function(){for(var e=this,t=0;t<e.currentELE.length;t++)e.elementInViewport(e.currentELE[t].obj)&&e.loadImage(e.currentELE[t].obj,{size:e.size,callback:function(n){n.src==e.currentELE[t]&&e.currentELE[t].url&&e.currentELE.splice(t,1)},url_165:e.currentELE[t].url_165})},refresh:function(){var e=this;e.freshELE(),e.engine()},img_ready:function(e,t){var n=this,r=new Image;return r.src=e,r.complete?void(n.isFunction(t.load)&&t.load.call(r)):(r.onerror=function(){n.isFunction(t.error)&&t.error.call(r),r=r.onload=r.onerror=null},void(r.onload=function(){n.isFunction(t.load)&&t.load.call(r),r=r.onload=r.onerror=null}))},loadImage:function(e,t){var n=this,r=(new Image,e.getAttribute(n.flag));t=t||{},r&&n.img_ready(r,{load:function(){var i=this;$(e).hasClass("error")&&$(e).removeClass("error refresh");var a=e.getAttribute("data-size")||t.size,o=i.width,l=i.height;if(n.img_center.is_open)n.set_img_center(e,n.img_center_width,n.img_center_height);else if(a){var c=n.setImageSize(a,o,l);e.style.height=c+"px"}n.pre_load_165&&t.url_165&&(e.src=t.url_165),n._st=setTimeout(function(){"IMG"==e.tagName?e.src=r:e.style.backgroundImage="url("+r+")",$(e).addClass("loaded").removeAttr(n.flag)},500),"function"==typeof t.callback&&t.callback.call(n,i)},error:function(){console.log(r+"图片加载失败");var t=this;$(e).addClass("error"),$(e).one("click",function(e){e.stopPropagation(),e.preventDefault(),t.retry=1,n.loadImage(t)}).addClass("refresh")}})},setImageSize:function(e,t,n){var r=parseInt(e),t=parseInt(t),n=parseInt(n),i=r*n/t;return i=parseInt(i)},elementInViewport:function(e){var t=this,n=e.getBoundingClientRect();e.getAttribute("data-top")||(e.setAttribute("data-top",n.top),$(e).addClass("img"));var r=(window.innerWidth||document.documentElement.clientWidth,window.innerHeight||document.documentElement.clientHeight,!1),i=t.distance;return(n.top>=i&&n.left>=i||n.top<0&&n.top+n.height>=i||n.left<0&&n.left+n.width>=i)&&(r=!0),r},isFunction:function(e){return"function"==typeof e},set_img_center:function(e,t,n){var r=this,i=e.getAttribute(r.flag),a=r.get_yue_img_size_from_url(i);t=parseInt(t),n=parseInt(n);var o=a.width||t,l=a.height||n;if(o/t>l/n){var c=n*o/l;e.style.width=c,e.style.height=n+"px",e.style.marginLeft=(c-t)/2>220?0:-(c-t)/2+"px"}else{var s=t*l/o;e.style.height=s,e.style.width=t+"px",e.style.marginTop=0>s-n-80?-(s-n)/2+"px":-(s-n)/2+50+"px"}},get_yue_img_size_from_url:function(e){var t=e.match(/\?(.*)/),n="",r="";if(t&&t[1]){var i=t[1],a=i.match(/(\d+)x(\d+)_(\d+)/);if(a instanceof Array)return a[1]&&(n=a[1]),a[2]&&(r=a[2]),{width:n,height:r}}return{width:n,height:r}}},n.exports=r});