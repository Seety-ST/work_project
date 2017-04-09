define('wap-kids:widget/picker/picker', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  
  var _pickerSlot = require('wap-kids:widget/picker/picker-slot');
  
  var _pickerSlot2 = _interopRequireDefault(_pickerSlot);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  exports.default = {
    name: 'mt-picker',
  
    componentName: 'picker',
  
    props: {
      slots: {
        type: Array
      },
      showToolbar: {
        type: Boolean,
        default: false
      },
      visibleItemCount: {
        type: Number,
        default: 5
      },
      valueKey: String,
      rotateEffect: {
        type: Boolean,
        default: false
      }
    },
  
    created: function created() {
      this.$on('slotValueChange', this.slotValueChange);
      var slots = this.slots || [];
      this.values = [];
      var values = this.values;
      var valueIndexCount = 0;
      slots.forEach(function (slot) {
        if (!slot.divider) {
          slot.valueIndex = valueIndexCount++;
          values[slot.valueIndex] = (slot.values || [])[slot.defaultIndex || 0];
        }
      });
    },
  
  
    methods: {
      slotValueChange: function slotValueChange() {
        this.$emit('change', this, this.values);
      },
      getSlot: function getSlot(slotIndex) {
        var slots = this.slots || [];
        var count = 0;
        var target;
        var children = this.$children.filter(function (child) {
          return child.$options.name === 'picker-slot';
        });
  
        slots.forEach(function (slot, index) {
          if (!slot.divider) {
            if (slotIndex === count) {
              target = children[index];
            }
            count++;
          }
        });
  
        return target;
      },
      getSlotValue: function getSlotValue(index) {
        var slot = this.getSlot(index);
        if (slot) {
          return slot.value;
        }
        return null;
      },
      setSlotValue: function setSlotValue(index, value) {
        var slot = this.getSlot(index);
        if (slot) {
          slot.currentValue = value;
        }
      },
      getSlotValues: function getSlotValues(index) {
        var slot = this.getSlot(index);
        if (slot) {
          return slot.mutatingValues;
        }
        return null;
      },
      setSlotValues: function setSlotValues(index, values) {
        var slot = this.getSlot(index);
        if (slot) {
          slot.mutatingValues = values;
        }
      },
      getValues: function getValues() {
        return this.values;
      },
      setValues: function setValues(values) {
        var _this = this;
  
        var slotCount = this.slotCount;
        values = values || [];
        if (slotCount !== values.length) {
          throw new Error('values length is not equal slot count.');
        }
        values.forEach(function (value, index) {
          _this.setSlotValue(index, value);
        });
      }
    },
  
    computed: {
      values: function values() {
        var slots = this.slots || [];
        var values = [];
        slots.forEach(function (slot) {
          if (!slot.divider) values.push(slot.value);
        });
  
        return values;
      },
      slotCount: function slotCount() {
        var slots = this.slots || [];
        var result = 0;
        slots.forEach(function (slot) {
          if (!slot.divider) result++;
        });
        return result;
      }
    },
  
    components: {
      PickerSlot: _pickerSlot2.default
    }
  };
  
  ;
  (function (template) {
  
    module && module.exports && (module.exports.template = template);
  
    exports && exports.default && (exports.default.template = template);
  })("<div class=\"picker\" :class=\"{ 'picker-3d': rotateEffect }\">\n  <div class=\"picker-toolbar\" v-if=\"showToolbar\"><slot></slot></div>\n  <div class=\"picker-items\">\n    <picker-slot v-for=\"slot in slots\" :valuekey=\"valueKey\" :values=\"slot.values || []\" :text-align=\"slot.textAlign || 'center'\" :visible-item-count=\"visibleItemCount\" :class-name=\"slot.className\" :flex=\"slot.flex\" v-model=\"values[slot.valueIndex]\" :rotate-effect=\"rotateEffect\" :divider=\"slot.divider\" :content=\"slot.content\"></picker-slot>\n    <div class=\"picker-center-highlight\"></div>\n  </div>\n</div>");

});
