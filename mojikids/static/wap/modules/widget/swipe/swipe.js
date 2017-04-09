define('wap-kids:widget/swipe/swipe', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
      value: true
  });
  
  var _extend = require('wap-kids:widget/swipe/extend');
  
  var _extend2 = _interopRequireDefault(_extend);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  var w = typeof window != 'undefined';
  
  window.Swiper = _extend2.default;
  
  exports.default = {
      name: 'swiper',
      props: {
          options: {
              type: Object,
              default: function _default() {
                  return {
                      autoplay: 3500
                  };
              }
          }
      },
      ready: function ready() {
          if (!this.swiper && w) {
              this.swiper = new _extend2.default(this.$el, this.options);
          }
      },
      mounted: function mounted() {
          var self = this;
          var mount = function mount() {
              if (!self.swiper && w) {
                  delete self.options.notNextTick;
                  self.swiper = new _extend2.default(self.$el, self.options);
              }
          };
          this.options.notNextTick ? mount() : this.$nextTick(mount);
      },
      updated: function updated() {
          this.swiper.update();
      },
      beforeDestroy: function beforeDestroy() {
          if (!!this.swiper) {
              this.swiper.destroy();
              delete this.swiper;
          }
      }
  };
  
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<div class=\"swiper-container\">\n    <slot name=\"parallax-bg\"></slot>\n    <div class=\"swiper-wrapper\">\n        <slot></slot>\n    </div>\n    <slot name=\"pagination\"></slot>\n    <slot name=\"button-prev\"></slot>\n    <slot name=\"button-next\"></slot>\n    <slot name=\"scrollbar\"></slot>\n</div>");

});
