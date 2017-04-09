define('wap-huipai:global/header/main', function(require, exports, module) {

  /**
   * Created by hudingwen on 15/8/3.
   */
  'use strict';
  
  /**依赖文件，要在注释上使用**/
  
  
  // var $ = require('zepto');
  
  
  function header_class(options)
  {
      var self = this;
  
      self.init(options);
  }
  
  header_class.prototype =
  {
      init : function(options)
      {
          var self = this;
          self.options =  options;
          self.options.className = options.className || '';
          self.options.style = options.style || '';
          self.render_ele = options.ele;
          self.left_side_html =  $.trim(options.left_side_html);
          if (self.options.left_icon_show == null) 
          {
              self.options.left_icon_show = true ;
          }
  
          self.config = true ;
  
  
          //如果头部隐藏，要把当前页节点margin-top改为0
          if (!options.header_show)
          {
              self.set_bar_css()
          }
  
          // 如果标题为空，标题读取文档标题
          if (!options.title.trim())
          {
              var document_title = document.title;
              self.options = $.extend(true,{},self.options,{title : document_title, left_side_html:self.left_side_html});
          } 
   
          var environment_type = window.__mall_environment_type;
  
          if(environment_type == 'dev')
          {
              options.title = '开发机-'+options.title;
          } 
          if(environment_type == 'test')
          {
              options.title = '测试机-'+options.title;
          }
  
          document.title = options.title;
  
          self.render(self.rend_ele);
  
        
              self.set_bar_css('45px',true);
              self.render_ele.css('height','auto');
         
  
          return self;
  
      },
      render: function (dom) {
          // tpl后缀的文件也可以用于模板嵌入，相比handlebars
          // tpl文件不具有模板变量功能，嵌入后只是作为字符串使
          // 用，tpl文件嵌入之前可以被插件压缩，体积更小。
          // handlebars由于缺少相应的压缩插件因此暂时不能在预
          // 编译阶段做压缩。选择tpl还是handlebars可以自由选
          // 择
  
          var self = this;
          var data = self.options;
          var template  = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
    this.compilerInfo = [4,'>= 1.0.0'];
  helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
    var buffer = "", stack1, functionType="function", self=this, escapeExpression=this.escapeExpression;
  
  function program1(depth0,data) {
    
    var buffer = "", stack1, helper;
    buffer += "\n	<!-- header start -->\n	<header class=\"global-header ui-border-b ";
    if (helper = helpers.className) { stack1 = helper.call(depth0, {hash:{},data:data}); }
    else { helper = (depth0 && depth0.className); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
    buffer += escapeExpression(stack1)
      + "\" style=\"";
    if (helper = helpers.style) { stack1 = helper.call(depth0, {hash:{},data:data}); }
    else { helper = (depth0 && depth0.style); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
    buffer += escapeExpression(stack1)
      + "\">\n		<div class=\"wbox clearfix\">\n\n			";
    stack1 = helpers['if'].call(depth0, (depth0 && depth0.left_icon_show), {hash:{},inverse:self.noop,fn:self.program(2, program2, data),data:data});
    if(stack1 || stack1 === 0) { buffer += stack1; }
    buffer += "\n			\n\n			<h3 class=\"title\">";
    if (helper = helpers.title) { stack1 = helper.call(depth0, {hash:{},data:data}); }
    else { helper = (depth0 && depth0.title); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
    buffer += escapeExpression(stack1)
      + "</h3>\n\n			";
    stack1 = helpers['if'].call(depth0, (depth0 && depth0.right_icon_show), {hash:{},inverse:self.noop,fn:self.program(7, program7, data),data:data});
    if(stack1 || stack1 === 0) { buffer += stack1; }
    buffer += "\n\n		</div>\n	</header>\n	<!-- header end -->\n";
    return buffer;
    }
  function program2(depth0,data) {
    
    var buffer = "", stack1;
    buffer += "\n				";
    stack1 = helpers['if'].call(depth0, (depth0 && depth0.left_side_html), {hash:{},inverse:self.program(5, program5, data),fn:self.program(3, program3, data),data:data});
    if(stack1 || stack1 === 0) { buffer += stack1; }
    buffer += "\n			";
    return buffer;
    }
  function program3(depth0,data) {
    
    var buffer = "", stack1, helper;
    buffer += "     \n					";
    if (helper = helpers.left_side_html) { stack1 = helper.call(depth0, {hash:{},data:data}); }
    else { helper = (depth0 && depth0.left_side_html); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
    if(stack1 || stack1 === 0) { buffer += stack1; }
    buffer += "\n				";
    return buffer;
    }
  
  function program5(depth0,data) {
    
    
    return "\n					<a href=\"javascript:void(0);\" >\n						<div class=\"back\" data-role=\"page-back\">\n							<i class=\"dib icon-font i-iconfont-back icon-font-size-16 icon-back\"></i>\n						</div>\n					</a>\n				";
    }
  
  function program7(depth0,data) {
    
    var buffer = "", stack1;
    buffer += "\n\n				";
    stack1 = helpers['if'].call(depth0, ((stack1 = (depth0 && depth0.share_icon)),stack1 == null || stack1 === false ? stack1 : stack1.show), {hash:{},inverse:self.noop,fn:self.program(8, program8, data),data:data});
    if(stack1 || stack1 === 0) { buffer += stack1; }
    buffer += "\n\n				";
    stack1 = helpers['if'].call(depth0, ((stack1 = (depth0 && depth0.omit_icon)),stack1 == null || stack1 === false ? stack1 : stack1.show), {hash:{},inverse:self.noop,fn:self.program(10, program10, data),data:data});
    if(stack1 || stack1 === 0) { buffer += stack1; }
    buffer += "\n		\n				";
    stack1 = helpers['if'].call(depth0, ((stack1 = (depth0 && depth0.show_txt)),stack1 == null || stack1 === false ? stack1 : stack1.show), {hash:{},inverse:self.noop,fn:self.program(12, program12, data),data:data});
    if(stack1 || stack1 === 0) { buffer += stack1; }
    buffer += "\n\n				";
    stack1 = helpers['if'].call(depth0, ((stack1 = (depth0 && depth0.search_icon)),stack1 == null || stack1 === false ? stack1 : stack1.show), {hash:{},inverse:self.noop,fn:self.program(14, program14, data),data:data});
    if(stack1 || stack1 === 0) { buffer += stack1; }
    buffer += "\n\n				";
    stack1 = helpers['if'].call(depth0, ((stack1 = (depth0 && depth0.go_index)),stack1 == null || stack1 === false ? stack1 : stack1.show), {hash:{},inverse:self.noop,fn:self.program(16, program16, data),data:data});
    if(stack1 || stack1 === 0) { buffer += stack1; }
    buffer += "\n\n				";
    stack1 = helpers['if'].call(depth0, ((stack1 = (depth0 && depth0.question)),stack1 == null || stack1 === false ? stack1 : stack1.show), {hash:{},inverse:self.noop,fn:self.program(18, program18, data),data:data});
    if(stack1 || stack1 === 0) { buffer += stack1; }
    buffer += "\n					\n			";
    return buffer;
    }
  function program8(depth0,data) {
    
    
    return "\n					<!-- 分享按钮 -->\n					<div class=\"share\" data-role=\"right-btn\">\n						<i class=\"icon-share\"></i>\n					</div>\n					<!-- 分享按钮 end -->\n				";
    }
  
  function program10(depth0,data) {
    
    var buffer = "", stack1;
    buffer += "\n					<!-- 三点 -->\n					<div class=\"omit\" >\n						<div data-role=\"right-btn\" class=\"icon-omit-item\">\n							<i class=\"icon-omit\" ></i>\n						</div>\n						<div class=\"omit-pop ui-border-radius-5 right012 \" data-role=\"omit-pop\">\n							<i class=\"dib icon-font i-iconfont-shixinxiaojiantou icon-font-size-7 color-2c2 ui-transform-180\"></i>\n							<ul class=\"list\">\n								<a href=\""
      + escapeExpression(((stack1 = ((stack1 = (depth0 && depth0.go_index)),stack1 == null || stack1 === false ? stack1 : stack1.url)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
      + "\">\n									<li>\n										<i class=\"dib icon-font i-iconfont-home icon-font-size-16\"></i>\n										<em class=\"color-fff f15 txt\">首页</em>\n									</li>\n								</a>\n								<a href=\""
      + escapeExpression(((stack1 = ((stack1 = (depth0 && depth0.omit_icon)),stack1 == null || stack1 === false ? stack1 : stack1.url)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
      + "\" >\n									<li class=\"ui-border-t\">\n										<i class=\"dib icon-font i-iconfont-no-data icon-font-size-16\"></i>\n										<em class=\"color-fff f15 txt\">举报</em>\n									</li>\n								</a>\n							</ul>\n						</div>\n\n					</div>\n					<!-- 三点 end -->\n				";
    return buffer;
    }
  
  function program12(depth0,data) {
    
    var buffer = "", stack1;
    buffer += "\n					<!-- 文字 -->\n					<div class=\"side-txt\" style=\""
      + escapeExpression(((stack1 = ((stack1 = (depth0 && depth0.show_txt)),stack1 == null || stack1 === false ? stack1 : stack1.style)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
      + "\" data-role=\"right-btn\">\n						"
      + escapeExpression(((stack1 = ((stack1 = (depth0 && depth0.show_txt)),stack1 == null || stack1 === false ? stack1 : stack1.content)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
      + "\n					</div>\n					<!-- end -->\n				";
    return buffer;
    }
  
  function program14(depth0,data) {
    
    
    return "\n					<div class=\"search\" data-role=\"right-btn\">\n						<i class=\"icon-search\"></i>\n					</div>\n				";
    }
  
  function program16(depth0,data) {
    
    
    return "\n					<div class=\"go-index\" data-role=\"right-btn\">\n						<a href=\"//www.51snap.cn/\"><i class=\"dib yue-ui-icon-size-22   icon-go-index\"></i></a>\n					</div>\n				";
    }
  
  function program18(depth0,data) {
    
    var buffer = "", stack1;
    buffer += "\n					<div class=\"go-question\" data-role=\"right-btn\">\n						<a href=\""
      + escapeExpression(((stack1 = ((stack1 = (depth0 && depth0.question)),stack1 == null || stack1 === false ? stack1 : stack1.url)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
      + "\"><i class=\"icon-question\"></i></a>\n					</div>\n				";
    return buffer;
    }
  
    buffer += "\n";
    stack1 = helpers['if'].call(depth0, (depth0 && depth0.header_show), {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
    if(stack1 || stack1 === 0) { buffer += stack1; }
    return buffer;
    });
          self.render_ele.html(template(data));
  
          self.$el = self.render_ele;
  
          self.setup_event(self.$el);
      },
      setup_event : function($el)
      {
          var self = this;
  
          var use_page_back = self.options.use_page_back == null ? true : false;
  
          self.$el.find('[data-role="page-back"]').on('click',function()
          {
  
  
              if(use_page_back)
              {
                  self.page_back();
              }
              else
              {
                  self.$el.trigger('click:left_btn');
              }
  
  
          });
  
          self.$el.find('[data-role="right-btn"]').on('click',function()
          {
              self.$el.trigger('click:right_btn');
          });
  
  
      },
  
      
      page_back : function()
      {
          var self = this;
  
          if(document.referrer)
          {
              window.history.back();
              return false;
          }
          else
          {
              window.location.href = __index_url_link ;
          }
  
      },
      set_bar_css : function(paddingTop,use_class)
      {
          var self = this;
          var paddingTop = paddingTop || '0';
          if(use_class)
          {
              $("[role=main]").addClass('main-top');
              
          }
          else
          {
              $("[role=main]").css({
                  paddingTop: paddingTop
              });
          }
          
      }
  };
  
  exports.init = function(options)
  {
      return new header_class(options);
  };
  
  

});
