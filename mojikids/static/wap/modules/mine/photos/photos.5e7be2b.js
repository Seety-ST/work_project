define('wap-kids:mine/photos/photos', function(require, exports, module) {

  "use strict";function in_array(t,i){for(var e=i.length,n=(Object.prototype.toString,0);e>n;n++)if(i[n]==t)return!0;return!1}var Util=require.syncLoad("wap-kids:global/util/index"),Lazyload=require.syncLoad("wap-kids:widget/lazyload/index");Array.prototype.removeByValue=function(t){for(var i=0;i<this.length;i++)if(this[i]==t){this.splice(i,1);break}},exports.init=function(t){return{el:"#photos-page",data:{mode:"view",count:1,preview:!1,preview_img:"",zzhb_list:[],wysq_list:[],selected_list:[],title:"我的照片",show_tips:!0,order_sn:t.order_sn,description:"",list:[]},mounted:function(){var t=this;t.reset(),t.get_list(),Vue.use(Lazyload),window.onresize=function(){t.init_height()},window.onhashchange=function(){var i=window.location.hash.substr(1);switch(i){case"":t.reset(),t.preview=!1}}},methods:{init_height:function(){for(var t=this,i=window.innerWidth,e=(i-12)/3+"px",n={height:e,width:e},s=0;s<t.list.length;s++)t.list[s].style=n},change_img_resize:function(t){return Util.matching_img_size_v2(t,"s260")},show_circle:function(t){var i=this,e=!1;return"zzhb"!=i.mode||t.selected?"wysq"!=i.mode||t.selected||0!=t.is_up||(e=!0):e=!0,e},show_fade:function(t){var i=this,e=!1;return"zzhb"==i.mode&&t.selected?e=!0:"wysq"!=i.mode||!t.selected&&1!=t.is_up||(e=!0),e},show_select:function(t){var i=this,e=!1;return"zzhb"==i.mode&&t.selected?e=!0:"wysq"==i.mode&&t.selected&&(e=!0),e},get_list:function(t){var i=this;t=t||function(){},Util.request({url:window.__MOJIKIDS_GLOBAL.AJAX_DOMAIN+"/mine/get_order_showcase.php",data:{order_sn:i.order_sn},success:function(e){e.res.data;i.list=e.res.data.list,i.description=e.res.data.description,i.init_height(),"function"==typeof t&&t.call(this)},error:function(){Util.toast({message:"网络异常",duration:2e3})}})},submit_wysq:function(t){var i=this;if(0==t.length)return void Util.toast({message:"请选择照片",duration:2e3});if(t.length>30)return void Util.toast({message:"上墙照片数不能超过30张",duration:2e3});var e=t.join(",");Util.request({url:window.__MOJIKIDS_GLOBAL.AJAX_DOMAIN+"/mine/submit_wysq.php",data:{image_ids:e},beforeSend:function(){Util.loading.open({text:"正在提交..."})},success:function(t){var e=t.res.data;e.result>0?(Util.toast({message:"谢谢~(≧▽≦)/~",duration:2e3}),i.get_list(function(){i.reset(),window.history.back()})):Util.toast({message:e.message,duration:2e3})},error:function(){Util.toast({message:"网络异常",duration:2e3})},complete:function(){Util.loading.close()}})},show_big_img:function(t){var i=this;i.preview=!0,i.preview_img="",window.location.hash="#preview";var e=new Image;e.src=t,Util.loading.open("图片加载中..."),e.onload=function(){i.preview_img=t,Util.loading.close()},e.onerror=function(){Util.toast("图片加载失败"),Util.loading.close()}},select_item:function(t){var i=this;if("zzhb"!=i.mode&&"wysq"!=i.mode)return void i.show_big_img(i.list[t].url);if(i.preview=!1,i.list[t]&&0==i.list[t].selected)if("zzhb"==i.mode){if(i.selected_list.length>9)return void Util.toast({message:"制作海报数量不能超过9张",duration:2e3});i.list[t].selected=!0,i.add(i.list[t])}else"wysq"==i.mode&&(0==i.list[t].is_up?(i.list[t].selected=!0,i.add(i.list[t])):Util.toast({message:"图片已经上墙",duration:2e3}));else i.list[t].selected=!1,i.minus(i.list[t],t)},add:function(t){var i=this;in_array(t.images,i.selected_list)||(t.num=i.selected_list.length+1,i.selected_list.push(t.image_id))},minus:function(t){var i=this,e=t.num;i.selected_list.removeByValue(t.image_id);for(var n=0;n<i.list.length;n++)i.list[n].num&&i.list[n].num>e&&i.list[n].num--},wysq:function(){var t=this;return t.mode="wysq",t.preview?(t.preview=!1,void window.history.back()):(window.location.hash=t.mode,void(t.selected_list.length>0&&Util.dialog({title:"谢谢您的分享！",message:"提交上墙照片后，该照片有可能被展示在MOJIKIDS官方宣传渠道",showCancelButton:!0,confirmButtonText:"确认上墙",cancelButtonText:"取消上墙"},function(i){"confirm"==i&&t.submit_wysq(t.selected_list)})))},zzhb:function(){var t=this;return t.preview?(t.preview=!1,void window.history.back()):(t.mode="zzhb",window.location.hash=t.mode,t.selected_list.length>9?void Util.toast({message:"制作海报数量不能超过9张",duration:2e3}):void(t.selected_list.length>0&&(Util.loading.open({text:"正在制作海报...",timeout:2e3}),setTimeout(function(){Util.loading.close();var i=t.selected_list.join(",");window.location.href=window.__MOJIKIDS_GLOBAL.PAGE_URL.bb+"?image_ids="+i},2e3))))},reset:function(){for(var t=this,i=0;i<t.list.length;i++)t.list[i].selected=!1,t.list[i].num=0;t.mode="view",t.selected_list=[]},qx:function(){var t=this;t.reset(),window.history.back()}}}},function(t){module&&module.exports&&(module.exports.template=t),exports&&exports.default&&(exports.default.template=t)}('<section class="photos-page">\n    <div class="top-bar pl15 pr15" v-show="show_tips">\n        <span>{{description}}</span>\n        <i class="delete" @click="show_tips = false"></i>\n    </div>\n\n    <div class="list-wrapper" v-show="!preview">\n        <ul class="container clearfix">\n            <template v-for="(item,index) in list">\n                <li class="item" :style="item.style">\n                    <div class="child">\n                        <img v-lazy.container="change_img_resize(item.url)">\n                        <div class="extend" @click="select_item(index)">\n                            <div class="fade-mix" v-show="show_fade(item)"></div>\n                            <span class="circle" v-show="show_select(item)">\n                                <em>{{item.num}}</em>\n                            </span>\n                            <div class="none" v-show="show_circle(item)">\n                                <div class="big"></div>\n                                <div class="mid"></div>\n                            </div>\n                        </div>\n                    </div>\n                </li>\n            </template>\n        </ul>\n    </div>\n\n    <div class="preview" v-show="preview">\n        <img :src="preview_img" class="w-100">\n    </div>\n\n    <div class="ui-button-submit-m f16 ui-border-t main-bg footer-bar" v-show="mode == \'view\'">\n        <div class="lbox type-full">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="zzhb">\n                <i class="icon-zzhb"></i>\n                <span>制作海报</span>\n            </button>\n        </div>\n        <div class="line">\n\n        </div>\n        <div class="rbox type-full">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="wysq"><i class="icon-wysq"></i><span>我要上墙</span>\n            </button>\n        </div>\n    </div>\n\n    <div class="ui-button-submit-m f16 ui-border-t main-bg footer-bar" v-show="mode == \'wysq\'">\n        <div class="lbox type-full cancel">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="qx">取消</button>\n        </div>\n        <div class="rbox type-full">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="wysq"><i class="icon-wysq"></i><span>我要上墙</span>\n            </button>\n        </div>\n    </div>\n\n    <div class="ui-button-submit-m f16 ui-border-t main-bg footer-bar" v-show="mode == \'zzhb\'">\n        <div class="lbox type-full cancel">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="qx">取消</button>\n        </div>\n        <div class="rbox type-full">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="zzhb">\n                <i class="icon-zzhb"></i>\n                <span>制作海报</span>\n            </button>\n        </div>\n    </div>\n</section>');

});