define('wap-kids:widget/go_top/go_top', function(require, exports, module) {

  "use strict";Object.defineProperty(exports,"__esModule",{value:!0}),exports.default={name:"go-top",data:function(){return{show:!1,scrolled:0}},created:function(){window.addEventListener("scroll",this.handleScroll)},destroyed:function(){window.removeEventListener("scroll",this.handleScroll)},watch:{scrolled:function(){}},methods:{go_top:function(){document.documentElement.scrollTop=document.body.scrollTop=0},handleScroll:function(){this.scrolled=document.documentElement.scrollTop||document.body.scrollTop,this.show=this.scrolled>100?!0:!1}}},function(o){module&&module.exports&&(module.exports.template=o),exports&&exports.default&&(exports.default.template=o)}('<div class="go-top-mod" v-show="show" @click="go_top()">\n    <a href="javascript:;"></a>\n</div>');

});
