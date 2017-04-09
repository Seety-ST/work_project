define('wap-kids:widget/go_top/gotop', function(require, exports, module) {

  'use strict';
  
  Object.defineProperty(exports, "__esModule", {
      value: true
  });
  exports.default = {
      name: 'go-top',
      data: function data() {
          return {
              show: false,
              scrolled: 0
          };
      },
  
      created: function created() {
          window.addEventListener('scroll', this.handleScroll);
      },
      destroyed: function destroyed() {
          window.removeEventListener('scroll', this.handleScroll);
      },
      watch: {
          scrolled: function scrolled(val) {}
      },
      methods: {
          go_top: function go_top() {
              document.documentElement.scrollTop = document.body.scrollTop = 0;
          },
          handleScroll: function handleScroll() {
              this.scrolled = document.documentElement.scrollTop || document.body.scrollTop;
              if (this.scrolled > 100) {
                  this.show = true;
              } else {
                  this.show = false;
              }
          }
      }
  
  };
  
  ;
  (function (template) {
  
      module && module.exports && (module.exports.template = template);
  
      exports && exports.default && (exports.default.template = template);
  })("<div class=\"go-top-mod\" v-show=\"show\" @click=\"go_top()\">\n    <a href=\"javascript:;\"></a>\n</div>");

});
