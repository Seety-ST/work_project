define('wap-kids:widget/picker/picker-slot', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  
  var _draggable = require('wap-kids:widget/picker/draggable');
  
  var _draggable2 = _interopRequireDefault(_draggable);
  
  var _translate = require('wap-kids:widget/picker/translate');
  
  var _translate2 = _interopRequireDefault(_translate);
  
  var _index = require('wap-kids:global/util/index');
  
  var _emitter = require('wap-kids:widget/picker/emitter');
  
  var _emitter2 = _interopRequireDefault(_emitter);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  (function (window) {
    var lastTime = 0,
        vendors = ['webkit', 'moz'],
        requestAnimationFrame = window.requestAnimationFrame,
        cancelAnimationFrame = window.cancelAnimationFrame,
        i = vendors.length;
  
    // try to un-prefix existing raf
    while (--i >= 0 && !requestAnimationFrame) {
      requestAnimationFrame = window[vendors[i] + 'RequestAnimationFrame'];
      cancelAnimationFrame = window[vendors[i] + 'CancelAnimationFrame'];
    }
  
    // polyfill with setTimeout fallback
    // heavily inspired from @darius gist mod: https://gist.github.com/paulirish/1579671#comment-837945
    if (!requestAnimationFrame || !cancelAnimationFrame) {
      requestAnimationFrame = function requestAnimationFrame(callback) {
        var now = +new Date(),
            nextTime = Math.max(lastTime + 16, now);
        return setTimeout(function () {
          callback(lastTime = nextTime);
        }, nextTime - now);
      };
  
      cancelAnimationFrame = clearTimeout;
    }
  
    // export to window
    window.requestAnimationFrame = requestAnimationFrame;
    window.cancelAnimationFrame = cancelAnimationFrame;
  })(window);
  
  var rotateElement = function rotateElement(element, angle) {
    if (!element) return;
    var transformProperty = _translate2.default.transformProperty;
  
    element.style[transformProperty] = element.style[transformProperty].replace(/rotateX\(.+?deg\)/gi, '') + (' rotateX(' + angle + 'deg)');
  };
  
  var ITEM_HEIGHT = 36;
  var VISIBLE_ITEMS_ANGLE_MAP = {
    3: -45,
    5: -20,
    7: -15
  };
  
  exports.default = {
    name: 'picker-slot',
  
    props: {
      values: {
        type: Array,
        default: function _default() {
          return [];
        }
      },
      value: {},
      visibleItemCount: {
        type: Number,
        default: 5
      },
      valueKey: String,
      rotateEffect: {
        type: Boolean,
        default: false
      },
      divider: {
        type: Boolean,
        default: false
      },
      textAlign: {
        type: String,
        default: 'center'
      },
      flex: {},
      className: {},
      content: {}
    },
  
    data: function data() {
      return {
        currentValue: this.value,
        mutatingValues: this.values,
        dragging: false,
        animationFrameId: null
      };
    },
  
  
    mixins: [_emitter2.default],
  
    computed: {
      flexStyle: function flexStyle() {
        return {
          'flex': this.flex,
          '-webkit-box-flex': this.flex,
          '-moz-box-flex': this.flex,
          '-ms-flex': this.flex
        };
      },
      classNames: function classNames() {
        var PREFIX = 'picker-slot-';
        var resultArray = [];
  
        if (this.rotateEffect) {
          resultArray.push(PREFIX + 'absolute');
        }
  
        var textAlign = this.textAlign || 'center';
        resultArray.push(PREFIX + textAlign);
  
        if (this.divider) {
          resultArray.push(PREFIX + 'divider');
        }
  
        if (this.className) {
          resultArray.push(this.className);
        }
  
        return resultArray.join(' ');
      },
      contentHeight: function contentHeight() {
        return ITEM_HEIGHT * this.visibleItemCount;
      },
      valueIndex: function valueIndex() {
        return this.mutatingValues.indexOf(this.currentValue);
      },
      dragRange: function dragRange() {
        var values = this.mutatingValues;
        var visibleItemCount = this.visibleItemCount;
  
        return [-ITEM_HEIGHT * (values.length - Math.ceil(visibleItemCount / 2)), ITEM_HEIGHT * Math.floor(visibleItemCount / 2)];
      }
    },
  
    methods: {
      value2Translate: function value2Translate(value) {
        var values = this.mutatingValues;
        var valueIndex = values.indexOf(value);
        var offset = Math.floor(this.visibleItemCount / 2);
  
        if (valueIndex !== -1) {
          return (valueIndex - offset) * -ITEM_HEIGHT;
        }
      },
      translate2Value: function translate2Value(translate) {
        translate = Math.round(translate / ITEM_HEIGHT) * ITEM_HEIGHT;
        var index = -(translate - Math.floor(this.visibleItemCount / 2) * ITEM_HEIGHT) / ITEM_HEIGHT;
  
        return this.mutatingValues[index];
      },
  
  
      updateRotate: function updateRotate(currentTranslate, pickerItems) {
        if (this.divider) return;
        var dragRange = this.dragRange;
        var wrapper = this.$refs.wrapper;
  
        if (!pickerItems) {
          pickerItems = wrapper.querySelectorAll('.picker-item');
        }
  
        if (currentTranslate === undefined) {
          currentTranslate = _translate2.default.getElementTranslate(wrapper).top;
        }
  
        var itemsFit = Math.ceil(this.visibleItemCount / 2);
        var angleUnit = VISIBLE_ITEMS_ANGLE_MAP[this.visibleItemCount] || -20;
  
        [].forEach.call(pickerItems, function (item, index) {
          var itemOffsetTop = index * ITEM_HEIGHT;
          var translateOffset = dragRange[1] - currentTranslate;
          var itemOffset = itemOffsetTop - translateOffset;
          var percentage = itemOffset / ITEM_HEIGHT;
  
          var angle = angleUnit * percentage;
          if (angle > 180) angle = 180;
          if (angle < -180) angle = -180;
  
          rotateElement(item, angle);
  
          if (Math.abs(percentage) > itemsFit) {
            (0, _index.addClass)(item, 'picker-item-far');
          } else {
            (0, _index.removeClass)(item, 'picker-item-far');
          }
        });
      },
  
      planUpdateRotate: function planUpdateRotate() {
        var _this = this;
  
        var el = this.$refs.wrapper;
        cancelAnimationFrame(this.animationFrameId);
  
        this.animationFrameId = requestAnimationFrame(function () {
          _this.updateRotate();
        });
  
        (0, _index.once)(el, _translate2.default.transitionEndProperty, function () {
          _this.animationFrameId = null;
          cancelAnimationFrame(_this.animationFrameId);
        });
      },
  
      initEvents: function initEvents() {
        var _this2 = this;
  
        var el = this.$refs.wrapper;
        var dragState = {};
  
        var velocityTranslate, prevTranslate, pickerItems;
  
        (0, _draggable2.default)(el, {
          start: function start(event) {
            cancelAnimationFrame(_this2.animationFrameId);
            _this2.animationFrameId = null;
            dragState = {
              range: _this2.dragRange,
              start: new Date(),
              startLeft: event.pageX,
              startTop: event.pageY,
              startTranslateTop: _translate2.default.getElementTranslate(el).top
            };
            pickerItems = el.querySelectorAll('.picker-item');
          },
  
          drag: function drag(event) {
            _this2.dragging = true;
  
            dragState.left = event.pageX;
            dragState.top = event.pageY;
  
            var deltaY = dragState.top - dragState.startTop;
            var translate = dragState.startTranslateTop + deltaY;
  
            _translate2.default.translateElement(el, null, translate);
  
            velocityTranslate = translate - prevTranslate || translate;
  
            prevTranslate = translate;
  
            if (_this2.rotateEffect) {
              _this2.updateRotate(prevTranslate, pickerItems);
            }
          },
  
          end: function end() {
            _this2.dragging = false;
  
            var momentumRatio = 7;
            var currentTranslate = _translate2.default.getElementTranslate(el).top;
            var duration = new Date() - dragState.start;
  
            var momentumTranslate;
            if (duration < 300) {
              momentumTranslate = currentTranslate + velocityTranslate * momentumRatio;
            }
  
            var dragRange = dragState.range;
  
            _this2.$nextTick(function () {
              var translate;
              if (momentumTranslate) {
                translate = Math.round(momentumTranslate / ITEM_HEIGHT) * ITEM_HEIGHT;
              } else {
                translate = Math.round(currentTranslate / ITEM_HEIGHT) * ITEM_HEIGHT;
              }
  
              translate = Math.max(Math.min(translate, dragRange[1]), dragRange[0]);
  
              _translate2.default.translateElement(el, null, translate);
  
              _this2.currentValue = _this2.translate2Value(translate);
  
              if (_this2.rotateEffect) {
                _this2.planUpdateRotate();
              }
            });
  
            dragState = {};
          }
        });
      },
      doOnValueChange: function doOnValueChange() {
        var value = this.currentValue;
        var wrapper = this.$refs.wrapper;
  
        _translate2.default.translateElement(wrapper, null, this.value2Translate(value));
      },
      doOnValuesChange: function doOnValuesChange() {
        var el = this.$el;
        var items = el.querySelectorAll('.picker-item');
        [].forEach.call(items, function (item, index) {
          _translate2.default.translateElement(item, null, ITEM_HEIGHT * index);
        });
        if (this.rotateEffect) {
          this.planUpdateRotate();
        }
      }
    },
  
    mounted: function mounted() {
      this.ready = true;
      this.$emit('input', this.currentValue);
  
      if (!this.divider) {
        this.initEvents();
        this.doOnValueChange();
      }
  
      if (this.rotateEffect) {
        this.doOnValuesChange();
      }
    },
  
  
    watch: {
      values: function values(val) {
        this.mutatingValues = val;
      },
      mutatingValues: function mutatingValues(val) {
        var _this3 = this;
  
        if (this.valueIndex === -1) {
          this.currentValue = (val || [])[0];
        }
        if (this.rotateEffect) {
          this.$nextTick(function () {
            _this3.doOnValuesChange();
          });
        }
      },
      currentValue: function currentValue(val) {
        this.doOnValueChange();
        if (this.rotateEffect) {
          this.planUpdateRotate();
        }
        this.$emit('input', val);
        this.dispatch('picker', 'slotValueChange', this);
      }
    }
  };
  
  ;
  (function (template) {
  
    module && module.exports && (module.exports.template = template);
  
    exports && exports.default && (exports.default.template = template);
  })("<div class=\"picker-slot\" :class=\"classNames\" :style=\"flexStyle\">\n  <div v-if=\"!divider\" ref=\"wrapper\" class=\"picker-slot-wrapper\" :class=\"{ dragging: dragging }\" :style=\"{ height: contentHeight + 'px' }\">\n    <div class=\"picker-item\" v-for=\"itemValue in mutatingValues\" :class=\"{ 'picker-selected': itemValue === currentValue }\">\n      {{ typeof itemValue === 'object' &amp;&amp; itemValue[valueKey] ? itemValue[valueKey] : itemValue }}\n    </div>\n  </div>\n  <div v-if=\"divider\">{{ content }}</div>\n</div>");

});
