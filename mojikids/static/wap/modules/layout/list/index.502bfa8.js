define("wap-kids:layout/list/index",function(t,a){"use strict";function e(t){for(;1===t.nodeType&&"BODY"!==t.tagName&&"HTML"!==t.tagName;){var a=getComputedStyle(t).overflowY;if("scroll"===a||"auto"===a)return t;t=t.parentNode}return window}var o=t.syncLoad("wap-kids:global/util/index");a.init=function(t){if(t=t||{},!t.template)return void console.error("缺少 options.template");if(!t.data||"function"!=typeof t.data)return void console.error("缺少 options.data 或 类型不为function");var a=['<div class="moji-list-wrapper">','    <div class="container">',t.template,"        ","    </div>",'    <div class="bottom pt10 pb10 tc color-666">','      <div v-show="data.list.length>0" >{{data.tips}}</div>   ',"    </div>",'    <div @click="reload" v-show="data.list.length == 0 && !data.has_next_page" class="no-data-type1 color-666 pt10 pb10 tc">',"        暂无数据，点击我再刷新一下","        ","    </div>","</div>"].join(""),n={template:a,data:function(){var a=t.data();return a.data.tips=t.data.tips||"努力加载中...",a},methods:{action:function(){var a=this;o.request({url:t.url,data:a.$data.ajax_params||{},beforeSend:function(){a.$emit("beforeSend",{})},success:function(t){try{"undefined"==typeof t.res&&(t.res=t.result_data,delete t.result_data)}catch(e){console.warn("you should change the response parent field")}var o=t.res,n=o.list;a.$data.ajax_params.page=o.page,a.$data.data.has_next_page=o.has_next_page,1==o.page?a.$data.data.list=n:o.page>1&&(a.$data.data.list=a.$data.data.list.concat(n)),a.$data.data.has_next_page||(a.$data.data.tips=""),a.$emit("success",t)},error:function(t){o.toast({message:"网络异常",position:"middle",duration:2e3}),console.error(t),a.$emit("error",t)},complete:function(){a.$emit("complete",!0)}})},refresh:function(){var t=this;t.$data.ajax_params.page=1,t.action()},load_more:function(){var t=this;t.$data.data.has_next_page&&(t.$data.ajax_params.page++,t.action())},scroll:function(){var t=this;if(1===t.$el.nodeType){var a=(t.vm,t.target=e(t.$el));t.scroll_listenner=function(){if(t.$el.clientHeight)if(a==window){var e=Math.max(window.pageYOffset||0,document.body.scrollTop);document.documentElement.clientHeight+e>=document.documentElement.scrollHeight&&t.load_more()}else a.clientHeight+a.scrollTop>=a.scrollHeight&&t.load_more()},a.addEventListener("scroll",t.scroll_listenner)}},reload:function(){var t=this;t.refresh(),o.loading.open({text:"努力加载中...",timeout:2e3})}},events:{"list-com:refresh":function(t){var a=this;t&&(a.$data.ajax_params=t),a.refresh()}},mounted:function(){var t=this;t.scroll()}};n.methods=o.extend(n.methods,t.methods);var i=Vue.extend(n);return i}});