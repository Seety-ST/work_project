define('wap-kids:widget/swipe/swipe-item', function(require, exports, module) {

  "use strict";
  
  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.default = {
    name: 'swiper-slide',
    ready: function ready() {
      this.update();
    },
    mounted: function mounted() {
      this.update();
    },
    updated: function updated() {
      this.update();
    },
    attached: function attached() {
      this.update();
    },
  
    methods: {
      update: function update() {
        if (this.$parent && this.$parent.swiper) {
          this.$parent.swiper.update(true);
          if (this.$parent.options.loop) {
            this.$parent.swiper.destroyLoop();
            this.$parent.swiper.createLoop();
          }
        }
      }
    }
  };
  
  (function (template) {
  
    module && module.exports && (module.exports.template = template);
  
    exports && exports.default && (exports.default.template = template);
  })("<div class=\"swiper-slide\">\n  <slot></slot>\n</div>");

});
