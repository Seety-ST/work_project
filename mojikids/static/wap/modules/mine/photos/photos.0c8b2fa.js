define('wap-kids:mine/photos/photos', function(require, exports, module) {

  "use strict";function in_array(t,e){for(var i=e.length,s=(Object.prototype.toString,0);i>s;s++)if(e[s]==t)return!0;return!1}var Util=require.syncLoad("wap-kids:global/util/index"),Lazyload=require.syncLoad("wap-kids:widget/lazyload/index"),Swipe=require("wap-kids:widget/swipe/index");Vue.use(Swipe),Array.prototype.removeByValue=function(t){for(var e=0;e<this.length;e++)if(this[e]==t){this.splice(e,1);break}},exports.init=function(t){return{el:"#photos-page",data:{mode:"view",count:1,preview:!1,preview_img:"",zzhb_list:[],wysq_list:[],selected_list:[],title:"我的照片",show_tips:!0,hide_bottom:"",order_sn:t.order_sn,description:"",swiperOption:{pagination:"",prevButton:"",nextButton:"",slidesPerView:1,paginationClickable:!0,spaceBetween:30,mousewheelControl:!0},list:[]},mounted:function(){var t=this;t.reset(),t.get_list(),Vue.use(Lazyload),window.onresize=function(){t.init_height()},window.onhashchange=function(){var e=window.location.hash.substr(1);switch(e){case"":t.reset(),t.preview=!1}},t.swiperOption.onSlideChangeEnd=function(e){var i=e.activeIndex;if(t.list[i].url){var s=t.list[i].url;t.selected_list=[],t.selected_list.push(t.get_id_by_url(s))}}},methods:{init_height:function(){for(var t=this,e=window.innerWidth,i=(e-12)/3+"px",s={height:i,width:i},n=0;n<t.list.length;n++)t.list[n].style=s},change_img_resize:function(t,e){return Util.matching_img_size_v2(t,e)},show_circle:function(t){var e=this,i=!1;return"zzhb"!=e.mode||t.selected?"wysq"!=e.mode||t.selected||0!=t.is_up||(i=!0):i=!0,i},show_fade:function(t){var e=this,i=!1;return"zzhb"==e.mode&&t.selected?i=!0:"wysq"!=e.mode||!t.selected&&1!=t.is_up||(i=!0),i},hide_bottom_bar:function(){var t=this;t.hide_bottom=t.hide_bottom?"":"fn-hide"},show_select:function(t){var e=this,i=!1;return"zzhb"==e.mode&&t.selected?i=!0:"wysq"==e.mode&&t.selected&&(i=!0),i},get_list:function(t){var e=this;t=t||function(){},Util.request({url:window.__MOJIKIDS_GLOBAL.AJAX_DOMAIN+"/mine/get_order_showcase.php",data:{order_sn:e.order_sn},success:function(i){i.res.data;e.list=i.res.data.list,e.description=i.res.data.description,e.init_height(),"function"==typeof t&&t.call(this)},error:function(){Util.toast({message:"网络异常",duration:2e3})}})},submit_wysq:function(t){var e=this;if(0==t.length)return void Util.toast({message:"请选择照片",duration:2e3});if(t.length>30)return void Util.toast({message:"上墙照片数不能超过30张",duration:2e3});var i=t.join(",");Util.request({url:window.__MOJIKIDS_GLOBAL.AJAX_DOMAIN+"/mine/submit_wysq.php",data:{image_ids:i},beforeSend:function(){Util.loading.open({text:"正在提交..."})},success:function(t){var i=t.res.data;i.result>0?(Util.toast({message:"谢谢~(≧▽≦)/~",duration:2e3}),e.get_list(function(){e.reset(),window.history.back()})):Util.toast({message:i.message,duration:2e3})},error:function(){Util.toast({message:"网络异常",duration:2e3})},complete:function(){Util.loading.close()}})},show_big_img:function(t){var e=this;e.preview=!0,e.preview_img="",e.selected_list=[],window.location.hash="#preview";var i=e.get_cur_index(t);e.$refs.swiperCom.swiper.slideTo(i),e.selected_list.push(e.get_id_by_url(t))},get_item_by_id:function(t){for(var e=this,i=null,s=0;s<e.list.length;s++)if(e.list[s].image_id&&e.list[s].image_id==t){i=e.list[s];break}return i},get_id_by_url:function(t){for(var e=this,i=0,s=0;s<e.list.length;s++)if(e.list[s].url&&e.list[s].url==t){i=e.list[s].image_id;break}return i},get_cur_index:function(t){for(var e=this,i=0,s=0;s<e.list.length;s++)if(e.list[s].url&&e.list[s].url==t){i=s;break}return i},select_item:function(t){var e=this;if("zzhb"!=e.mode&&"wysq"!=e.mode)return void e.show_big_img(e.list[t].url);if(e.preview=!1,e.list[t]&&0==e.list[t].selected)if("zzhb"==e.mode){if(e.selected_list.length>9)return void Util.toast({message:"制作海报数量不能超过9张",duration:2e3});e.list[t].selected=!0,e.add(e.list[t])}else"wysq"==e.mode&&(0==e.list[t].is_up?(e.list[t].selected=!0,e.add(e.list[t])):Util.toast({message:"图片已经上墙",duration:2e3}));else e.list[t].selected=!1,e.minus(e.list[t],t)},add:function(t){var e=this;in_array(t.images,e.selected_list)||(t.num=e.selected_list.length+1,e.selected_list.push(t.image_id))},minus:function(t){var e=this,i=t.num;e.selected_list.removeByValue(t.image_id);for(var s=0;s<e.list.length;s++)e.list[s].num&&e.list[s].num>i&&e.list[s].num--},wysq:function(){var t=this;if(t.preview){if(1==t.selected_list.length){var e=t.get_item_by_id(t.selected_list[0]);if(e&&1==e.is_up)return void Util.toast({message:"图片已经上墙",duration:2e3})}t.preview=!1}else t.mode="wysq",window.location.hash=t.mode;t.selected_list.length>0&&Util.dialog({title:"谢谢您的分享！",message:"提交上墙照片后，该照片有可能被展示在MOJIKIDS官方宣传渠道",showCancelButton:!0,confirmButtonText:"确认上墙",cancelButtonText:"取消上墙"},function(e){"confirm"==e&&t.submit_wysq(t.selected_list)})},zzhb:function(){var t=this;return t.preview?t.preview=!1:(t.mode="zzhb",window.location.hash=t.mode),t.selected_list.length>9?void Util.toast({message:"制作海报数量不能超过9张",duration:2e3}):void(t.selected_list.length>0&&(Util.loading.open({text:"正在制作海报...",timeout:2e3}),setTimeout(function(){Util.loading.close();var e=t.selected_list.join(",");window.location.href=window.__MOJIKIDS_GLOBAL.PAGE_URL.bb+"?image_ids="+e},2e3)))},reset:function(){for(var t=this,e=0;e<t.list.length;e++)t.list[e].selected=!1,t.list[e].num=0;t.mode="view",t.selected_list=[]},qx:function(){var t=this;t.reset(),window.history.back()}}}},function(t){module&&module.exports&&(module.exports.template=t),exports&&exports.default&&(exports.default.template=t)}('<section class="photos-page">\n    <div class="list-wrapper" v-show="!preview">\n        <div class="top-bar pl15 pr15" v-show="show_tips">\n            <span>{{description}}</span>\n            <i class="delete" @click="show_tips = false"></i>\n        </div>\n        <ul class="container clearfix">\n            <template v-for="(item,index) in list">\n                <li class="item" :style="item.style">\n                    <div class="child">\n                        <img v-lazy.container="change_img_resize(item.url,\'s260\')">\n                        <div class="extend" @click="select_item(index)">\n                            <div class="fade-mix" v-show="show_fade(item)"></div>\n                            <span class="circle" v-show="show_select(item)">\n                                <em>{{item.num}}</em>\n                            </span>\n                            <div class="none" v-show="show_circle(item)">\n                                <div class="big"></div>\n                                <div class="mid"></div>\n                            </div>\n                        </div>\n                    </div>\n                </li>\n            </template>\n        </ul>\n    </div>\n\n    <div class="preview" v-show="preview">\n        <!-- 如果你后续需要找到当前实例化后的swiper对象以对其进行一些操作的话，可以自定义配置一个ref属性 -->\n        <swiper :options="swiperOption" ref="swiperCom" class="swiper-box">\n            <!-- 幻灯内容 -->\n            <swiper-slide v-for="item in list"><img @click="hide_bottom_bar" v-bind:src="change_img_resize(item.url,\'\')" alt="" class="w-100"></swiper-slide>\n        </swiper>\n    </div>\n\n    <div class="ui-button-submit-m f16 ui-border-t main-bg footer-bar" :class="hide_bottom" v-show="mode == \'view\'">\n        <div class="lbox type-full">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="zzhb">\n                <i class="icon-zzhb"></i>\n                <span>制作海报</span>\n            </button>\n        </div>\n        <div class="line">\n\n        </div>\n        <div class="rbox type-full">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="wysq"><i class="icon-wysq"></i><span>我要上墙</span>\n            </button>\n        </div>\n    </div>\n\n    <div class="ui-button-submit-m f16 ui-border-t main-bg footer-bar" v-show="mode == \'wysq\'">\n        <div class="lbox type-full cancel">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="qx">取消</button>\n        </div>\n        <div class="rbox type-full">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="wysq"><i class="icon-wysq"></i><span>我要上墙</span>\n            </button>\n        </div>\n    </div>\n\n    <div class="ui-button-submit-m f16 ui-border-t main-bg footer-bar" v-show="mode == \'zzhb\'">\n        <div class="lbox type-full cancel">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="qx">取消</button>\n        </div>\n        <div class="rbox type-full">\n            <button type="button" name="button" class="ui-button  ui-button-block ui-button-100per " @click="zzhb">\n                <i class="icon-zzhb"></i>\n                <span>制作海报</span>\n            </button>\n        </div>\n    </div>\n</section>');

});
