define("pc-huipai:global/utility/index",function(t){function n(t,n){var e="";n=n||"",e=-1!=$.inArray(n,[120,320,165,640,600,145,440,230,260])?"_"+n:"";var i=/^http:\/\/(img|image)\d*(-c|-wap|-d)?(.poco.cn.*|.yueus.com.*)\.jpg|gif|png|bmp/i,o=/_(32|64|86|100|145|165|260|320|440|468|640).(jpg|png|jpeg|gif|bmp)/i;return i.test(t)&&(t=o.test(t)?t.replace(o,e+".$2"):t.replace("/(.d*).jpg|.jpg|.gif|.png|.bmp/i",e+".jpg")),t}var e=($(window),window.localStorage),i=t("pc-huipai:global/cookie/index"),o=(t("pc-huipai:global/ua/index"),{timeout:1e4});$.extend($.ajaxSettings,o);var a=window.location;if(a.origin||(a.origin=a.protocol+"//"+a.hostname+(a.port?":"+a.port:"")),/wifi/.test(a.origin)){a.origin.replace("-wifi","")}else{var r=a.origin.split(".");r[0]+"-wifi."+r[1]+"."+r[2]}window.$_AppCallJSObj=$({}),window._AppCallJSFunc=function(t,n){$_AppCallJSObj.trigger(t,n)},$_AppCallJSObj.on("set_location_cookie",function(t,n){i.set("yue_location_id",n.location_id)});var l={"int":function(t){return parseInt(t,10)||0},"float":function(t){return parseFloat(t)},format_float:function(t,n){n=n||0;var e=Math.pow(10,n);return Math.round(t*e)/e||0},getHash:function(){return window.location.hash.substr(1)},get_zoom_height_by_zoom_width:function(t,n,e){return parseInt(n*e/t)},storage:{prefix:"yypay-app-",set:function(t,n){try{return e&&"undefined"!=typeof e?"undefined"==typeof n?l.storage.remove(t):(e.setItem(l.storage.prefix+t,JSON.stringify(n)),n):!1}catch(i){return console.warn(i),!1}},get:function(t){try{var n=e.getItem(l.storage.prefix+t);return n?JSON.parse(n):n}catch(i){return console.warn(i),!1}},remove:function(t){return e.removeItem(l.storage.prefix+t)}},ajax_request:function(t){{var t=t||{},n=t.url,e=t.data||{},i=t.cache||!1,o=t.beforeSend||function(){},a=t.success||function(){},r=t.error||function(){},l=t.complete||function(){},c=t.type||"GET",u=t.dataType||"json",s=null==t.async?!0:!1,d=t.headers||{};t.timeout||1e4}if(!window.Vue){var p=$.ajax({url:n,type:c,data:e,cache:i,async:s,dataType:u,beforeSend:o,success:a,error:r,complete:l});return p=$.extend(p,{xhr_data:e})}i||(e._=(new Date).getTime()),Vue.http.options.headers=d,Vue.http.options.emulateJSON="json"==u?!0:!1,"GET"==c?Vue.http({url:n,method:c,params:e,before:o}).then(function(t){var n=t.data;a.call(this,n,t)},function(t){r.call(this,t)}):"POST"==c&&Vue.http.post(n,e,{url:n,before:o}).then(function(t){var n=t.data;a.call(this,n,t)},function(t){r.call(this,t)})},matching_img_size:function(t,e){var i=e;return n(t,i)},get_url_params:function(t,n){var e=new RegExp("[?&]"+n+"=([^&]+)","i"),i=e.exec(t);return i&&i.length>1?i[1]:""},page_pv_stat_action:function(t){},err_log:function(){},dialog:function(t){var t=t||{},n=t.buttons||["取消","确认"],e=$.dialog({title:t.title||"",content:t.content||"温馨提示内容",button:n}),i=t.no_confirm_btn||!1;return e.on("dialog:action",function(t){1==n.length?e.trigger("confirm",t):2==n.length?1==t.index?e.trigger("confirm",t):e.trigger("cancel",t):i&&e.trigger("confirm",t)}).on("dialog:hide",function(n){t.hide&&t.hide.call(this,n)}).on("dialog:show",function(n){t.show&&t.show.call(this,n)}),e},dialog_show:function(t){var t=t||{},n=t.title||"",e=t.content||"",i=Handlebars.template(function(t,n,e,i,o){function a(t,n){var i,o,a="";return a+="\n                    <h4>",(o=e.title)?i=o.call(t,{hash:{},data:n}):(o=t&&t.title,i=typeof o===u?o.call(t,{hash:{},data:n}):o),a+=s(i)+"</h4>\n                "}this.compilerInfo=[4,">= 1.0.0"],e=this.merge(e,t.helpers),o=o||{};var r,l,c="",u="function",s=this.escapeExpression,d=this;return c+='<div class="ui-dialog" data-role="ui-dialog">\n    <div class="ui-dialog-cnt">\n        <div class="ui-dialog-bd">\n            <div>\n                ',r=e["if"].call(n,n&&n.title,{hash:{},inverse:d.noop,fn:d.program(1,a,o),data:o}),(r||0===r)&&(c+=r),c+="  \n                <div>",(l=e.content)?r=l.call(n,{hash:{},data:o}):(l=n&&n.content,r=typeof l===u?l.call(n,{hash:{},data:o}):l),c+=s(r)+'</div>\n            </div>\n        </div>\n        <div class="ui-dialog-ft ui-btn-group">\n            <button type="button" data-role="ui-dialog-button"  class="select" >关闭</button> \n        </div>\n    </div>\n</div>'}),o=i({title:n,content:e});$("body").append(o);var a=$("body").find('[data-role="ui-dialog"]');return a.addClass("show"),a.find('[data-role="ui-dialog-button"]').on("click",function(t){t.preventDefault(),$(this).removeClass("show"),a.remove()}),a},get_yue_img_size_from_url:function(t){var n="",e="",i=t.match(/\?(.*)/);if(!i)return{width:n,height:e};if(i[1]){var o=i[1],a=o.match(/(\d+)x(\d+)_(\d+)/);return a[1]&&(n=a[1]),a[2]&&(e=a[2]),{width:n,height:e}}return{width:n,height:e}},$_AppCallJSObj:$_AppCallJSObj};return window.__Util_Tools=l,l});