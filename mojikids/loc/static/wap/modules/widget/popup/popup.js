define('wap-kids:widget/popup/popup', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
      value: true
  });
  
  var _extend = require('wap-kids:widget/popup/extend');
  
  var _extend2 = _interopRequireDefault(_extend);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  exports.default = {
      name: 'mt-popup',
  
      mixins: [_extend2.default],
  
      props: {
          modal: {
              default: true
          },
  
          modalFade: {
              default: false
          },
  
          lockScroll: {
              default: false
          },
  
          closeOnClickModal: {
              default: true
          },
  
          popupTransition: {
              type: String,
              default: 'popup-slide'
          },
  
          position: {
              type: String,
              default: ''
          }
      },
  
      data: function data() {
          return {
              currentValue: false,
              currentTransition: this.popupTransition
          };
      },
  
  
      watch: {
          currentValue: function currentValue(val) {
              this.$emit('input', val);
          },
          value: function value(val) {
              this.currentValue = val;
          }
      },
  
      beforeMount: function beforeMount() {
          if (this.popupTransition !== 'popup-fade') {
              this.currentTransition = 'popup-slide-' + this.position;
          }
      },
      mounted: function mounted() {
          if (this.value) {
              this.rendered = true;
              this.currentValue = true;
              this.open();
          }
      }
  };
  
  ;
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<transition :name=\"currentTransition\">\n    <div v-show=\"currentValue\" class=\"mint-popup\" :class=\"[position ? 'mint-popup-' + position : '']\">\n        <slot></slot>\n    </div>\n</transition>");

});
