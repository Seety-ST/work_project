define('wap-kids:widget/area/area', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
      value: true
  });
  
  var _index = require('wap-kids:widget/picker/index');
  
  var _index2 = _interopRequireDefault(_index);
  
  var _index3 = require('wap-kids:widget/popup/index');
  
  var _index4 = _interopRequireDefault(_index3);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  var Util = require.syncLoad('wap-kids:global/util/index');
  var global_location_data = {};
  
  exports.default = {
      name: 'moji-area',
      props: {
          show: {
              type: Boolean,
              default: false
          },
          locationId: {
              type: String,
              default: '101029001'
          },
          title: {
              type: String,
              default: ''
          }
  
      },
      mounted: function mounted() {
          var self = this;
  
          self.get_location_data();
      },
      data: function data() {
          return {
              visible: false,
              slots: [{
                  flex: 1,
                  values: [],
                  defaultIndex: 7,
                  className: 'slot1',
                  textAlign: 'center'
              }, {
                  divider: true,
                  content: '-',
                  className: 'slot2'
              }, {
                  flex: 1,
                  values: [],
                  className: 'slot3',
                  textAlign: 'center'
              }],
              city_obj: {},
              cur_province_id: '',
              cur_province_name: '',
              cur_city_id: '',
              cur_city_name: ''
          };
      },
  
      components: {
          'mt-picker': _index2.default,
          'mt-popup': _index4.default
      },
      computed: {},
      methods: {
          open: function open() {
              var self = this;
              self.$data.visible = true;
          },
          confirm: function confirm() {
              var self = this;
              var location_id = self.$data.city_obj[self.$data.city] || '';
              var province_name = self.$data.province;
              var city_name = self.$data.city;
  
              self.$data.visible = false;
  
              self.$emit('confirm', {
                  location_id: location_id,
                  province_name: province_name,
                  city_name: city_name
              });
          },
          address_change: function address_change(picker, values) {
  
              var self = this;
              picker.setSlotValues(1, global_location_data[values[0]]);
  
              self.$data.province = values[0];
              self.$data.city = values[1];
  
              if (self.$data.city_obj[self.$data.city]) {
                  self.$data.location_id = self.$data.city_obj[self.$data.city];
              }
          },
          get_location_data: function get_location_data() {
              var self = this;
  
              Util.request({
                  url: 'http://yp.yueus.com/action/get_location_data_v2.php?callback=?',
                  dataType: 'JSONP',
                  timeout: 10000,
                  cache: true,
                  beforeSend: function beforeSend() {
                      //self.$el.html('地区加载中...');
                  },
                  success: function success(ret) {
                      var province = JSON.parse(ret.data.province);
                      var city = JSON.parse(ret.data.city);
  
                      // 整合地区数据
                      var province_obj = {};
                      var city_obj = {};
                      var location_data = {};
  
                      for (var i = 0; i < province.length; i++) {
  
                          var province_id = province[i].location_id;
                          var province_name = province[i].location_name;
                          var temp = [];
  
                          for (var j = 0; j < city.length; j++) {
                              var parent_id = city[j].parent_id;
                              if (parent_id == province_id) {
                                  temp.push(city[j].location_name);
                              }
  
                              city_obj[city[j].location_name] = city[j].location_id;
  
                              if (self.locationId == city[j].location_id && self.locationId.slice(0, 6) == province_id) {
                                  self.cur_province_id = province_id;
                                  self.cur_province_name = province_name;
  
                                  console.log(city[j].location_name);
  
                                  self.cur_city_id = city[j].location_id;
                                  self.cur_city_name = city[j].location_name;
                              }
                          }
  
                          location_data[province_name] = temp;
                      }
  
                      console.log(location_data);
  
                      global_location_data = location_data;
  
                      self.$data.slots[0].values = Object.keys(location_data);
                      self.$data.city_obj = city_obj;
  
                      setTimeout(function () {
                          // 初始化设置
                          self.$refs.picker.setSlotValue(0, self.cur_province_name);
                          self.$refs.picker.setSlotValue(1, self.cur_city_name);
                      }, 500);
                  }
              });
          }
      }
  };
  
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<mt-popup v-model=\"visible\" position=\"bottom\" class=\"moji-area\">\n  <mt-picker :slots=\"slots\" @change=\"address_change\" class=\"mint-area-picker\" ref=\"picker\" show-toolbar=\"\">\n    <span class=\"moji-area-action moji-area-cancel\" @click=\"visible = false\">取消</span>\n    <span class=\"moji-area-action moji-area-confirm\" @click=\"confirm\">确认</span>\n  </mt-picker>\n</mt-popup>");

});
