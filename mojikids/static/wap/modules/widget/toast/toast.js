define('wap-kids:widget/toast/toast', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
      value: true
  });
  exports.default = {
      props: {
          message: String,
          className: {
              type: String,
              default: ''
          },
          position: {
              type: String,
              default: 'middle'
          },
          iconClass: {
              type: String,
              default: ''
          }
      },
  
      data: function data() {
          return {
              visible: false
          };
      },
  
  
      computed: {
          customClass: function customClass() {
              var classes = [];
              switch (this.position) {
                  case 'top':
                      classes.push('is-placetop');
                      break;
                  case 'bottom':
                      classes.push('is-placebottom');
                      break;
                  default:
                      classes.push('is-placemiddle');
              }
              classes.push(this.className);
  
              return classes.join(' ');
          }
      }
  };
  
  ;
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<transition name=\"mint-toast-pop\" data-role=\"toast\">\n    <div class=\"mint-toast\" v-show=\"visible\" :class=\"customClass\" :style=\"{ 'padding': iconClass === '' ? '10px' : '20px' }\">\n        <i class=\"mint-toast-icon\" :class=\"iconClass\" v-if=\"iconClass !== ''\"></i>\n        <span class=\"mint-toast-text\" :style=\"{ 'padding-top': iconClass === '' ? '0' : '10px' }\">{{ message }}</span>\n    </div>\n</transition>");

});
