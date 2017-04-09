/**
 * Created by ��Բ on 16/8/10
 */
'use strict';

/**�����ļ���Ҫ��ע����ʹ��**/


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
        // tpl��׺���ļ�Ҳ��������ģ��Ƕ�룬���handlebars
        // tpl�ļ�������ģ��������ܣ�Ƕ���ֻ����Ϊ�ַ���ʹ
        // �ã�tpl�ļ�Ƕ��֮ǰ���Ա����ѹ���������С��
        // handlebars����ȱ����Ӧ��ѹ����������ʱ������Ԥ
        // ����׶���ѹ����ѡ��tpl����handlebars��������ѡ
        // ��

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


