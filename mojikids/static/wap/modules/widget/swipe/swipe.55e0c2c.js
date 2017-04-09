define('wap-kids:widget/swipe/swipe', function(require, exports, module) {

  "use strict";function _interopRequireDefault(e){return e&&e.__esModule?e:{"default":e}}Object.defineProperty(exports,"__esModule",{value:!0});var _extend=require("wap-kids:widget/swipe/extend"),_extend2=_interopRequireDefault(_extend),w="undefined"!=typeof window;window.Swiper=_extend2.default,exports.default={name:"swiper",props:{options:{type:Object,"default":function(){return{autoplay:3500}}}},ready:function(){!this.swiper&&w&&(this.swiper=new _extend2.default(this.$el,this.options))},mounted:function(){var e=this,t=function(){!e.swiper&&w&&(delete e.options.notNextTick,e.swiper=new _extend2.default(e.$el,e.options))};this.options.notNextTick?t():this.$nextTick(t)},updated:function(){this.swiper.update()},beforeDestroy:function(){this.swiper&&(this.swiper.destroy(),delete this.swiper)}},function(e){module&&module.exports&&(module.exports.template=e),exports&&exports.default&&(exports.default.template=e)}('<div class="swiper-container">\n    <slot name="parallax-bg"></slot>\n    <div class="swiper-wrapper">\n        <slot></slot>\n    </div>\n    <slot name="pagination"></slot>\n    <slot name="button-prev"></slot>\n    <slot name="button-next"></slot>\n    <slot name="scrollbar"></slot>\n</div>');

});
