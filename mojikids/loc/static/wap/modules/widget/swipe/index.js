define('wap-kids:widget/swipe/index', function(require, exports, module) {

  'use strict';
  
  var _extend = require('wap-kids:widget/swipe/extend');
  
  var _extend2 = _interopRequireDefault(_extend);
  
  var _swipe = require('wap-kids:widget/swipe/swipe');
  
  var _swipe2 = _interopRequireDefault(_swipe);
  
  var _swipeItem = require('wap-kids:widget/swipe/swipe-item');
  
  var _swipeItem2 = _interopRequireDefault(_swipeItem);
  
  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
  
  if (typeof window != 'undefined') window.Swiper = _extend2.default; /**
                                                                       * Vue-awesome-swiper
                                                                       * @author Surmon.me
                                                                       */
  
  var swiper = {
    swiperSlide: _swipeItem2.default,
    swiper: _swipe2.default,
    swiperPlugins: _extend2.default.prototype.plugins,
    install: function install(Vue) {
      Vue.component('swiper', _swipe2.default);
      Vue.component('swiper-slide', _swipeItem2.default);
    }
  };
  
  module.exports = swiper;

});
