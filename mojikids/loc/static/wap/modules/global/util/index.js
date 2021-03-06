define('wap-kids:global/util/index', function(require, exports, module) {

  'use strict';
  
  var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };
  
  /**
   * moji 工具集
   */
  
  // 配置ajax库
  var MOJI_AJAX = window.__ajax__ || $.ajax;
  var STORAGE = window.localStorage;
  
  var in_array = function in_array(needle, haystack) {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
          if (haystack[i] == needle) return true;
      }
      return false;
  };
  
  /**
   * 去空格
   * @param  {[type]} string [description]
   * @return {[type]}        [description]
   */
  var trim = function trim(string) {
      return (string || '').replace(/^[\s\uFEFF]+|[\s\uFEFF]+$/g, '');
  };
  
  /**
   * 判断css是否存在
   * @param  {[type]}  el  [description]
   * @param  {[type]}  cls [description]
   * @return {Boolean}     [description]
   */
  var hasClass = function hasClass(el, cls) {
      if (!el || !cls) return false;
      if (cls.indexOf(' ') != -1) throw new Error('className should not contain space.');
      if (el.classList) {
          return el.classList.contains(cls);
      } else {
          return (' ' + el.className + ' ').indexOf(' ' + cls + ' ') > -1;
      }
  };
  
  /**
   * 增加样式
   * @param {[type]} el  [description]
   * @param {[type]} cls [description]
   */
  var addClass = function addClass(el, cls) {
      if (!el) return;
      var curClass = el.className;
      var classes = (cls || '').split(' ');
  
      for (var i = 0, j = classes.length; i < j; i++) {
          var clsName = classes[i];
          if (!clsName) continue;
  
          if (el.classList) {
              el.classList.add(clsName);
          } else {
              if (!hasClass(el, clsName)) {
                  curClass += ' ' + clsName;
              }
          }
      }
      if (!el.classList) {
          el.className = curClass;
      }
  };
  
  /**
   * 删除样式
   * @param  {[type]} el  [description]
   * @param  {[type]} cls [description]
   * @return {[type]}     [description]
   */
  var removeClass = function removeClass(el, cls) {
      if (!el || !cls) return;
      var classes = cls.split(' ');
      var curClass = ' ' + el.className + ' ';
  
      for (var i = 0, j = classes.length; i < j; i++) {
          var clsName = classes[i];
          if (!clsName) continue;
  
          if (el.classList) {
              el.classList.remove(clsName);
          } else {
              if (hasClass(el, clsName)) {
                  curClass = curClass.replace(' ' + clsName + ' ', ' ');
              }
          }
      }
      if (!el.classList) {
          el.className = trim(curClass);
      }
  };
  
  /**
   * 绑定事件
   * @type {[type]}
   */
  var bindEvent = function () {
      if (document.addEventListener) {
          return function (element, event, handler) {
              if (element && event && handler) {
                  element.addEventListener(event, handler, false);
              }
          };
      } else {
          return function (element, event, handler) {
              if (element && event && handler) {
                  element.attachEvent('on' + event, handler);
              }
          };
      }
  }();
  
  /**
   * 移除事件
   * @type {[type]}
   */
  var unbindEvent = function () {
      if (document.removeEventListener) {
          return function (element, event, handler) {
              if (element && event) {
                  element.removeEventListener(event, handler, false);
              }
          };
      } else {
          return function (element, event, handler) {
              if (element && event) {
                  element.detachEvent('on' + event, handler);
              }
          };
      }
  }();
  
  /**
   * 绑定一次事件
   * @param  {[type]}   el    [description]
   * @param  {[type]}   event [description]
   * @param  {Function} fn    [description]
   * @return {[type]}         [description]
   */
  var bindOnce = function bindOnce(el, event, fn) {
      var listener = function listener() {
          if (fn) {
              fn.apply(this, arguments);
          }
          unbindEvent(el, event, listener);
      };
      bindEvent(el, event, listener);
  };
  
  /**
   * cookie对象
   * @return {[type]} [description]
   */
  var cookie = function cookie() {
      // Helpers
      var doc = window.document;
      var MILLISECONDS_OF_DAY = 24 * 60 * 60 * 1e3;
  
      function encode(str) {
          return encodeURIComponent(str);
      }
  
      function decode(str) {
          return decodeURIComponent(str.replace(/\+/g, " "));
      }
  
      function isNotEmptyStr(str) {
          return typeof str === "string" && str !== "";
      }
  
      var cookie_obj = {
          /**
           * 获取cookie值
           *
           * @param name
           * @param options
           * @returns {*}
           */
          get: function get(name, options) {
              options = options || {};
              var ret, matchArr;
              if (isNotEmptyStr(name)) {
                  if (matchArr = String(doc.cookie).match(new RegExp("(?:^| )" + name + "(?:(?:=([^;]*))|;|$)"))) {
                      if (matchArr[1]) {
                          ret = !options.decode ? matchArr[1] : decode(matchArr[1]);
                      }
                  }
              }
              return ret;
          },
          /**
           * 设置cookie
           *
           * @param name
           * @param val
           * @param options
           * @returns {*|string}
           */
          set: function set(name, val, options) {
              options = options || {};
              var text = String(options.encode ? encode(val) : val),
                  date = options.expires,
                  // 过期时间, 单位:天
              domain = options.domain || yue_domain,
                  // 有效域名
              path = options.path || yue_path,
                  // 有效路径
              secure = options.secure;
              // 是否安全设置
              // 从当前时间开始，多少天后过期
              if (typeof date === "number") {
                  date = new Date();
                  date.setTime(date.getTime() + options.expires * MILLISECONDS_OF_DAY);
              }
              // expiration date
              if (date instanceof Date) {
                  text += "; expires=" + date.toUTCString();
              }
              // domain
              if (isNotEmptyStr(domain)) {
                  text += "; domain=" + domain;
              }
              // path
              if (isNotEmptyStr(path)) {
                  text += "; path=" + path;
              }
              // secure
              if (secure) {
                  text += "; secure";
              }
              doc.cookie = name + "=" + text;
              return text;
          },
          /**
           * 删除cookie
           * @param name
           * @param options
           * @returns {*|string}
           */
          del: function del(name, options) {
              return this.set(name, "", {
                  expires: -1,
                  domain: options.domain || yue_domain,
                  path: options.path || yue_path,
                  secure: options.secure
              });
          }
      };
  
      return cookie_obj;
  };
  
  /**
   * 本地存储
   * @type {Object}
   */
  var storage = {
      /**
       * 前缀
       */
      prefix: 'mojikids-app-',
      /**
       * 设置
       * @param key
       * @param val
       * @returns {*}
       */
      set: function set(key, val) {
          try {
              if (!STORAGE || typeof STORAGE == 'undefined') {
                  return false;
              }
  
              if (typeof val == 'undefined') {
                  return STORAGE.remove(key);
              }
  
              STORAGE.setItem('mojikids-app-' + key, JSON.stringify(val));
  
              return val;
          } catch (err) {
              console.warn(err);
  
              return false;
          }
      },
      /**
       * 获取
       * @param key
       * @returns {*}
       */
      get: function get(key) {
          try {
              var item = STORAGE.getItem('mojikids-app-' + key);
  
              if (!item) {
                  return item;
              } else {
                  return JSON.parse(item);
              }
          } catch (err) {
              console.warn(err);
  
              return false;
          }
      },
      /**
       * 删除
       * @param key
       * @returns {*}
       */
      remove: function remove(key) {
          return STORAGE.removeItem('mojikids-app-' + key);
      }
  };
  
  /**
   * 异步请求
   * @param  {[type]} options [description]
   * @return {[type]}         [description]
   */
  var request = function request(options) {
      var options = options || {};
  
      var url = options.url;
      var data = options.data || {};
      var cache = options.cache || false;
      var _beforeSend = options.beforeSend || function () {};
      var _success = options.success || function () {};
      var _error = options.error || function () {};
      var complete = options.complete || function () {};
      var type = options.type || 'GET';
      var dataType = options.dataType || 'json';
      var async = options.async == null ? true : false;
      var headers = options.headers || {};
      var timeout = options.timeout || 10000;
  
      if (!cache) {
          data._ = new Date().getTime();
      }
  
      console.info('ajax request params:', data);
  
      // jQuery版本的请求
      var ajax_obj = MOJI_AJAX({
          url: url,
          type: type,
          data: data,
          cache: cache,
          async: async,
          dataType: dataType,
          beforeSend: function beforeSend(XMLHttpRequest, settings) {
              console.log('%c请求前，请求路径是:' + url, 'color:#66ccff');
              _beforeSend(XMLHttpRequest, settings);
          },
          success: function success(data, textStatus) {
              //成功回调方法扩展处理
              console.log('%c请求成功，请求路径是:' + url, 'color:#00dd00');
              _success(data, textStatus);
          },
          error: function error(XMLHttpRequest, textStatus, errorThrown) {
              //错误方法扩展处理
              console.log('%c请求失败，请求路径是:' + url, 'color:#ff5544');
              //console.log('http://www.poco.cn&err_str='+XMLHttpRequest.response);
              _error(XMLHttpRequest, textStatus, errorThrown);
          },
          complete: options.complete
      });
  
      return ajax_obj;
  };
  
  /**
   * 返回UA对象
   * @return {[type]} [description]
   */
  var ua_func = function ua_func() {
      var ua = {};
      var win = window;
      var nav = win.navigator;
      var app_version = nav.appVersion;
  
      //访问来自手机
      ua.isMobile = /(iphone|ipod|android|ios|ipad|nokia|blackberry|tablet|symbian)/.test(nav.userAgent.toLowerCase());
  
      //手机系统
      ua.isAndroid = /android/gi.test(app_version);
      ua.isIDevice = /iphone|ipad/gi.test(app_version);
      ua.isTouchPad = /hp-tablet/gi.test(app_version);
      ua.isIpad = /ipad/gi.test(app_version);
  
      ua.otherPhone = !(ua.isAndroid || ua.isIDevice);
  
      //浏览器品牌类型
      ua.is_uc = /uc/gi.test(app_version);
      ua.is_chrome = /CriOS/gi.test(app_version) || /Chrome/gi.test(app_version);
      ua.is_qq = /QQBrowser/gi.test(app_version);
      ua.is_real_safari = /safari/gi.test(app_version) && !ua.is_chrome && !ua.is_qq; //真正的原生IOS safari浏览器
      //iphone safari是否全屏
      ua.is_standalone = window.navigator.standalone ? true : false;
  
      //手机版本
      if (ua.isAndroid) {
          var android_version = parseFloat(app_version.slice(app_version.indexOf("Android") + 8));
          ua.android_version = android_version;
      } else if (ua.isIDevice) {
          var v = app_version.match(/OS (\d+)_(\d+)_?(\d+)?/);
          var ios_version = false;
          if (v) {
              var ios_version = v[1];
  
              if (v[2]) ios_version += '.' + v[2];
              if (v[3]) ios_version += '.' + v[3];
          } else {
              ios_version = false;
          }
  
          ua.ios_version = ios_version;
      }
  
      ua.is_iphone_safari_no_fullscreen = ua.isIDevice && ua.ios_version < "7" && !ua.isIpad && ua.is_real_safari && !ua.is_standalone;
  
      ua.is_yue_app = /yue_pai/.test(app_version);
      ua.is_yue_seller = /yueseller/.test(app_version);
      //weixin
      ua.is_weixin = /MicroMessenger/gi.test(app_version);
      // wap
      ua.is_normal_wap = !ua.is_yue_app && !ua.is_weixin;
      return ua;
  };
  
  /**
   * 切换图片size
   * @param img_url
   * @param size
   * @returns {*}
   */
  function change_img_resize(img_url, size) {
      var size_str = '';
  
      size = size || '';
  
      if (in_array(size, [120, 320, 165, 640, 600, 145, 440, 230, 260]) != -1) {
          size_str = '_' + size;
      } else {
          size_str = '';
      }
      // 解析img_url
  
      var url_reg = /^http:\/\/(img|image)\d*(-c|-wap|-d)?(.poco.cn.*|.yueus.com.*)\.jpg|gif|png|bmp/i;
  
      var reg = /_(32|64|86|100|145|165|260|320|440|468|640).(jpg|png|jpeg|gif|bmp)/i;
  
      if (url_reg.test(img_url)) {
          if (reg.test(img_url)) {
              img_url = img_url.replace(reg, size_str + '.$2');
          } else {
              img_url = img_url.replace('/(\.\d*).jpg|.jpg|.gif|.png|.bmp/i', size_str + ".jpg"); //两个.jpg为兼容软件的上传图片名
          }
      }
  
      return img_url;
  };
  
  /*
  * @param {Object} target 目标对象。
  * @param {Object} source 源对象。
  * @param {boolean} deep 是否复制(继承)对象中的对象。
  * @returns {Object} 返回继承了source对象属性的新对象。
  */
  var extend = function extend(target, /*optional*/source, /*optional*/deep) {
      target = target || {};
      var sType = typeof source === 'undefined' ? 'undefined' : _typeof(source),
          i = 1,
          options;
      if (sType === 'undefined' || sType === 'boolean') {
          deep = sType === 'boolean' ? source : false;
          source = target;
          target = this;
      }
      if ((typeof source === 'undefined' ? 'undefined' : _typeof(source)) !== 'object' && Object.prototype.toString.call(source) !== '[object Function]') source = {};
      while (i <= 2) {
          options = i === 1 ? target : source;
          if (options != null) {
              for (var name in options) {
                  var src = target[name],
                      copy = options[name];
                  if (target === copy) continue;
                  if (deep && copy && (typeof copy === 'undefined' ? 'undefined' : _typeof(copy)) === 'object' && !copy.nodeType) target[name] = this.extend(src || (copy.length != null ? [] : {}), copy, deep);else if (copy !== undefined) target[name] = copy;
              }
          }
          i++;
      }
      return target;
  };
  
  /**
   * 地址定位
   * @param  {[type]} options [description]
   * @return {[type]}         [description]
   */
  var open_location = function open_location(options) {
      options = options || {};
  
      var ua = ua_func();
      if (ua.is_weixin) {
          if (!wx) {
              alert('wx object is undefined');
              return;
          }
  
          options.wx_prams = options.wx_prams || {};
  
          var default_wx_params = {
              latitude: 0, // 纬度，浮点数，范围为90 ~ -90
              longitude: 0, // 经度，浮点数，范围为180 ~ -180。
              name: '', // 位置名
              address: '', // 地址详情说明
              scale: 20, // 地图缩放级别,整形值,范围从1~28。默认为最大
              infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
          };
  
          var params = extend(default_wx_params, options.wx_prams);
  
          params.latitude = params.latitude * 1;
          params.longitude = params.longitude * 1;
  
          wx.openLocation(params);
  
          return {
              type: 'wx'
          };
      } else {
          var gaode_params = options.gaode_params || {};
          var map = "";
  
          //<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=355b76e40579761f854c58b9a597443b"></script>
  
          if (!gaode_params.longitude || !gaode_params.latitude) {
              alert('longitude or latitude is error');
              return;
          }
  
          if (typeof gaode_params.before_init == 'function') {
              gaode_params.before_init.call(this);
          }
  
          var default_gaode_params = {
              resizeEnable: true,
              center: [gaode_params.longitude, gaode_params.latitude],
              zoom: 18 };
  
          var params = extend(default_gaode_params, gaode_params);
  
          if (!map) {
              var head = document.getElementsByTagName('head')[0];
              var script = document.createElement('script');
              script.type = 'text/javascript';
              script.src = 'http://webapi.amap.com/maps?v=1.3&key=355b76e40579761f854c58b9a597443b';
              head.appendChild(script);
  
              script.onload = function (am) {
                  console.log(AMap);
  
                  setTimeout(function () {
                      map = new AMap.Map(gaode_params.el, params);
  
                      new AMap.Marker({
                          map: map
                      });
                  }, 500);
              };
  
              return {
                  type: 'gaode'
              };
          }
      }
  };
  
  /**
   * 新版图片转换尺寸
   * @param $img_url
   * @param string $size
              s32	方形缩略图,边长为32
              s64	方形缩略图,边长为64
              s86	方形缩略图,边长为86
              s100	方形缩略图,边长为100
              s145	方形缩略图,边长为145
              s145-hd	方形缩略图,边长为145,增加锐化效果
              s165	方形缩略图,边长为165
              s260	方形缩略图,边长为260
              s260-hd	方形缩略图,边长为260,增加锐化效果
              s468	方形缩略图,边长为468
              s640	方形缩略图,边长为640
              m165	等比例最小边缩略图,最小边为165
              m320	等比例最小边缩略图,最小边为320
              m440	等比例最小边缩略图,最小边为440
              m440-hd	等比例最小边缩略图,最小边为440,增加锐化效果
              m640	等比例最小边缩略图,最小边为640
              m640-hd	等比例最小边缩略图,最小边为640,增加锐化效果
              l165	等比例最大边缩略图,最大边为165
              l320	等比例最大边缩略图,最大边为320
              l440	等比例最大边缩略图,最大边为440
              l440-hd	等比例最大边缩略图,最大边为440,增加锐化效果
              l640	等比例最大边缩略图,最大边为640
              l640-hd	等比例最大边缩略图,最大边为640,增加锐化效果
   *
   * @return mixed
   */
  var new_yueyue_resize_act_img_url = function new_yueyue_resize_act_img_url(img_url) {
      var size = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
  
      var size_str = '';
  
      size = size || '';
  
      if (in_array(size, ['s32', 's64', 's86', 's100', 's145', 's145-hd', 's165', 's260', 's260-hd', 's468', 's640', 'm165', 'm320', 'm440', 'm440-hd', 'm640', 'm640-hd', 'l165', 'l320', 'l440', 'l440-hd', 'l640', 'l640-hd'])) {
          size_str = '_' + size;
      } else {
          size_str = '';
      }
  
      var reg = /(jpg|png|jpeg|gif|bmp)_(s32|s64|s86|s100|s145|s145-hd|s165|s260|s260-hd|s468|s640|m165|m320|m440|m440-hd|m640|m640-hd|l165|l320|l440|l440-hd|l640|l640-hd)/i;
  
      if (reg.test(img_url)) {
          img_url = img_url.replace(reg, "$1" + size_str);
      } else {
          img_url = img_url.replace(/(.jpg|.jpg|.gif|.png|.bmp)/i, "$1" + size_str); //两个.jpg为兼容软件的上传图片名
      }
  
      return img_url;
  };
  
  // 封装ui组件
  var Toast = require.syncLoad('wap-kids:widget/toast/index');
  var Dialog = require.syncLoad('wap-kids:widget/dialog/index');
  var Loading = require.syncLoad('wap-kids:widget/loading/index');
  
  if (window.Vue) {
      // 全局使用toast
      Vue.$toast = Vue.prototype.$toast = Toast;
      // 全局使用Dialog
      Vue.$dialog = Vue.prototype.$dialog = Dialog;
      // 全局使用loading
      Vue.$loading = Vue.prototype.$loading = Loading;
  }
  
  module.exports = {
      on: bindEvent,
      off: unbindEvent,
      once: bindOnce,
      hasClass: hasClass,
      addClass: addClass,
      removeClass: removeClass,
      cookie: cookie(),
      storage: storage,
      request: request,
      ua: ua_func(),
      matching_img_size: change_img_resize,
      matching_img_size_v2: new_yueyue_resize_act_img_url,
      toast: Vue.$toast,
      dialog: Vue.$dialog,
      loading: Vue.$loading,
      extend: extend,
      open_location: open_location
  };

});
