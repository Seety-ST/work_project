define('wap-kids:widget/loading/extend', function(require, exports, module) {

  'use strict';
  
  var _loading = require('wap-kids:widget/loading/loading');
  
  var _loading2 = _interopRequireDefault(_loading);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  var Indicator = Vue.extend(_loading2.default);
  var instance = void 0;
  var timer = void 0;
  
  module.exports = {
    open: function open() {
      var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  
      if (!instance) {
        instance = new Indicator({
          el: document.createElement('div')
        });
      }
  
      if (instance.visible) return;
      instance.text = typeof options === 'string' ? options : options.text || '';
      instance.spinnerType = options.spinnerType || 'snake';
      instance.timeout = options.timeout || 0;
      document.body.appendChild(instance.$el);
      if (timer) {
        clearTimeout(timer);
      }
  
      if (instance.timeout > 0) {
        setTimeout(function () {
          instance.visible = false;
        }, instance.timeout);
      }
  
      Vue.nextTick(function () {
        instance.visible = true;
      });
    },
    close: function close() {
      if (instance) {
        instance.visible = false;
        timer = setTimeout(function () {
          if (instance.$el) {
            instance.$el.style.display = 'none';
          }
        }, 400);
      }
    }
  };

});
