define('wap-kids:widget/toast/extend', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  
  var _toast = require('wap-kids:widget/toast/toast');
  
  var _toast2 = _interopRequireDefault(_toast);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  var ToastConstructor = Vue.extend(_toast2.default); /**
                                                       * Toast 组件 逻辑层
                                                       */
  
  var toastPool = [];
  
  var getAnInstance = function getAnInstance() {
    if (toastPool.length > 0) {
      var instance = toastPool[0];
      toastPool.splice(0, 1);
      return instance;
    }
  
    var toast_constructor_obj = new ToastConstructor({
      el: document.createElement('div')
    });
  
    return toast_constructor_obj;
  };
  
  var returnAnInstance = function returnAnInstance(instance) {
    if (instance) {
      toastPool.push(instance);
    }
  };
  
  var removeDom = function removeDom(event) {
    if (event.target.parentNode) {
      event.target.parentNode.removeChild(event.target);
    }
  };
  
  ToastConstructor.prototype.close = function () {
    this.visible = false;
    this.$el.addEventListener('transitionend', removeDom);
    this.closed = true;
    returnAnInstance(this);
  };
  
  var Toast = function Toast() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  
    var duration = options.duration || 3000;
  
    var instance = getAnInstance();
    instance.closed = false;
    clearTimeout(instance.timer);
    instance.message = typeof options === 'string' ? options : options.message;
    instance.position = options.position || 'middle';
    instance.className = options.className || '';
    instance.iconClass = options.iconClass || '';
  
    document.body.appendChild(instance.$el);
    Vue.nextTick(function () {
      instance.visible = true;
      instance.$el.removeEventListener('transitionend', removeDom);
      instance.timer = setTimeout(function () {
        if (instance.closed) return;
        instance.close();
      }, duration);
    });
    return instance;
  };
  
  exports.default = Toast;

});
