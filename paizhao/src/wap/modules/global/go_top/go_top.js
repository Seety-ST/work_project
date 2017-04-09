/**
 * Created by 汤圆 on 16/8/10
 */
'use strict';

/**依赖文件，要在注释上使用**/


// var $ = require('jquery');



function go_top(options)
{
    var self = this;
    var options = options || {};
    self.$render_ele = options.render_ele;
    self.init();
}


go_top.prototype =
{
    init : function()
    {
        var self = this;
        self.render();
        self.setup_event();
    },

    render: function () 
    {
        // tpl后缀的文件也可以用于模板嵌入，相比handlebars
        // tpl文件不具有模板变量功能，嵌入后只是作为字符串使
        // 用，tpl文件嵌入之前可以被插件压缩，体积更小。
        // handlebars由于缺少相应的压缩插件因此暂时不能在预
        // 编译阶段做压缩。选择tpl还是handlebars可以自由选
        // 择

        var self = this;
        var template  = __inline('./go_top.tmpl');
        var view = self.$render_ele.append(template());
        self.ele = $(view).find('#go-top');
        self.ele.on('click', function(event) {
            event.preventDefault();
            // $("html,body").animate({scrollTop:0},200);
            scroll(0,0);
        });


    },

    setup_event : function() 
    {
        var self = this;
        $(window).scroll(function(){
            if($(window).scrollTop() > 500)
            {
                self.ele.removeClass('fn-hide');
            }
            else
            {
                self.ele.addClass('fn-hide');
            }
        });
    }


};

return go_top;



