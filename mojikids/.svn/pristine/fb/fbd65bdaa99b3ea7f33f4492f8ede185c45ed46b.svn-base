define('wap-kids:widget/date/date', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
      value: true
  });
  var Util = require.syncLoad('wap-kids:global/util/index');
  exports.default = {
      // 组件标记
      name: 'moji-date',
      props: {
          //显示日期
          show_date: {
              type: Boolean,
              default: false
          },
          //约满的日期
          canbook_days_list: {
              type: Array
          },
          //行程ID
          schedule_id: {
              type: String
          },
          //推广码
          c_code: {
              type: String,
              default: ""
          },
          //推广版本码
          v_code: {
              type: String,
              default: ""
          }
  
      },
      created: function created() {
          this.initData(null);
      },
      data: function data() {
          return {
              currentMonth: 1, //当前月
              currentYear: 1970, //当前年
              currentWeek: 1, //所在星期的第几天
              days: [], //当月显示的日期时间
              choose_time: "", //选中的时间，格式化：年-月-日
              choose_time_format: "", //选中的时候，格式化：例如2017年12月20日（周一）
              child_canbook_days_list: this.canbook_days_list, //可以预约的日期
              show_date_detail_list: false, //是否展示时分列表
              date_detail_list: [], //选中日对应的时分列表
              date_detail_key_list: {}, //日期值对应精确时分的数组,key-value对应形式，key形式：2017-01-01对应一个数组
              config_week_array: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"] //配置的日期数组
          };
      },
  
      computed: {},
      methods: {
          initData: function initData(cur) {
              var date;
              if (cur) {
                  date = new Date(cur);
              } else {
                  date = new Date();
              }
              this.currentYear = date.getFullYear(); //当前传入时间的年份
              this.currentMonth = date.getMonth() + 1; //当前传入时间的月份
  
              //当月第一天的所在的星期的第几天
              date.setDate(1); //date对象设置为当月第一天
              this.currentWeek = date.getDay(); // 1...6,0//当前传入时间所在星期的第几天
  
              /**日期结构**/
              console.log(this.currentWeek);
              /*if (this.currentWeek == 0) {
                  this.currentWeek = 7;//如果是0，表示星期日，则设置为第7个（排列结构为周一到周日）
              }*/
              this.currentWeek = this.currentWeek + 1; //周日为结构的第一个，为0的时候+1（排列结构为周日到周六）
              /**日期结构end**/
  
              var the_first_day = date.getDate(); //当前时间当月的第一天
              var str = this.formatDate(this.currentYear, this.currentMonth, the_first_day); //对时间进行形式包装处理
  
              console.log("当月的第一天:" + str + "," + this.currentWeek);
  
              //处理形式
              var child_canbook_days_list_format = [];
              var child_canbook_days_list = this.child_canbook_days_list;
              var child_canbook_days_list_len = child_canbook_days_list.length;
  
              //清空日期值对应的准确时间数组
              this.date_detail_key_list = {};
              for (var i = 0; i < child_canbook_days_list_len; i++) {
                  child_canbook_days_list_format.push(new Date(child_canbook_days_list[i]["date"]));
                  //日期key跟日期的时间点形成对应
                  var detail_list_key = child_canbook_days_list[i]["date"];
                  this.date_detail_key_list[detail_list_key] = child_canbook_days_list[i]["timezone"];
              }
  
              //console.log(child_canbook_days_list_format);
              this.days.length = 0;
              //通过当月头一天所在星期的第几天进行结构梳理
              for (var i = this.currentWeek - 1; i >= 0; i--) {
                  var d = new Date(str);
                  d.setDate(d.getDate() - i); //当前传入时间的当月的一天
                  //console.log("y:" + d);
                  var full_class = "";
  
                  if (!this.in_array(d, child_canbook_days_list_format)) {
                      full_class = "full";
                  }
  
                  var pre_date_obj = {
                      date: d,
                      active_class: "",
                      full_class: full_class
  
                  };
                  this.days.push(pre_date_obj);
              }
              for (var i = 1; i <= 42 - this.currentWeek; i++) {
                  var d = new Date(str);
                  d.setDate(d.getDate() + i);
  
                  var full_class = "";
  
                  if (!this.in_array(d, child_canbook_days_list_format)) {
                      full_class = "full";
                  }
  
                  var pos_date_obj = {
                      date: d,
                      active_class: "",
                      full_class: full_class
                  };
                  this.days.push(pos_date_obj);
              }
          },
          //判断值是否在数组
          in_array: function in_array(stringToSearch, arrayToSearch) {
              for (var s = 0; s < arrayToSearch.length; s++) {
                  var thisEntry = arrayToSearch[s].toString();
                  //console.log("输入值"+stringToSearch);
                  //console.log("数组值"+thisEntry);
                  if (thisEntry == stringToSearch) {
                      return true;
                  }
              }
              return false;
          },
          //点击了选择日期
          pick: function pick(date) {
              console.log("点中了时间");
              //点击了非本月的，返回
              if (date.getMonth() + 1 != this.currentMonth) {
                  console.log("点击了非本月的");
                  return;
              }
              //点击了约满档期或者历史档期，返回
              var days_len = this.days.length;
              var cur_days = this.days;
              for (var i = 0; i < days_len; i++) {
                  if (cur_days[i]["date"] == date) {
                      if (cur_days[i]["full_class"] == "full") {
                          console.log("点击了档期满的");
                          return;
                      }
                  }
              }
  
              //点中处理，设定样式
              for (var j = 0; j < days_len; j++) {
                  cur_days[j]["active_class"] = "";
                  if (date == cur_days[j]["date"]) {
                      if (date.getDate() > 9) {
                          cur_days[j]["active_class"] = "active-big";
                      } else {
                          cur_days[j]["active_class"] = "active-little";
                      }
                  }
              }
  
              //获取到点击日期的对应时间细分列表
              var date_key = this.formatDate(date.getFullYear(), date.getMonth() + 1, date.getDate());
              console.log("date_key" + date_key);
  
              this.date_detail_list = this.date_detail_key_list[date_key];
  
              this.show_date_detail_list = true;
              this.choose_time = this.formatDate(date.getFullYear(), date.getMonth() + 1, date.getDate());
  
              this.choose_time_format = this.formatDate_text(date.getFullYear(), date.getMonth() + 1, date.getDate(), date);
  
              //设置选中
              var detail_list_len = this.date_detail_list.length;
              for (var i = 0; i < detail_list_len; i++) {
                  this.date_detail_list[i]["click_cur"] = "";
              }
          },
          ajax_get_date: function ajax_get_date(d, choose_year_month) {
              var self = this;
              var schedule_id = self.schedule_id;
              var c_code = self.c_code;
              var v_code = self.v_code;
  
              Util.request({
                  url: window.__MOJIKIDS_GLOBAL['AJAX_DOMAIN'] + 'order/get_date.php',
                  type: 'GET',
                  cache: false,
                  data: {
                      date: choose_year_month,
                      schedule_id: schedule_id,
                      c: c_code,
                      v: v_code
                  },
                  beforeSend: function beforeSend() {
                      Util.toast({
                          message: '获取日期中...',
                          position: 'middle',
                          duration: 1000
                      });
                  },
                  success: function success(respond) {
  
                      console.log(respond.res.list);
  
                      if (respond.res.list.length > 0) {
                          var list_len = respond.res.list.length;
                      } else {
                          var list_len = 0;
                      }
  
                      console.log("返回长度" + list_len);
  
                      //清空可以订的日期
                      self.child_canbook_days_list = [];
                      for (var i = 0; i < list_len; i++) {
                          if (respond.res.list[i]) {
                              self.child_canbook_days_list.push(respond.res.list[i]);
                          }
                      }
  
                      self.initData(self.formatDate(d.getFullYear(), d.getMonth() + 1, 1)); //上个月第一天，然后初始化
                      self.show_date_detail_list = false;
                  },
                  error: function error() {
                      Util.toast({
                          message: '获取档期日期失败，请切换日期再试',
                          position: 'middle',
                          duration: 1000
                      });
                  }
              });
          },
          pickPre: function pickPre(year, month) {
              //  setDate(0); 上月最后一天
              //  setDate(-1); 上月倒数第二天
              //  setDate(dx) 参数dx为 上月最后一天的前后dx天
              var d = new Date(this.formatDate(year, month, 1));
              d.setDate(0);
  
              console.log("上个年月" + d.getFullYear() + (d.getMonth() + 1));
  
              //清理精确时间
              this.date_detail_list = {};
              //选中的年月
              var choose_year_month = this.formatDate_ym(d.getFullYear(), d.getMonth() + 1);
              console.log(choose_year_month);
              //异步获取数据
              this.ajax_get_date(d, choose_year_month);
          },
          pickNext: function pickNext(year, month) {
  
              var d = new Date(this.formatDate(year, month, 1));
              d.setDate(42); //上个月后35天，为当月的下个月时间点
  
              console.log("下个年月" + d.getFullYear() + (d.getMonth() + 1));
  
              //清理精确时间
              this.date_detail_list = {};
              //选中的年月
              var choose_year_month = this.formatDate_ym(d.getFullYear(), d.getMonth() + 1);
              console.log(choose_year_month);
              //异步获取数据
              this.ajax_get_date(d, choose_year_month);
          },
          pick_detail: function pick_detail(title, value, index) {
              console.log("选中的时间为" + this.choose_time + " " + title);
  
              console.log("item的value值为" + value);
  
              console.log("选中的index值：" + index);
              //设置选中
              var detail_list_len = this.date_detail_list.length;
              for (var i = 0; i < detail_list_len; i++) {
                  this.date_detail_list[i]["click_cur"] = "";
                  if (i == index) {
                      this.date_detail_list[i]["click_cur"] = "detail-list-item-cur";
                  }
              }
  
              //返回给父组件
              console.log(this.choose_time);
              //var this_detail_date = JSON.parse(JSON.stringify(this.choose_time))+" "+title;//年月日加时分
              var this_detail_date = JSON.parse(JSON.stringify(this.choose_time)); //年月日
              var this_detail_date_value = value; //选中的精确时间的value值
  
              var this_detail_date_format = JSON.parse(JSON.stringify(this.choose_time_format)) + " " + title; //符合格式的时间值
  
              console.log(this_detail_date);
              this.$emit('pick_detail', this_detail_date, this_detail_date_value, this_detail_date_format);
          },
          // 返回 类似 2016-01-02 格式的字符串
          formatDate: function formatDate(year, month, day) {
              var y = year;
              var m = month;
              if (m < 10) m = "0" + m;
              var d = day;
              if (d < 10) d = "0" + d;
              return y + "-" + m + "-" + d;
          },
          //返回 类似 201601格式的字符串
          formatDate_ym: function formatDate_ym(year, month) {
              var y = year;
              var m = month;
              if (m < 10) m = "0" + m;
              return y + "" + m;
          },
          //返回结构 类似 格式化：例如2017年12月20日（周一）
          formatDate_text: function formatDate_text(year, month, day, date_obj) {
              var config_week_array = this.config_week_array;
              var choose_week_day = date_obj.getDay();
              var choose_week_text = config_week_array[choose_week_day];
              var return_val = year + "年" + month + "月" + day + "日（" + choose_week_text + "）";
              return return_val;
          }
      }
  };
  
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<div class=\"date-container\" id=\"date-container\" v-if=\"show_date\">\n    <div class=\"date-nav ui-border-b clearfix\">\n        <div class=\"nav-icon fl\" @click=\"pickPre(currentYear,currentMonth)\">\n            <i class=\"icon-allow-grey icon-allow-grey-left\"></i>\n        </div>\n        <div class=\"nav-text tc f16 color-111 dib fl\">{{ currentYear }}年{{ currentMonth }}月</div>\n        <div class=\"nav-icon fr\" @click=\"pickNext(currentYear,currentMonth)\">\n            <i class=\"icon-allow-grey\"></i>\n        </div>\n    </div>\n\n    <div class=\"date-content\">\n        <div class=\"content-tips pt10 pb5 color-666 f12 tc\">\n            注：<span class=\"color-css\">灰色</span>为已约满，<span class=\"main-color\">黄色</span>为可预约的哟~\n        </div>\n        <div class=\"content-main\">\n            <!--时间内容-->\n            <ul class=\"weekdays color-111\">\n                <li>日</li>\n                <li>一</li>\n                <li>二</li>\n                <li>三</li>\n                <li>四</li>\n                <li>五</li>\n                <li>六</li>\n            </ul>\n            <ul class=\"days\">\n                <li @click=\"pick(day.date)\" v-for=\"day in days\">\n                    <span v-if=\"day.date.getMonth()+1 != currentMonth\" class=\"other-month\">{{ day.date.getDate() }}</span>\n                    <span v-else=\"\" :class=\"[day.active_class,day.full_class]\">{{ day.date.getDate() }}</span>\n                </li>\n            </ul>\n\n        </div>\n    </div>\n\n    <div class=\"date-detail-list\">\n        <div class=\"detail-list-title\">请选择时间点：<span class=\"title-tips main-color\" v-show=\"show_date_detail_list ? false : true\">（请先选择日期）</span></div>\n        <ul class=\"detail-list clearfix\" v-show=\"show_date_detail_list\">\n            <li class=\"detail-list-item\" :class=\"item.click_cur\" v-for=\"(item,index) in date_detail_list\" @click=\"pick_detail(item.title,item.value,index)\">{{ item.title }}</li>\n        </ul>\n    </div>\n</div>");

});
