define("pc-huipai:go_top",function(){"use strict";function e(e){var n=this,e=e||{};n.$render_ele=e.render_ele,n.init()}return e.prototype={init:function(){var e=this;e.render(),e.setup_event()},render:function(){var e=this,n=Handlebars.template(function(e,n,t,i,o){return this.compilerInfo=[4,">= 1.0.0"],t=this.merge(t,e.helpers),o=o||{},'<div id="go-top" class="go-top-mod" style="display:none;">\n    <a href="javascript:;"></a>\n</div>'}),t=e.$render_ele.append(n());e.ele=$(t).find("#go-top"),e.ele.on("click",function(e){e.preventDefault(),$("html,body").animate({scrollTop:0},200)})},setup_event:function(){var e=this;$(window).scroll(function(){$(window).scrollTop()>150?e.ele.fadeIn(500):e.ele.fadeOut(300)})}},e});