define('wap-kids:widget/dialog/dialog', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  
  var _extend = require('wap-kids:widget/popup/extend');
  
  var _extend2 = _interopRequireDefault(_extend);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  var CONFIRM_TEXT = '确认';
  var CANCEL_TEXT = '取消';
  
  exports.default = {
    mixins: [_extend2.default],
  
    props: {
      modal: {
        default: true
      },
      showClose: {
        type: Boolean,
        default: true
      },
      lockScroll: {
        type: Boolean,
        default: false
      },
      closeOnClickModal: {
        default: true
      },
      closeOnPressEscape: {
        default: true
      },
      inputType: {
        type: String,
        default: 'text'
      }
    },
  
    computed: {
      confirmButtonClasses: function confirmButtonClasses() {
        var classes = 'mint-msgbox-btn mint-msgbox-confirm ' + this.confirmButtonClass;
        if (this.confirmButtonHighlight) {
          classes += ' mint-msgbox-confirm-highlight';
        }
        return classes;
      },
      cancelButtonClasses: function cancelButtonClasses() {
        var classes = 'mint-msgbox-btn mint-msgbox-cancel ' + this.cancelButtonClass;
        if (this.cancelButtonHighlight) {
          classes += ' mint-msgbox-cancel-highlight';
        }
        return classes;
      }
    },
  
    methods: {
      doClose: function doClose() {
        var _this = this;
  
        this.value = false;
        this._closing = true;
  
        this.onClose && this.onClose();
  
        setTimeout(function () {
          if (_this.modal && _this.bodyOverflow !== 'hidden') {
            document.body.style.overflow = _this.bodyOverflow;
            document.body.style.paddingRight = _this.bodyPaddingRight;
          }
          _this.bodyOverflow = null;
          _this.bodyPaddingRight = null;
        }, 200);
        this.opened = false;
  
        if (!this.transition) {
          this.doAfterClose();
        }
      },
      handleAction: function handleAction(action) {
        if (this.$type === 'prompt' && action === 'confirm' && !this.validate()) {
          return;
        }
        var callback = this.callback;
        this.value = false;
        callback(action);
      },
      validate: function validate() {
        if (this.$type === 'prompt') {
          var inputPattern = this.inputPattern;
          if (inputPattern && !inputPattern.test(this.inputValue || '')) {
            this.editorErrorMessage = this.inputErrorMessage || '输入的数据不合法!';
            this.$refs.input.classList.add('invalid');
            return false;
          }
          var inputValidator = this.inputValidator;
          if (typeof inputValidator === 'function') {
            var validateResult = inputValidator(this.inputValue);
            if (validateResult === false) {
              this.editorErrorMessage = this.inputErrorMessage || '输入的数据不合法!';
              this.$refs.input.classList.add('invalid');
              return false;
            }
            if (typeof validateResult === 'string') {
              this.editorErrorMessage = validateResult;
              return false;
            }
          }
        }
        this.editorErrorMessage = '';
        this.$refs.input.classList.remove('invalid');
        return true;
      },
      handleInputType: function handleInputType(val) {
        if (val === 'range' || !this.$refs.input) return;
        this.$refs.input.type = val;
      }
    },
  
    watch: {
      inputValue: function inputValue() {
        if (this.$type === 'prompt') {
          this.validate();
        }
      },
      value: function value(val) {
        var _this2 = this;
  
        this.handleInputType(this.inputType);
        if (val && this.$type === 'prompt') {
          setTimeout(function () {
            if (_this2.$refs.input) {
              _this2.$refs.input.focus();
            }
          }, 500);
        }
      },
      inputType: function inputType(val) {
        this.handleInputType(val);
      }
    },
  
    data: function data() {
      return {
        title: '',
        message: '',
        type: '',
        showInput: false,
        inputValue: null,
        inputPlaceholder: '',
        inputPattern: null,
        inputValidator: null,
        inputErrorMessage: '',
        showConfirmButton: true,
        showCancelButton: false,
        confirmButtonText: CONFIRM_TEXT,
        cancelButtonText: CANCEL_TEXT,
        confirmButtonClass: '',
        confirmButtonDisabled: false,
        cancelButtonClass: '',
        editorErrorMessage: null,
        callback: null
      };
    }
  };
  
  ;
  (function (template) {
  
    module && module.exports && (module.exports.template = template);
  
    exports && exports.default && (exports.default.template = template);
  })("<div class=\"mint-msgbox-wrapper\">\n  <transition name=\"msgbox-bounce\">\n    <div class=\"mint-msgbox\" v-show=\"value\">\n      <div class=\"mint-msgbox-header\" v-if=\"title !== ''\">\n        <div class=\"mint-msgbox-title\">{{ title }}</div>\n      </div>\n      <div class=\"mint-msgbox-content\" v-if=\"message !== ''\">\n        <div class=\"mint-msgbox-message\" v-html=\"message\"></div>\n        <div class=\"mint-msgbox-input\" v-show=\"showInput\">\n          <input v-model=\"inputValue\" :placeholder=\"inputPlaceholder\" ref=\"input\">\n          <div class=\"mint-msgbox-errormsg\" :style=\"{ visibility: !!editorErrorMessage ? 'visible' : 'hidden' }\">{{ editorErrorMessage }}</div>\n        </div>\n      </div>\n      <div class=\"mint-msgbox-btns\">\n        <button :class=\"[ cancelButtonClasses ]\" v-show=\"showCancelButton\" @click=\"handleAction('cancel')\">{{ cancelButtonText }}</button>\n        <button :class=\"[ confirmButtonClasses ]\" v-show=\"showConfirmButton\" @click=\"handleAction('confirm')\">{{ confirmButtonText }}</button>\n      </div>\n    </div>\n  </transition>\n</div>");

});
