/**
 * Vue-awesome-swiper
 * @author Surmon.me
 */

import Swiper from './extend.js'
import SwiperComponent from './swipe.vue'
import SlideComponent from './swipe-item.vue'

if (typeof window != 'undefined') window.Swiper = Swiper

const swiper = {
  swiperSlide: SlideComponent,
  swiper: SwiperComponent,
  swiperPlugins: Swiper.prototype.plugins,
  install: function(Vue) {
    Vue.component('swiper', SwiperComponent)
    Vue.component('swiper-slide', SlideComponent)
  }
}

module.exports = swiper
