define('wap-kids:layout/footer/footer', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
      value: true
  });
  exports.default = {
      // 组件标记
      name: 'moji-footer',
      props: {
          class_name: {
              type: String,
              default: ''
          },
          index: {
              type: String,
              default: 0
          }
      },
      data: function data() {
          return {
              footer_show: false
          };
      },
  
      computed: {},
      methods: {}
  };
  
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<footer class=\"global-footer\">\n    <a href=\"#\">\n        <div class=\"box\" :class=\"{ 'active' : index == 0 }\">\n            <span>首页</span>\n        </div>\n    </a>\n    <a href=\"#\">\n        <div class=\"box\" :class=\"{ 'active' : index == 1 }\">\n            <span>订单</span>\n        </div>\n    </a>\n    <a href=\"#\">\n        <div class=\"box\" :class=\"{ 'active' : index == 2 }\">\n            <span>我的</span>\n        </div>\n    </a>\n</footer>");

});
