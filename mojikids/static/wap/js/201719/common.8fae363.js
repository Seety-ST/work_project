;/*!wap-kids:global/app/index*/
define("wap-kids:global/app/index",function(n,o,c){"use strict";var i=n.syncLoad("wap-kids:global/util/index"),l=i.ua,t=window.PocoWebViewJavascriptBridge,e="undefined"!=typeof t;e=l.is_yue_app?!0:l.is_yue_seller?!0:window.SUPE_APP&&window.SUPE_APP.isApp?!0:!1;var a="poco.yuepai.";if(e){t.init();var u=$({});window.pocoAppEventTrigger=function(){return u.trigger.apply(u,arguments)}}var r={isPaiApp:e,on:function(){return r.isPaiApp?u.on.apply(u,arguments):void 0},off:function(){return r.isPaiApp?u.off.apply(u,arguments):void 0},once:function(){return r.isPaiApp?u.once.apply(u,arguments):void 0},chat:function(n){t.callHandler(a+"function.chat",n,function(){"function"==typeof n.callback&&n.callback.call(this)})},qrcodescan:function(){return alert("Error:methods is undefined"),!0},check_login:function(n){t.callHandler(a+"info.login",{},function(o,c){n.call(this,o,c)})},login:function(n){var n=n||{};t.callHandler(a+"function.login",{pocoid:n.pocoid,username:n.username,icon:n.icon,token:n.token,token_expirein:n.token_expirein},function(){})},waplogin:function(n){var n=n||{};console.log("APP waplogin"),t.callHandler(a+"function.waplogin",n,function(n){console.log("======支付宝支付回调参数======"),console.log(n)})},logout:function(){console.log("APP logout"),t.callHandler(a+"function.logout",{},function(){})},get_chat_info:function(n){t.callHandler(a+"info.chat",{},function(o){"function"==typeof n&&n.call(this,o)})},get_login_info:function(n){t.callHandler(a+"info.login",{},function(o){"function"==typeof n&&n.call(this,o)})},alipay:function(n,o){return n?(console.log(n),void t.callHandler(a+"function.alipay",n,function(n){console.log("======支付宝支付回调参数======"),console.log(n),"function"==typeof o&&o.call(this,n)})):void alert("no params")},wxpay:function(n,o){return n?(console.log(n),void t.callHandler(a+"function.wxpay",n,function(n){console.log("======微信支付回调参数======"),console.log(n),"function"==typeof o&&o.call(this,n)})):void alert("no params")},unionpay:function(n,o){return n?(console.log(n),void t.callHandler(a+"function.unionpay",n,function(n){console.log("======银联支付回调参数======"),console.log(n),"function"==typeof o&&o.call(this,n)})):void alert("no params")},upload_img:function(n,o,c){console.log("upload img");var i="",l=o.photosize||640,e="wifi"==window.APP_NET_STATUS?"-wifi":"";console.log("net status:"+window.APP_NET_STATUS);var u="http://sendmedia-w"+e+".yueus.com:8078/",r="http://sendmedia-w"+e+".yueus.com:8079/";if(!o)return void alert("no params");if(!n)return void alert("no type");var s="",f="";switch(n){case"header_icon":i=u+"icon.cgi",s="modify_headicon",f="camera_album";break;case"single_img":i=r+"upload.cgi",f="camera_album";break;case"modify_cardcover":i=r+"upload.cgi",s="modify_cardcover",f="camera_album";break;case"multi_img":i=r+"upload.cgi",f="camera_album"}o=$.extend(o,{url:i,photosize:l,operation:s,src_opts:f}),console.log("-----upload img params-----"),console.log(o),t.callHandler(a+"function.uploadpic",o,function(n){c.call(this,n)})},show_chat_list:function(){return alert("Error:methods is undefined"),!0},debug:function(){return alert("Error:methods is undefined"),!0},show_alumn_imgs:function(n){var n=n||{};t.callHandler(a+"function.show_album_imgs",n,function(){})},get_netstatus:function(n){t.callHandler(a+"info.netstatus",{},function(o){console.log("===========调用 App 获取网络状态==========="),console.log(o),"function"==typeof n&&n.call(this,o)})},sso_login:function(n,o){t.callHandler(a+"function.bind_account",n,function(n){"function"==typeof o&&o.call(this,n)})},call_phone:function(n,o){t.callHandler(a+"function.callphone",n,function(n){"function"==typeof o&&o.call(this,n)})},get_gps:function(){return alert("Error:methods is undefined"),!0},set_setting:function(){return alert("Error:methods is undefined"),!0},get_setting:function(){return alert("Error:methods is undefined"),!0},clear_cache:function(){t.callHandler(a+"function.clearcache",{},function(){})},show_bottom_bar:function(){return alert("Error:methods is undefined"),!0},check_update:function(){return alert("Error:methods is undefined"),!0},app_back:function(){console.log("调用 App Back Function"),window.AppCanPageBack=!0,t.callHandler(a+"function.back",{},function(){})},app_info:function(n){console.log("App app-info"),t.callHandler(a+"info.app",{},function(o){console.log(o),"function"==typeof n&&n.call(this,o)})},switchtopage:function(n,o){console.log("App switchtopage"),t.callHandler(a+"function.switchtopage",n,function(n){console.log(n),"function"==typeof o&&o.call(this,n)})},share_card:function(n,o){var c=n||{};console.log("share_card请求前参数："),console.log(c),t.callHandler(a+"function.sharecard",{url:c.url,pic:c.pic||c.img,img:c.pic||c.img,content:c.content,title:c.title,sinacontent:c.sinacontent,sina_conent:c.sina_conent,userid:c.userid,qrcodeurl:c.qrcodeurl,jscbfunc:c.jscbfunc||function(){},sourceid:c.sourceid||0},function(n){console.log("share_card回调："),console.log(n),"function"==typeof o&&o.call(this,n)})},analysis:function(){return alert("Error:methods is undefined"),!0},nav_to_app_page:function(){alert("Error:methods is undefined")},openlink:function(n){return console.log("App openlink"),n?void t.callHandler(a+"function.openlink",{url:n},function(n){console.log("openlink 回调数据:"+n),"function"==typeof callback&&callback.call(this,n)}):void alert("请传入合法url")},openttpayfinish:function(n){console.log("App ttpayfinish"),t.callHandler(a+"function.ttpayfinish",{},function(o){console.log(o),"function"==typeof n&&n.call(this,o)})},close_webview:function(n){console.log("App close_webview"),t.callHandler(a+"function.close",{},function(o){console.log(o),"function"==typeof n&&n.call(this,o)})},show_selectcity:function(n,o){console.log("App selectcity"),t.callHandler(a+"function.selectcity",n,function(n){console.log(n),"function"==typeof o&&o.call(this,n)})},qrcodeshow:function(n,o,c){console.log("App qrcodeshow"),o=o||0,t.callHandler(a+"function.qrcodeshow",{qrcodes:n,index:o},function(n){console.log(n),"function"==typeof c&&c.call(this,n)})},showtopmenu:function(n,o,c){console.log("App showtopbar"),o=o||{},o.type=n?2:1,o.show_bar&&(o.type=0),t.callHandler(a+"function.showtopbar",o,function(n){console.log(n),"function"==typeof c&&c.call(this,n)})},payfinish:function(n,o){n=n||{},t.callHandler(a+"function.payfinish",n,function(n){console.log(n),"function"==typeof o&&o.call(this,n)})},open_paypage:function(n,o){n=n||{},console.log("==== open_paypage options ===="),console.log(n),t.callHandler(a+"function.pay",n,function(n){console.log(n),"function"==typeof o&&o.call(this,n)})}};c.exports=r});
;/*!wap-kids:global/util/index*/
define("wap-kids:global/util/index",function(e,n,t){"use strict";function i(e,n){var t="";n=n||"",t=-1!=$.inArray(n,[120,320,165,640,600,145,440,230,260])?"_"+n:"";var i=/^http:\/\/(img|image)\d*(-c|-wap|-d)?(.poco.cn.*|.yueus.com.*)\.jpg|gif|png|bmp/i,o=/_(32|64|86|100|145|165|260|320|440|468|640).(jpg|png|jpeg|gif|bmp)/i;return i.test(e)&&(e=o.test(e)?e.replace(o,t+".$2"):e.replace("/(.d*).jpg|.jpg|.gif|.png|.bmp/i",t+".jpg")),e}var o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},a=window.__ajax__||$.ajax,s=window.localStorage,r=function(e){return(e||"").replace(/^[\s\uFEFF]+|[\s\uFEFF]+$/g,"")},c=function(e,n){if(!e||!n)return!1;if(-1!=n.indexOf(" "))throw new Error("className should not contain space.");return e.classList?e.classList.contains(n):(" "+e.className+" ").indexOf(" "+n+" ")>-1},d=function(e,n){if(e){for(var t=e.className,i=(n||"").split(" "),o=0,a=i.length;a>o;o++){var s=i[o];s&&(e.classList?e.classList.add(s):c(e,s)||(t+=" "+s))}e.classList||(e.className=t)}},u=function(e,n){if(e&&n){for(var t=n.split(" "),i=" "+e.className+" ",o=0,a=t.length;a>o;o++){var s=t[o];s&&(e.classList?e.classList.remove(s):c(e,s)&&(i=i.replace(" "+s+" "," ")))}e.classList||(e.className=r(i))}},p=function(){return document.addEventListener?function(e,n,t){e&&n&&t&&e.addEventListener(n,t,!1)}:function(e,n,t){e&&n&&t&&e.attachEvent("on"+n,t)}}(),f=function(){return document.removeEventListener?function(e,n,t){e&&n&&e.removeEventListener(n,t,!1)}:function(e,n,t){e&&n&&e.detachEvent("on"+n,t)}}(),l=function(e,n,t){var i=function o(){t&&t.apply(this,arguments),f(e,n,o)};p(e,n,i)},g=function(){function e(e){return encodeURIComponent(e)}function n(e){return decodeURIComponent(e.replace(/\+/g," "))}function t(e){return"string"==typeof e&&""!==e}var i=window.document,o=864e5;return function(){var a={get:function(e,o){o=o||{};var a,s;return t(e)&&(s=String(i.cookie).match(new RegExp("(?:^| )"+e+"(?:(?:=([^;]*))|;|$)")))&&s[1]&&(a=o.decode?n(s[1]):s[1]),a},set:function(n,a,s){s=s||{};var r=String(s.encode?e(a):a),c=s.expires,d=s.domain||yue_domain,u=s.path||yue_path,p=s.secure;return"number"==typeof c&&(c=new Date,c.setTime(c.getTime()+s.expires*o)),c instanceof Date&&(r+="; expires="+c.toUTCString()),t(d)&&(r+="; domain="+d),t(u)&&(r+="; path="+u),p&&(r+="; secure"),i.cookie=n+"="+r,r},del:function(e,n){return this.set(e,"",{expires:-1,domain:n.domain||yue_domain,path:n.path||yue_path,secure:n.secure})}};return a}},m={prefix:"mojikids-app-",set:function(e,n){try{return s&&"undefined"!=typeof s?"undefined"==typeof n?s.remove(e):(s.setItem("mojikids-app-"+e,JSON.stringify(n)),n):!1}catch(t){return console.warn(t),!1}},get:function(e){try{var n=s.getItem("mojikids-app-"+e);return n?JSON.parse(n):n}catch(t){return console.warn(t),!1}},remove:function(e){return s.removeItem("mojikids-app-"+e)}},y=function(e){{var e=e||{},n=e.url,t=e.data||{},i=e.cache||!1,o=e.beforeSend||function(){},s=e.success||function(){},r=e.error||function(){},c=e.complete||function(){},d=e.type||"GET",u=e.dataType||"json",p=null==e.async?!0:!1;e.headers||{},e.timeout||1e4}i||(t._=(new Date).getTime()),console.info("ajax request params:",t);var f=a({url:n,type:d,data:t,cache:i,async:p,dataType:u,beforeSend:o,success:s,error:r,complete:c});return f},v=function(){var e={},n=window,t=n.navigator,i=t.appVersion;if(e.isMobile=/(iphone|ipod|android|ios|ipad|nokia|blackberry|tablet|symbian)/.test(t.userAgent.toLowerCase()),e.isAndroid=/android/gi.test(i),e.isIDevice=/iphone|ipad/gi.test(i),e.isTouchPad=/hp-tablet/gi.test(i),e.isIpad=/ipad/gi.test(i),e.otherPhone=!(e.isAndroid||e.isIDevice),e.is_uc=/uc/gi.test(i),e.is_chrome=/CriOS/gi.test(i)||/Chrome/gi.test(i),e.is_qq=/QQBrowser/gi.test(i),e.is_real_safari=/safari/gi.test(i)&&!e.is_chrome&&!e.is_qq,e.is_standalone=window.navigator.standalone?!0:!1,e.isAndroid){var o=parseFloat(i.slice(i.indexOf("Android")+8));e.android_version=o}else if(e.isIDevice){var a=i.match(/OS (\d+)_(\d+)_?(\d+)?/),s=!1;if(a){var s=a[1];a[2]&&(s+="."+a[2]),a[3]&&(s+="."+a[3])}else s=!1;e.ios_version=s}return e.is_iphone_safari_no_fullscreen=e.isIDevice&&e.ios_version<"7"&&!e.isIpad&&e.is_real_safari&&!e.is_standalone,e.is_yue_app=/yue_pai/.test(i),e.is_yue_seller=/yueseller/.test(i),e.is_weixin=/MicroMessenger/gi.test(i),e.is_normal_wap=!e.is_yue_app&&!e.is_weixin,e},_=function(e,n,t){e=e||{};var i,a="undefined"==typeof n?"undefined":o(n),s=1;for(("undefined"===a||"boolean"===a)&&(t="boolean"===a?n:!1,n=e,e=this),"object"!==("undefined"==typeof n?"undefined":o(n))&&"[object Function]"!==Object.prototype.toString.call(n)&&(n={});2>=s;){if(i=1===s?e:n,null!=i)for(var r in i){var c=e[r],d=i[r];e!==d&&(t&&d&&"object"===("undefined"==typeof d?"undefined":o(d))&&!d.nodeType?e[r]=this.extend(c||(null!=d.length?[]:{}),d,t):void 0!==d&&(e[r]=d))}s++}return e},h=e.syncLoad("wap-kids:widget/toast/index"),w=e.syncLoad("wap-kids:widget/dialog/index"),b=e.syncLoad("wap-kids:widget/loading/index");window.Vue&&(Vue.$toast=Vue.prototype.$toast=h,Vue.$dialog=Vue.prototype.$dialog=w,Vue.$loading=Vue.prototype.$loading=b),t.exports={on:p,off:f,once:l,hasClass:c,addClass:d,removeClass:u,cookie:g,storage:m,request:y,ua:v(),matching_img_size:i,toast:Vue.$toast,dialog:Vue.$dialog,loading:Vue.$loading,extend:_}});
;/*!wap-kids:global/wxsdk/index*/
define("wap-kids:global/wxsdk/index",function(n,o,c){"use strict";var e=n.syncLoad("wap-kids:global/util/index"),s={version:"default"};s.isWeiXin=function(){return/MicroMessenger/i.test(navigator.userAgent)},s.chooseImage=function(n){wx.chooseImage({count:n.count,sizeType:["original","compressed"],sourceType:["album","camera"],success:function(o){n.success&&"function"==typeof n.success&&n.success(o)},cancel:function(o){n.cancel&&"function"==typeof n.cancel&&n.cancel(o)},fail:function(o){n.fail&&"function"==typeof n.fail&&n.fail(o)}})},s.upload_imgs=function(n){function o(n){wx.uploadImage({localId:n.localId[c],isShowProgressTips:s,success:function(s){var t={localId:n.localId[c],media_id:s.serverId};i.push(t),n.success_single&&"function"==typeof n.success_single&&n.success_single(s,c,e),c++,c>=n.localId.length?n.success_all&&"function"==typeof n.success_all&&n.success_all(i,c,e):o(n)},cancel:function(o){n.cancel_single&&"function"==typeof n.cancel_single&&n.cancel_single(o,c,e)},fail:function(o){n.fail_single&&"function"==typeof n.fail_single&&n.fail_single(o,c,e)}})}var c=0,e=n.localId.length,s=n.isShowProgressTips||1;o(n);var i=[]},s.downloadImage=function(n){if(!n.media_id)throw"serverId/media_Id error!";wx.downloadImage({serverId:n.media_id,isShowProgressTips:n.isShowProgressTips||1,success:function(o){o.localId;n.success&&"function"==typeof n.success&&n.success(o)},cancel:function(o){n.cancel&&"function"==typeof n.cancel&&n.cancel(o)},fail:function(o){n.fail&&"function"==typeof n.fail&&n.fail(o)}})},s.chooseImages_and_uploadImages_and_downloadImages=function(n){var o=n.upload_type||"pics",c=n.$el||$("body"),i=n.choose_trigger_str||"chooseImages",t=parseInt(n.choose_count)||9,l=n.choose_success||function(){},u=n.choose_cancel||function(){},a=n.choose_fail||function(){},f=n.choose_success_change_count||t,r=n.upload_trigger_str||"uploadImages",g=n.upload_success||function(){},_=n.upload_cancel||function(){},d=n.upload_fail||function(){},p=n.upload_success_all||function(){},h=n.get_trigger_str||"getImagesUrl",m=n.get_imgUrl_beforeSend||function(){},y=n.get_imgUrl_success||function(){},I=n.get_imgUrl_error||function(){},w=n.get_imgUrl_complete||function(){},v=[],x=[];return c.on(i,function(){var n=[];wx.chooseImage({count:t,success:function(o){n=o.localIds,console.log(o),v=n,l&&"function"==typeof l&&l(o),f&&"function"==typeof f&&(t=f.call(this,o))},cancel:function(n){u&&"function"==typeof u&&u(n)},fail:function(n){a&&"function"==typeof a&&a(n)}})}),c.on(r,function(){0!=v.length&&s.upload_imgs({localId:v,success_single:function(n,o){n.serverId;g&&"function"==typeof g&&g(n,o,v.length)},cancel_single:function(n,o){_&&"function"==typeof _&&_(n,o,v.length)},fail_single:function(n,o){d&&"function"==typeof d&&d(n,o,v.length)},success_all:function(n,o){x=n,console.log("mdeia_list"),console.log(x),p&&"function"==typeof p&&p(n,o,v.length)}})}),c.on(h,function(){if(0!=x.length){if(!window.$__ajax_domain)return void alert("mojikids:fail ajax url!");e.request({url:window.$__ajax_domain+"wx_image.php",data:{obj_list:x,upload_type:o},dataType:"JSON",type:"POST",cache:!1,beforeSend:function(){m&&"function"==typeof m&&m()},success:function(n){y&&"function"==typeof y&&y(n)},error:function(n){I&&"function"==typeof I&&I(n)},complete:function(){w&&"function"==typeof w&&w()}})}}),this.set_choose_count=function(n){t=n},this},c.exports=s});