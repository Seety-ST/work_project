define('wap-kids:widget/go_top/radio', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
      value: true
  });
  /**
   * mt-radio
   * @module components/radio
   * @desc 单选框列表，依赖 cell 组件
   *
   * @param {string[], object[]} options - 选项数组，可以传入 [{label: 'label', value: 'value', disabled: true}] 或者 ['ab', 'cd', 'ef']
   * @param {string} value - 选中值
   * @param {string} title - 标题
   * @param {string} [align=left] - checkbox 对齐位置，`left`, `right`
   *
   * @example
   * <mt-radio v-model="value" :options="['a', 'b', 'c']"></mt-radio>
   */
  exports.default = {
      name: 'my-radio',
  
      props: {
          options: {
              type: Array,
              required: true
          },
          defalueval: String
      },
      data: function data() {
          return {
              currentValue: this.defalueval
          };
      },
  
      watch: {
          defalueval: function defalueval(val) {
              this.currentValue = val;
          },
          currentValue: function currentValue(val) {
              this.$emit('input', val);
              console.log(val);
          }
      }
  
  };
  
  ;
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<div>\n    <label class=\"radio-mod-1 \" v-for=\"option in options\" @change=\"$emit('change', currentValue)\" :class=\"{'is-checked': option.value === currentValue}\">\n        <span class=\"el-radio-input \">\n            <span class=\"radio-inner\"></span>\n            <input type=\"radio\" class=\"el-radio-original\" v-model=\"currentValue\" :disabled=\"option.disabled\" :value=\"option.value || option\">\n        </span>\n        <span class=\"el-radio-label f14 color-ccc\" v-text=\"option.value\"></span>\n    </label>\n</div>");

});
