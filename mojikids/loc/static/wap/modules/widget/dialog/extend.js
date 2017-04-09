define('wap-kids:widget/dialog/extend', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.MessageBox = undefined;
  
  var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };
  
  var _dialog = require('wap-kids:widget/dialog/dialog');
  
  var _dialog2 = _interopRequireDefault(_dialog);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  var CONFIRM_TEXT = '确定';
  var CANCEL_TEXT = '取消';
  
  // 默认配置
  var defaults = {
    title: '提示',
    message: '',
    type: '',
    showInput: false,
    showClose: true,
    modalFade: false,
    lockScroll: false,
    closeOnClickModal: true,
    inputValue: null,
    inputPlaceholder: '',
    inputPattern: null,
    inputValidator: null,
    inputErrorMessage: '',
    showConfirmButton: true,
    showCancelButton: false,
    confirmButtonPosition: 'right',
    confirmButtonHighlight: false,
    cancelButtonHighlight: false,
    confirmButtonText: CONFIRM_TEXT,
    cancelButtonText: CANCEL_TEXT,
    confirmButtonClass: '',
    cancelButtonClass: ''
  };
  
  var merge = function merge(target) {
    for (var i = 1, j = arguments.length; i < j; i++) {
      var source = arguments[i];
      for (var prop in source) {
        if (source.hasOwnProperty(prop)) {
          var value = source[prop];
          if (value !== undefined) {
            target[prop] = value;
          }
        }
      }
    }
  
    return target;
  };
  
  var MessageBoxConstructor = Vue.extend(_dialog2.default);
  
  var currentMsg, instance;
  var msgQueue = [];
  
  var defaultCallback = function defaultCallback(action) {
    if (currentMsg) {
      var callback = currentMsg.callback;
      if (typeof callback === 'function') {
        if (instance.showInput) {
          callback(instance.inputValue, action);
        } else {
          callback(action);
        }
      }
      if (currentMsg.resolve) {
        var $type = currentMsg.options.$type;
        if ($type === 'confirm' || $type === 'prompt') {
          if (action === 'confirm') {
            if (instance.showInput) {
              currentMsg.resolve({ value: instance.inputValue, action: action });
            } else {
              currentMsg.resolve(action);
            }
          } else if (action === 'cancel' && currentMsg.reject) {
            currentMsg.reject(action);
          }
        } else {
          currentMsg.resolve(action);
        }
      }
    }
  };
  
  var initInstance = function initInstance() {
    instance = new MessageBoxConstructor({
      el: document.createElement('div')
    });
  
    instance.callback = defaultCallback;
  };
  
  var showNextMsg = function showNextMsg() {
    if (!instance) {
      initInstance();
    }
  
    if (!instance.value || instance.closeTimer) {
      if (msgQueue.length > 0) {
        currentMsg = msgQueue.shift();
  
        var options = currentMsg.options;
        for (var prop in options) {
          if (options.hasOwnProperty(prop)) {
            instance[prop] = options[prop];
          }
        }
        if (options.callback === undefined) {
          instance.callback = defaultCallback;
        }
        ['modal', 'showClose', 'closeOnClickModal', 'closeOnPressEscape'].forEach(function (prop) {
          if (instance[prop] === undefined) {
            instance[prop] = true;
          }
        });
        document.body.appendChild(instance.$el);
  
        Vue.nextTick(function () {
          instance.value = true;
        });
      }
    }
  };
  
  var MessageBox = function MessageBox(options, callback) {
    if (typeof options === 'string') {
      options = {
        title: options
      };
      if (arguments[1]) {
        options.message = arguments[1];
      }
      if (arguments[2]) {
        options.type = arguments[2];
      }
    } else if (options.callback && !callback) {
      callback = options.callback;
    }
  
    if (typeof Promise !== 'undefined') {
      return new Promise(function (resolve, reject) {
        // eslint-disable-line
        msgQueue.push({
          options: merge({}, defaults, MessageBox.defaults || {}, options),
          callback: callback,
          resolve: resolve,
          reject: reject
        });
  
        showNextMsg();
      });
    } else {
      msgQueue.push({
        options: merge({}, defaults, MessageBox.defaults || {}, options),
        callback: callback
      });
  
      showNextMsg();
    }
  };
  
  MessageBox.setDefaults = function (defaults) {
    MessageBox.defaults = defaults;
  };
  
  MessageBox.alert = function (message, title, options) {
    if ((typeof title === 'undefined' ? 'undefined' : _typeof(title)) === 'object') {
      options = title;
      title = '';
    }
    return MessageBox(merge({
      title: title,
      message: message,
      $type: 'alert',
      closeOnPressEscape: false,
      closeOnClickModal: false
    }, options));
  };
  
  MessageBox.confirm = function (message, title, options) {
    if ((typeof title === 'undefined' ? 'undefined' : _typeof(title)) === 'object') {
      options = title;
      title = '';
    }
    return MessageBox(merge({
      title: title,
      message: message,
      $type: 'confirm',
      showCancelButton: true
    }, options));
  };
  
  MessageBox.prompt = function (message, title, options) {
    if ((typeof title === 'undefined' ? 'undefined' : _typeof(title)) === 'object') {
      options = title;
      title = '';
    }
    return MessageBox(merge({
      title: title,
      message: message,
      showCancelButton: true,
      showInput: true,
      $type: 'prompt'
    }, options));
  };
  
  MessageBox.close = function () {
    instance.value = false;
    msgQueue = [];
    currentMsg = null;
  };
  
  exports.default = MessageBox;
  exports.MessageBox = MessageBox;

});
