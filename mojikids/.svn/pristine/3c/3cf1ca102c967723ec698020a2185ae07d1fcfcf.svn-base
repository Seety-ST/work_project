define('wap-kids:widget/datepicker/datepicker', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  
  var _index = require('wap-kids:widget/picker/index');
  
  var _index2 = _interopRequireDefault(_index);
  
  var _index3 = require('wap-kids:widget/popup/index');
  
  var _index4 = _interopRequireDefault(_index3);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  var FORMAT_MAP = {
    Y: 'year',
    M: 'month',
    D: 'date',
    H: 'hour',
    m: 'minute'
  };
  
  exports.default = {
    name: 'mt-datetime-picker',
  
    props: {
      cancelText: {
        type: String,
        default: '取消'
      },
      confirmText: {
        type: String,
        default: '确定'
      },
      type: {
        type: String,
        default: 'datetime'
      },
      curDate: {
        type: String,
        default: ''
      },
      startDate: {
        type: Date,
        default: function _default() {
          return new Date(new Date().getFullYear() - 10, 0, 1);
        }
      },
      endDate: {
        type: Date,
        default: function _default() {
          return new Date(new Date().getFullYear() + 10, 11, 31);
        }
      },
      startHour: {
        type: Number,
        default: 0
      },
      endHour: {
        type: Number,
        default: 23
      },
      yearFormat: {
        type: String,
        default: '{value}'
      },
      monthFormat: {
        type: String,
        default: '{value}'
      },
      dateFormat: {
        type: String,
        default: '{value}'
      },
      hourFormat: {
        type: String,
        default: '{value}'
      },
      minuteFormat: {
        type: String,
        default: '{value}'
      },
      value: null
    },
  
    data: function data() {
      return {
        visible: false,
        startYear: null,
        endYear: null,
        startMonth: 1,
        endMonth: 12,
        startDay: 1,
        endDay: 31,
        currentValue: null,
        selfTriggered: false,
        dateSlots: [],
        shortMonthDates: [],
        longMonthDates: [],
        febDates: [],
        leapFebDates: []
      };
    },
  
  
    components: {
      'mt-picker': _index2.default,
      'mt-popup': _index4.default
    },
  
    methods: {
      open: function open() {
        this.visible = true;
      },
      close: function close() {
        this.visible = false;
      },
      isLeapYear: function isLeapYear(year) {
        return year % 400 === 0 || year % 100 !== 0 && year % 4 === 0;
      },
      isShortMonth: function isShortMonth(month) {
        return [4, 6, 9, 11].indexOf(month) > -1;
      },
      getMonthEndDay: function getMonthEndDay(year, month) {
        if (this.isShortMonth(month)) {
          return 30;
        } else if (month === 2) {
          return this.isLeapYear(year) ? 29 : 28;
        } else {
          return 31;
        }
      },
      getTrueValue: function getTrueValue(formattedValue) {
        if (!formattedValue) return;
        while (isNaN(parseInt(formattedValue, 10))) {
          formattedValue = formattedValue.slice(1);
        }
        return parseInt(formattedValue, 10);
      },
      getValue: function getValue(values) {
        var _this = this;
  
        var value = void 0;
        if (this.type === 'time') {
          value = values.map(function (value) {
            return ('0' + _this.getTrueValue(value)).slice(-2);
          }).join(':');
        } else {
          var year = this.getTrueValue(values[0]);
          var month = this.getTrueValue(values[1]);
          var date = this.getTrueValue(values[2]);
          var maxDate = this.getMonthEndDay(year, month);
          if (date > maxDate) {
            this.selfTriggered = true;
            date = 1;
          }
          var hour = this.typeStr.indexOf('H') > -1 ? this.getTrueValue(values[this.typeStr.indexOf('H')]) : 0;
          var minute = this.typeStr.indexOf('m') > -1 ? this.getTrueValue(values[this.typeStr.indexOf('m')]) : 0;
          value = new Date(year, month - 1, date, hour, minute);
        }
        return value;
      },
      onChange: function onChange(picker) {
        var values = picker.$children.filter(function (child) {
          return child.currentValue !== undefined;
        }).map(function (child) {
          return child.currentValue;
        });
        if (this.selfTriggered) {
          this.selfTriggered = false;
          return;
        }
        this.currentValue = this.getValue(values);
        this.handleValueChange();
      },
      fillValues: function fillValues(type, start, end) {
        var values = [];
        for (var i = start; i <= end; i++) {
          if (i < 10) {
            values.push(this[FORMAT_MAP[type] + 'Format'].replace('{value}', ('0' + i).slice(-2)));
          } else {
            values.push(this[FORMAT_MAP[type] + 'Format'].replace('{value}', i));
          }
        }
        return values;
      },
      pushSlots: function pushSlots(slots, type, start, end) {
        slots.push({
          flex: 1,
          values: this.fillValues(type, start, end)
        });
      },
      generateSlots: function generateSlots() {
        var _this2 = this;
  
        var dateSlots = [];
        var INTERVAL_MAP = {
          Y: this.rims.year,
          M: this.rims.month,
          D: this.rims.date,
          H: this.rims.hour,
          m: this.rims.min
        };
        var typesArr = this.typeStr.split('');
        typesArr.forEach(function (type) {
          if (INTERVAL_MAP[type]) {
            _this2.pushSlots.apply(null, [dateSlots, type].concat(INTERVAL_MAP[type]));
          }
        });
        if (this.typeStr === 'Hm') {
          dateSlots.splice(1, 0, {
            divider: true,
            content: ':'
          });
        }
        this.dateSlots = dateSlots;
        this.handleExceededValue();
      },
      handleExceededValue: function handleExceededValue() {
        var _this3 = this;
  
        var values = [];
        if (this.type === 'time') {
          values = this.currentValue.split(':');
        } else {
          values = [this.yearFormat.replace('{value}', this.getYear(this.currentValue)), this.monthFormat.replace('{value}', ('0' + this.getMonth(this.currentValue)).slice(-2)), this.dateFormat.replace('{value}', ('0' + this.getDate(this.currentValue)).slice(-2))];
          if (this.type === 'datetime') {
            values.push(this.hourFormat.replace('{value}', ('0' + this.getHour(this.currentValue)).slice(-2)), this.minuteFormat.replace('{value}', ('0' + this.getMinute(this.currentValue)).slice(-2)));
          }
        }
        this.dateSlots.filter(function (child) {
          return child.values !== undefined;
        }).map(function (slot) {
          return slot.values;
        }).forEach(function (slotValues, index) {
          if (slotValues.indexOf(values[index]) === -1) {
            values[index] = slotValues[0];
          }
        });
        this.$nextTick(function () {
          _this3.setSlotsByValues(values);
        });
      },
      setSlotsByValues: function setSlotsByValues(values) {
        var setSlotValue = this.$refs.picker.setSlotValue;
        if (this.type === 'time') {
          setSlotValue(0, values[0]);
          setSlotValue(1, values[1]);
        }
        if (this.type !== 'time') {
          setSlotValue(0, values[0]);
          setSlotValue(1, values[1]);
          setSlotValue(2, values[2]);
          if (this.type === 'datetime') {
            setSlotValue(3, values[3]);
            setSlotValue(4, values[4]);
          }
        }
        [].forEach.call(this.$refs.picker.$children, function (child) {
          return child.doOnValueChange();
        });
      },
      rimDetect: function rimDetect(result, rim) {
        var position = rim === 'start' ? 0 : 1;
        var rimDate = rim === 'start' ? this.startDate : this.endDate;
        if (this.getYear(this.currentValue) === rimDate.getFullYear()) {
          result.month[position] = rimDate.getMonth() + 1;
          if (this.getMonth(this.currentValue) === rimDate.getMonth() + 1) {
            result.date[position] = rimDate.getDate();
            if (this.getDate(this.currentValue) === rimDate.getDate()) {
              result.hour[position] = rimDate.getHours();
              if (this.getHour(this.currentValue) === rimDate.getHours()) {
                result.min[position] = rimDate.getMinutes();
              }
            }
          }
        }
      },
      isDateString: function isDateString(str) {
        return (/\d{4}(\-|\/|.)\d{1,2}\1\d{1,2}/.test(str)
        );
      },
      getYear: function getYear(value) {
        return this.isDateString(value) ? value.split(' ')[0].split(/-|\/|\./)[0] : value.getFullYear();
      },
      getMonth: function getMonth(value) {
        return this.isDateString(value) ? value.split(' ')[0].split(/-|\/|\./)[1] : value.getMonth() + 1;
      },
      getDate: function getDate(value) {
        return this.isDateString(value) ? value.split(' ')[0].split(/-|\/|\./)[2] : value.getDate();
      },
      getHour: function getHour(value) {
        if (this.isDateString(value)) {
          var str = value.split(' ')[1] || '00:00:00';
          return str.split(':')[0];
        }
        return value.getHours();
      },
      getMinute: function getMinute(value) {
        if (this.isDateString(value)) {
          var str = value.split(' ')[1] || '00:00:00';
          return str.split(':')[1];
        }
        return value.getMinutes();
      },
      confirm: function confirm() {
        this.visible = false;
        this.$emit('confirm', this.currentValue);
      },
      handleValueChange: function handleValueChange() {
        this.$emit('input', this.currentValue);
      }
    },
  
    computed: {
      rims: function rims() {
        if (!this.currentValue) return { year: [], month: [], date: [], hour: [], min: [] };
        var result = void 0;
        if (this.type === 'time') {
          result = {
            hour: [this.startHour, this.endHour],
            min: [0, 59]
          };
          return result;
        }
        result = {
          year: [this.startDate.getFullYear(), this.endDate.getFullYear()],
          month: [1, 12],
          date: [1, this.getMonthEndDay(this.getYear(this.currentValue), this.getMonth(this.currentValue))],
          hour: [0, 23],
          min: [0, 59]
        };
        this.rimDetect(result, 'start');
        this.rimDetect(result, 'end');
        return result;
      },
      typeStr: function typeStr() {
        if (this.type === 'time') {
          return 'Hm';
        } else if (this.type === 'date') {
          return 'YMD';
        } else {
          return 'YMDHm';
        }
      }
    },
  
    watch: {
      value: function value(val) {
        this.currentValue = val;
      },
      rims: function rims() {
        this.generateSlots();
      }
    },
  
    mounted: function mounted() {
      this.currentValue = this.value;
      if (!this.value) {
        if (this.type.indexOf('date') > -1) {
          if (this.curDate) {
            var cur_date = this.curDate;
            this.currentValue = new Date(cur_date);
          } else {
            this.currentValue = this.startDate;
          }
        } else {
          this.currentValue = ('0' + this.startHour).slice(-2) + ':00';
        }
      }
      this.generateSlots();
    }
  };
  
  ;
  (function (template) {
  
    module && module.exports && (module.exports.template = template);
  
    exports && exports.default && (exports.default.template = template);
  })("<mt-popup v-model=\"visible\" position=\"bottom\" class=\"mint-datetime\">\n  <mt-picker :slots=\"dateSlots\" @change=\"onChange\" :visible-item-count=\"7\" class=\"mint-datetime-picker\" ref=\"picker\" show-toolbar=\"\">\n    <span class=\"mint-datetime-action mint-datetime-cancel\" @click=\"visible = false\">{{ cancelText }}</span>\n    <span class=\"mint-datetime-action mint-datetime-confirm\" @click=\"confirm\">{{ confirmText }}</span>\n  </mt-picker>\n</mt-popup>");

});
