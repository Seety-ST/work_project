define("wap-huipai:global/utility/index",function(t){function n(t,n){var e="";n=n||"",e=-1!=$.inArray(n,[120,320,165,640,600,145,440,230,260])?"_"+n:"";var i=/^http:\/\/(img|image)\d*(-c|-wap|-d)?(.poco.cn.*|.yueus.com.*)\.jpg|gif|png|bmp/i,a=/_(32|64|86|100|145|165|260|320|440|468|640).(jpg|png|jpeg|gif|bmp)/i;return i.test(t)&&(t=a.test(t)?t.replace(a,e+".$2"):t.replace("/(.d*).jpg|.jpg|.gif|.png|.bmp/i",e+".jpg")),t}{var e=($(window),window.localStorage),i=t.syncLoad("wap-huipai:global/cookie/index");t.syncLoad("wap-huipai:global/ua/index")}t.syncLoad("wap-huipai:global/layer/layer");var a={timeout:1e4};$.extend($.ajaxSettings,a);var o=window.location;if(o.origin||(o.origin=o.protocol+"//"+o.hostname+(o.port?":"+o.port:"")),/wifi/.test(o.origin)){o.origin.replace("-wifi","")}else{var r=o.origin.split(".");r[0]+"-wifi."+r[1]+"."+r[2]}window.$_AppCallJSObj=$({}),window._AppCallJSFunc=function(t,n){$_AppCallJSObj.trigger(t,n)},$_AppCallJSObj.on("set_location_cookie",function(t,n){i.set("yue_location_id",n.location_id)});var l={"int":function(t){return parseInt(t,10)||0},"float":function(t){return parseFloat(t)},format_float:function(t,n){n=n||0;var e=Math.pow(10,n);return Math.round(t*e)/e||0},getHash:function(){return window.location.hash.substr(1)},get_zoom_height_by_zoom_width:function(t,n,e){return parseInt(n*e/t)},storage:{prefix:"paizhao-app-",set:function(t,n){try{return e&&"undefined"!=typeof e?"undefined"==typeof n?l.storage.remove(t):(e.setItem(l.storage.prefix+t,JSON.stringify(n)),n):!1}catch(i){return console.warn(i),!1}},get:function(t){try{var n=e.getItem(l.storage.prefix+t);return n?JSON.parse(n):n}catch(i){return console.warn(i),!1}},remove:function(t){return e.removeItem(l.storage.prefix+t)}},ajax_request:function(t){{var t=t||{},n=t.url,e=t.data||{},i=t.cache||!1,a=t.beforeSend||function(){},o=t.success||function(){},r=t.error||function(){},l=t.complete||function(){},c=t.type||"GET",u=t.dataType||"json",s=null==t.async?!0:!1,d=t.headers||{};t.timeout||1e4}if(!window.Vue){var p=$.ajax({url:n,type:c,data:e,cache:i,async:s,dataType:u,beforeSend:a,success:o,error:r,complete:l});return p=$.extend(p,{xhr_data:e})}i||(e._=(new Date).getTime()),Vue.http.options.headers=d,Vue.http.options.emulateJSON="json"==u?!0:!1,"GET"==c?Vue.http({url:n,method:c,params:e,before:a}).then(function(t){var n=t.data;o.call(this,n,t)},function(t){r.call(this,t)}):"POST"==c&&Vue.http.post(n,e,{url:n,before:a}).then(function(t){var n=t.data;o.call(this,n,t)},function(t){r.call(this,t)})},matching_img_size:function(t,e){var i=e;return n(t,i)},get_url_params:function(t,n){var e=new RegExp("[?&]"+n+"=([^&]+)","i"),i=e.exec(t);return i&&i.length>1?i[1]:""},page_pv_stat_action:function(t){},err_log:function(){},dialog:function(t){var t=t||{},n=t.buttons||["取消","确认"],e=$.dialog({title:t.title||"",content:t.content||"温馨提示内容",button:n}),i=t.no_confirm_btn||!1;return e.on("dialog:action",function(t){1==n.length?e.trigger("confirm",t):2==n.length?1==t.index?e.trigger("confirm",t):e.trigger("cancel",t):i&&e.trigger("confirm",t)}).on("dialog:hide",function(n){t.hide&&t.hide.call(this,n)}).on("dialog:show",function(n){t.show&&t.show.call(this,n)}),e},dialog_show:function(t){var t=t||{},n=t.title||"",e=t.content||"",i=Handlebars.template(function(t,n,e,i,a){function o(t,n){var i,a,o="";return o+="\n                    <h4>",(a=e.title)?i=a.call(t,{hash:{},data:n}):(a=t&&t.title,i=typeof a===u?a.call(t,{hash:{},data:n}):a),o+=s(i)+"</h4>\n                "}this.compilerInfo=[4,">= 1.0.0"],e=this.merge(e,t.helpers),a=a||{};var r,l,c="",u="function",s=this.escapeExpression,d=this;return c+='<div class="ui-dialog" data-role="ui-dialog">\n    <div class="ui-dialog-cnt">\n        <div class="ui-dialog-bd">\n            <div>\n                ',r=e["if"].call(n,n&&n.title,{hash:{},inverse:d.noop,fn:d.program(1,o,a),data:a}),(r||0===r)&&(c+=r),c+="  \n                <div>",(l=e.content)?r=l.call(n,{hash:{},data:a}):(l=n&&n.content,r=typeof l===u?l.call(n,{hash:{},data:a}):l),c+=s(r)+'</div>\n            </div>\n        </div>\n        <div class="ui-dialog-ft ui-btn-group">\n            <button type="button" data-role="ui-dialog-button"  class="select" >关闭</button> \n        </div>\n    </div>\n</div>'}),a=i({title:n,content:e});$("body").append(a);var o=$("body").find('[data-role="ui-dialog"]');return o.addClass("show"),o.find('[data-role="ui-dialog-button"]').on("click",function(t){t.preventDefault(),$(this).removeClass("show"),o.remove()}),o},loading:function(t){var n=layer.open({type:1,content:'<i class="loading"></i><p class="tc mt5">加载中</p>',shade:!1,style:"background:rgba(0,0,0,.5);text-align:center;color:#fff;",skin:"paizhao_loading"});return n},get_yue_img_size_from_url:function(t){var n="",e="",i=t.match(/\?(.*)/);if(!i)return{width:n,height:e};if(i[1]){var a=i[1],o=a.match(/(\d+)x(\d+)_(\d+)/);return o[1]&&(n=o[1]),o[2]&&(e=o[2]),{width:n,height:e}}return{width:n,height:e}},$_AppCallJSObj:$_AppCallJSObj};return window.__Util_Tools=l,l});