define('wap-kids:mine/photos/photos', function(require, exports, module) {

  'use strict';
  
  var Util = require.syncLoad('wap-kids:global/util/index');
  var Lazyload = require.syncLoad('wap-kids:widget/lazyload/index');
  // 加载轮播图模块
  var Swipe = require('wap-kids:widget/swipe/index');
  
  // 使用轮播图
  Vue.use(Swipe);
  
  /**
   * 判断元素是否存在
   * @param  {[type]} needle   [description]
   * @param  {[type]} haystack [description]
   * @return {[type]}          [description]
   */
  function in_array(needle, haystack) {
      var length = haystack.length;
      var gettype = Object.prototype.toString;
      for (var i = 0; i < length; i++) {
          if (haystack[i] == needle) {
              return true;
          }
      }
      return false;
  }
  
  /**
   * 删除数组指定元素
   * @param  {[type]} val [description]
   * @return {[type]}     [description]
   */
  Array.prototype.removeByValue = function (val) {
      for (var i = 0; i < this.length; i++) {
          if (this[i] == val) {
              this.splice(i, 1);
              break;
          }
      }
  };
  
  exports.init = function (params) {
      return {
          el: '#photos-page',
          data: {
              mode: 'view',
              count: 1,
              preview: false,
              preview_img: '',
              zzhb_list: [],
              wysq_list: [],
              selected_list: [],
              title: '我的照片',
              show_tips: true,
              hide_bottom: '',
              order_sn: params.order_sn,
              description: '',
              swiperOption: {
                  pagination: '',
                  prevButton: '',
                  nextButton: '',
                  slidesPerView: 1,
                  paginationClickable: true,
                  spaceBetween: 30,
                  mousewheelControl: true
  
              },
              list: []
          },
          mounted: function mounted() {
              var self = this;
  
              self.reset();
  
              self.get_list();
  
              // 配置使用lazyload
              Vue.use(Lazyload);
  
              window.onresize = function () {
                  self.init_height();
              };
  
              window.onhashchange = function () {
                  var hash = window.location.hash.substr(1);
                  switch (hash) {
                      case '':
                          self.reset();
                          self.preview = false;
                          break;
                      default:
                  }
              };
  
              self.swiperOption.onSlideChangeEnd = function (swiper) {
                  var index = swiper.activeIndex;
                  if (self.list[index].url) {
                      var url = self.list[index].url;
  
                      self.selected_list = [];
  
                      self.selected_list.push(self.get_id_by_url(url));
                  }
              };
          },
          methods: {
              init_height: function init_height() {
                  var self = this;
                  var width = document.body.clientWidth;
                  var value = parseInt((width - 2 * 2) / 3) + 'px';
  
                  var obj = {
                      height: value,
                      width: value
                  };
  
                  for (var i = 0; i < self.list.length; i++) {
                      self.list[i].style = obj;
                  }
              },
              change_img_resize: function change_img_resize(url, size) {
                  var self = this;
  
                  return Util.matching_img_size_v2(url, size);
              },
              show_circle: function show_circle(item) {
                  var self = this;
                  var tag = false;
                  if (self.mode == 'zzhb' && !item.selected) {
                      tag = true;
                  } else if (self.mode == 'wysq' && !item.selected && item.is_up == 0) {
                      tag = true;
                  }
  
                  return tag;
              },
              show_fade: function show_fade(item) {
                  var self = this;
                  var tag = false;
                  if (self.mode == 'zzhb' && item.selected) {
                      tag = true;
                  } else if (self.mode == 'wysq' && (item.selected || item.is_up == 1)) {
                      tag = true;
                  }
  
                  return tag;
              },
              hide_bottom_bar: function hide_bottom_bar() {
                  var self = this;
                  if (!self.hide_bottom) {
                      self.hide_bottom = 'fn-hide';
                  } else {
                      self.hide_bottom = '';
                  }
              },
              show_select: function show_select(item) {
                  var self = this;
                  var tag = false;
                  if (self.mode == 'zzhb' && item.selected) {
                      tag = true;
                  } else if (self.mode == 'wysq' && item.selected) {
                      tag = true;
                  }
  
                  return tag;
              },
              get_list: function get_list(callback) {
                  var self = this;
  
                  callback = callback || function () {};
  
                  Util.request({
                      url: window.__MOJIKIDS_GLOBAL['AJAX_DOMAIN'] + '/mine/get_order_showcase.php',
                      data: {
                          order_sn: self.order_sn
                      },
                      success: function success(response) {
                          var data = response.res.data;
  
                          if (data.result < 1) {
                              Util.toast({
                                  message: data.message,
                                  duration: 2000
                              });
  
                              setTimeout(function () {
                                  window.location.href = window.__MOJIKIDS_GLOBAL['PAGE_URL']['mine'];
                              }, 2000);
  
                              return;
                          }
  
                          self.list = response.res.data.list;
  
                          self.description = response.res.data.description;
  
                          self.init_height();
  
                          if (typeof callback == 'function') {
                              callback.call(this);
                          }
                      },
                      error: function error() {
                          Util.toast({
                              message: '网络异常',
                              duration: 2000
                          });
                      }
                  });
              },
              submit_wysq: function submit_wysq(list) {
                  var self = this;
  
                  if (list.length == 0) {
                      Util.toast({
                          message: '请选择照片',
                          duration: 2000
                      });
                      return;
                  }
  
                  if (list.length > 30) {
                      Util.toast({
                          message: '上墙照片数不能超过30张',
                          duration: 2000
                      });
                      return;
                  }
  
                  var list_str = list.join(',');
  
                  Util.request({
                      url: window.__MOJIKIDS_GLOBAL['AJAX_DOMAIN'] + '/mine/submit_wysq.php',
                      data: {
                          image_ids: list_str
                      },
                      beforeSend: function beforeSend() {
                          Util.loading.open({
                              text: '正在提交...'
                          });
                      },
                      success: function success(response) {
                          var data = response.res.data;
  
                          if (data.result > 0) {
                              Util.toast({
                                  message: '谢谢~\(≧▽≦)/~',
                                  duration: 2000
                              });
  
                              self.get_list(function () {
                                  self.reset();
                                  window.history.back();
                              });
                          } else {
                              Util.toast({
                                  message: data.message,
                                  duration: 2000
                              });
                          }
                      },
                      error: function error() {
                          Util.toast({
                              message: '网络异常',
                              duration: 2000
                          });
                      },
                      complete: function complete() {
                          Util.loading.close();
                      }
                  });
              },
              show_big_img: function show_big_img(url) {
                  var self = this;
  
                  self.preview = true;
  
                  self.preview_img = '';
  
                  self.selected_list = [];
  
                  window.location.hash = '#preview';
  
                  var index = self.get_cur_index(url);
  
                  self.$refs.swiperCom.swiper.slideTo(index);
  
                  self.selected_list.push(self.get_id_by_url(url));
              },
              get_item_by_id: function get_item_by_id(image_id) {
                  var self = this;
  
                  var item = null;
  
                  for (var i = 0; i < self.list.length; i++) {
                      if (self.list[i].image_id && self.list[i].image_id == image_id) {
                          item = self.list[i];
                          break;
                      }
                  }
  
                  return item;
              },
              get_id_by_url: function get_id_by_url(url) {
                  var self = this;
  
                  var id = 0;
  
                  for (var i = 0; i < self.list.length; i++) {
                      if (self.list[i].url && self.list[i].url == url) {
                          id = self.list[i].image_id;
                          break;
                      }
                  }
  
                  return id;
              },
              get_cur_index: function get_cur_index(url) {
                  var self = this;
  
                  var index = 0;
  
                  for (var i = 0; i < self.list.length; i++) {
                      if (self.list[i].url && self.list[i].url == url) {
                          index = i;
                          break;
                      }
                  }
  
                  return index;
              },
              /**
               * 选中某一个图片
               * @return {[type]} [description]
               */
              select_item: function select_item(index) {
                  var self = this;
  
                  if (self.mode != 'zzhb' && self.mode != 'wysq') {
                      // 图片选要在我要上墙才能使用
                      self.show_big_img(self.list[index].url);
                      return;
                  }
  
                  self.preview = false;
  
                  if (self.list[index] && self.list[index].selected == false) {
                      if (self.mode == 'zzhb') {
                          if (self.selected_list.length >= 9) {
                              Util.toast({
                                  message: '最多只能选择9张照片 ￣□￣',
                                  duration: 2000
                              });
                              return;
                          }
  
                          self.list[index].selected = true;
  
                          self.add(self.list[index]);
                      } else if (self.mode == 'wysq') {
                          if (self.list[index].is_up == 0) {
                              self.list[index].selected = true;
                              self.add(self.list[index]);
                          } else {
                              Util.toast({
                                  message: '图片已经上墙',
                                  duration: 2000
                              });
                          }
                      }
                  } else {
                      self.list[index].selected = false;
  
                      self.minus(self.list[index], index);
                  }
              },
              /**
               * 添加一项
               * @param {[type]} item [description]
               */
              add: function add(item) {
                  var self = this;
                  if (!in_array(item.images, self.selected_list)) {
                      item.num = self.selected_list.length + 1;
                      self.selected_list.push(item.image_id);
                  }
              },
              /**
               * 减少一项
               * @param  {[type]} item  [description]
               * @param  {[type]} index [description]
               * @return {[type]}       [description]
               */
              minus: function minus(item, index) {
                  var self = this;
  
                  var temp_num = item.num;
  
                  self.selected_list.removeByValue(item.image_id);
  
                  for (var i = 0; i < self.list.length; i++) {
                      if (self.list[i].num && self.list[i].num > temp_num) {
                          self.list[i].num--;
                      }
                  }
              },
              /**
               * 我要上墙
               */
              wysq: function wysq() {
                  var self = this;
  
                  if (self.preview) {
                      if (self.selected_list.length == 1) {
                          var item = self.get_item_by_id(self.selected_list[0]);
  
                          if (item && item.is_up == 1) {
                              Util.toast({
                                  message: '图片已经上墙',
                                  duration: 2000
                              });
  
                              return;
                          }
                      }
                  } else {
                      self.mode = 'wysq';
                      window.location.hash = self.mode;
                  }
  
                  if (self.selected_list.length > 0) {
  
                      Util.dialog({
                          title: '谢谢您的分享！',
                          message: '提交上墙照片后，该照片有可能被展示在MOJIKIDS官方宣传渠道',
                          showCancelButton: true,
                          confirmButtonText: '确认上墙',
                          cancelButtonText: '取消上墙'
                      },
                      /**
                       * 按钮点中后的回调
                       * @param  {[string]} value [description]
                       * @return {[string]} 返回confirm  || cancel [description]
                       */
                      function (value) {
                          if (value == 'confirm') {
                              self.submit_wysq(self.selected_list);
  
                              //self.preview = false;
                          }
                      });
                  }
              },
              /**
               * 制作海报
               * @return {[type]} [description]
               */
              zzhb: function zzhb() {
                  var self = this;
  
                  if (self.preview) {
                      //self.preview = false;
                  } else {
                      self.mode = 'zzhb';
  
                      window.location.hash = self.mode;
                  }
  
                  if (self.selected_list.length > 9) {
                      Util.toast({
                          message: '制作海报数量不能超过9张',
                          duration: 2000
                      });
                      return;
                  }
  
                  if (self.selected_list.length > 0) {
                      Util.loading.open({
                          text: '正在制作海报...',
                          timeout: 1000
                      });
  
                      setTimeout(function () {
                          Util.loading.close();
  
                          var image_ids = self.selected_list.join(',');
  
                          window.location.href = window.__MOJIKIDS_GLOBAL['PAGE_URL']['bb'] + '?image_ids=' + image_ids + '&car_type=hb';
                      }, 1000);
                  }
              },
              /**
               * 重置
               */
              reset: function reset() {
                  var self = this;
  
                  for (var i = 0; i < self.list.length; i++) {
                      self.list[i].selected = false;
                      self.list[i].num = 0;
                  }
  
                  self.mode = 'view';
                  self.hide_bottom = '';
                  self.selected_list = [];
              },
              qx: function qx() {
                  var self = this;
  
                  self.reset();
  
                  window.history.back();
              }
          }
  
      };
  };
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<section class=\"photos-page\">\n    <div class=\"list-wrapper\" v-show=\"!preview\">\n        <div class=\"top-bar pl15 pr15\" v-show=\"show_tips\">\n            <span>{{description}}</span>\n            <i class=\"delete\" @click=\"show_tips = false\"></i>\n        </div>\n        <ul class=\"container clearfix\">\n            <template v-for=\"(item,index) in list\">\n                <li class=\"item\" :style=\"item.style\">\n                    <div class=\"child\">\n                        <img v-lazy.container=\"change_img_resize(item.url,'s260')\">\n                        <div class=\"extend\" @click=\"select_item(index)\">\n                            <div class=\"fade-mix\" v-show=\"show_fade(item)\"></div>\n                            <span class=\"circle\" v-show=\"show_select(item)\">\n                                <em>{{item.num}}</em>\n                            </span>\n                            <div class=\"none\" v-show=\"show_circle(item)\">\n                                <div class=\"big\"></div>\n                                <div class=\"mid\"></div>\n                            </div>\n                        </div>\n                    </div>\n                </li>\n            </template>\n        </ul>\n    </div>\n\n    <div class=\"preview\" v-show=\"preview\">\n        <!-- 如果你后续需要找到当前实例化后的swiper对象以对其进行一些操作的话，可以自定义配置一个ref属性 -->\n        <swiper :options=\"swiperOption\" ref=\"swiperCom\" class=\"swiper-box\">\n            <!-- 幻灯内容 -->\n            <swiper-slide v-for=\"item in list\"><img @click=\"hide_bottom_bar\" v-bind:src=\"change_img_resize(item.url,'')\" alt=\"\" class=\"w-100\"></swiper-slide>\n        </swiper>\n    </div>\n\n    <div class=\"ui-button-submit-m f16 ui-border-t main-bg footer-bar\" :class=\"hide_bottom\" v-show=\"mode == 'view'\">\n        <div class=\"lbox type-full\">\n            <button type=\"button\" name=\"button\" class=\"ui-button  ui-button-block ui-button-100per \" @click=\"zzhb\">\n                <i class=\"icon-zzhb\"></i>\n                <span>制作海报</span>\n            </button>\n        </div>\n        <div class=\"line\">\n\n        </div>\n        <div class=\"rbox type-full\">\n            <button type=\"button\" name=\"button\" class=\"ui-button  ui-button-block ui-button-100per \" @click=\"wysq\"><i class=\"icon-wysq\"></i><span>我要上墙</span>\n            </button>\n        </div>\n    </div>\n\n    <div class=\"ui-button-submit-m f16 ui-border-t main-bg footer-bar\" v-show=\"mode == 'wysq'\">\n        <div class=\"lbox type-full cancel\">\n            <button type=\"button\" name=\"button\" class=\"ui-button  ui-button-block ui-button-100per \" @click=\"qx\">取消</button>\n        </div>\n        <div class=\"rbox type-full\">\n            <button type=\"button\" name=\"button\" class=\"ui-button  ui-button-block ui-button-100per \" @click=\"wysq\"><i class=\"icon-wysq\"></i><span>我要上墙</span>\n            </button>\n        </div>\n    </div>\n\n    <div class=\"ui-button-submit-m f16 ui-border-t main-bg footer-bar\" v-show=\"mode == 'zzhb'\">\n        <div class=\"lbox type-full cancel\">\n            <button type=\"button\" name=\"button\" class=\"ui-button  ui-button-block ui-button-100per \" @click=\"qx\">取消</button>\n        </div>\n        <div class=\"rbox type-full\">\n            <button type=\"button\" name=\"button\" class=\"ui-button  ui-button-block ui-button-100per \" @click=\"zzhb\">\n                <i class=\"icon-zzhb\"></i>\n                <span>制作海报</span>\n            </button>\n        </div>\n    </div>\n</section>");

});
