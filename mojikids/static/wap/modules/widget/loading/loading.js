define('wap-kids:widget/loading/loading', function(require, exports, module) {

  "use strict";
  
  Object.defineProperty(exports, "__esModule", {
      value: true
  });
  exports.default = {
      props: {
          text: String,
          timeout: Number
      },
      data: function data() {
          return {
              visible: false
          };
      }
  };
  
  ;
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<transition name=\"mint-indicator\">\n    <div class=\"mint-indicator\" v-show=\"visible\">\n        <div class=\"mint-indicator-wrapper\" :style=\"{ 'padding': text ? '20px' : '15px' }\">\n            <div class=\"mint-indicator-spin\">\n                <div class=\"v-spinner-snake\"></div>\n            </div>\n            <span class=\"mint-indicator-text\" v-show=\"text\">{{ text }}</span>\n        </div>\n        <div class=\"mint-indicator-mask\" @touchmove.stop.prevent=\"\"></div>\n    </div>\n</transition>");

});
