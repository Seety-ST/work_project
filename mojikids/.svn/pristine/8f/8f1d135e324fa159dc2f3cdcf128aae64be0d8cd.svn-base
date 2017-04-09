define('wap-kids:widget/radio/radio', function(require, exports, module) {

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
      name: 'mt-radio',
  
      props: {
          title: String,
          align: String,
          options: {
              type: Array,
              required: true
          },
          value: String
      },
  
      data: function data() {
          return {
              currentValue: this.value
          };
      },
  
  
      watch: {
          value: function value(val) {
              this.currentValue = val;
          },
          currentValue: function currentValue(val) {
              this.$emit('input', val);
          }
      }
  };
  
  ;
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<div class=\"mint-radiolist\" @change=\"$emit('change', currentValue)\">\n    <label class=\"mint-radiolist-title\" v-text=\"title\"></label>\n    <div v-for=\"option in options\">\n        <label class=\"mint-radiolist-label\" slot=\"title\">\n        <span :class=\"{'is-right': align === 'right'}\" class=\"mint-radio\">\n          <input class=\"mint-radio-input\" type=\"radio\" v-model=\"currentValue\" :disabled=\"option.disabled\" :value=\"option.value || option\">\n          <span class=\"mint-radio-core\"></span>\n        </span>\n        <span class=\"mint-radio-label\" v-text=\"option.label || option\"></span>\n      </label>\n  </div>\n</div>");

});
