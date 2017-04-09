/**
 * Created by hudingwen on 15/7/19.
 */
// var $ = require('zepto');
var utility = require.syncLoad('wap-huipai:global/utility/index');


(function($,window)
{   
    /**
     * ͼƬ�����
     * @param {[jquery object]} contain [�����ͼƬ�����ĸ�����]
     * @param {[object]} options {size : ������������ ��320}
     */
    function LazyLoading(contain,options)
    {
        var self = this;

        if(!contain)
        {
            throw '��δ��ʼ����lazyload�ĸ�����';
        }

        options = options || {};

        self.init(contain,options);

        return self;



    }

    LazyLoading.prototype =
    {
        init : function(contain,options)
        {
            var self = this;

            self.options = options;

            self.size = self.options.size;

            self.distance = self.options.distance || 0;

            self.currentELE = {};

            //���븸����
            if(contain){
                this.container = contain;
                //�״δ���
                self.freshELE(this.container);
                self.engine();
                //���¼�
                $(window).scroll(function()
                {
                    self.engine();
                });
            }
            else{
                this.container = null;
            }


        },
        freshELE : function(con){

            var self = this

            con = con || self.container;

            var $ele_arr = $(con).find('[data-lazyload-url]');

            var map = [];

            $ele_arr.each(function(i,obj)
            {
                var url = $(obj).attr('data-lazyload-url');

                map[i] =
                {
                    url : url,
                    obj : obj
                };
            });

            self.currentELE = map;



            //�ⲿˢ�� ���븸Ԫ�� �ҵ�����[data-lazyload-url]��Ԫ��
            //con ? self.currentELE = $(con).find('[data-lazyload-url]') : this.currentELE = $(this.container).find('[data-lazyload-url]');
        },
        engine : function(){

            var self = this;

            //[data-preurl]Ԫ�ر�������ͼƬ
            
            if(!self.currentELE.length)
            {
                return;
            }
            for (var i = 0; i < self.currentELE.length; i++){
                if ( elementInViewport(self.currentELE[i].obj,self.container) ){
                    loadImage(self.currentELE[i].obj,
                        {
                            size : self.size,
                            callback : function(img)
                            {
                                if(img.src == self.currentELE[i] && self.currentELE[i].url)
                                {
                                    self.currentELE.splice(i,1);
                                }
                            }
                        });
                }
            }
        },
        refresh : function()
        {
            var self = this;

            self.freshELE();
            self.engine();
        }
    };

    function loadImage (el,options) {
        //����ͼƬ ���ֱ�ǩ IMG �� ����[background-image]
        var img = new Image(),
            src = el.getAttribute('data-lazyload-url'),
            self = this;

        options = options || {};

        if(!src)
        {
            return;
        }

        img_ready(src,
        {
            load : function()
            {
                var img = this;

                if($(el).hasClass('error'))
                {
                    $(el).removeClass('error refresh');
                }

                var size = el.getAttribute('data-size') || options.size;
                var oldWidth = img.width;
                var oldHeight = img.height;


                if(size)
                {
                    var newHeight = setImageSize(size,oldWidth,oldHeight);
                    el.style.height = newHeight+'px';
                }

                self._st = setTimeout(function()
                {
                    el.tagName == 'IMG' ? el.src = src : el.style.backgroundImage = 'url('+ src + ')';

                    if(el.tagName == 'IMG' && el.hasAttribute('height'))
                    {
                        el.removeAttribute('height');                                     
                    }

                    $(el).addClass('loaded').removeAttr('data-lazyload-url');
                },250);



                typeof options.callback == 'function' && options.callback.call(self,img);
            },
            error : function()
            {
                var img = this;

                console.warn('ͼƬ����ʧ��:src='+img.src);

                $(el).addClass('error');

                $(el).one('click', function(event)
                {
                    event.stopPropagation();
                    event.preventDefault();

                    img.retry = 1;

                    loadImage(img);
                }).addClass('refresh');
            }
        });



    }

    function setImageSize (size,oldWidth,oldHeight)
    {

        var newWidth = utility.int(size);
        var oldWidth = utility.int(oldWidth);
        var oldHeight = utility.int(oldHeight);

        var newHeight = (newWidth * oldHeight) / oldWidth;

        newHeight = utility.int(newHeight);

        return newHeight;
    }

    function elementInViewport(el,container) {
        //�ж�Ԫ���Ƿ���������ɼ���Χ��
        var rect = el.getBoundingClientRect();

        if(!el.getAttribute('data-top'))
        {
            el.setAttribute('data-top',rect.top);
            $(el).addClass('img');
        }

        /*var W = window.innerWidth || document.documentElement.clientWidth;
        var H = window.innerHeight || document.documentElement.clientHeight;

        var flag = false;
        var distance = ;

        if((rect.top >= distance && rect.left >= distance
          || rect.top < 0 && (rect.top + rect.height) >= distance
          || rect.left < 0 && (rect.left + rect.width >= distance))
         && rect.top <= H && rect.left <= W )
        {
            flag = true;
        }

        return flag;*/

        return (
            rect.top >= 0 && rect.left >= 0 && rect.top  <= (window.innerHeight|| document.documentElement.clientHeight)

            )
    }

    function isFunction(o) {
        return typeof o === 'function';
    }

    function img_ready(imgUrl, options)
    {
        var img = new Image();

        img.src = imgUrl;

        // ���ͼƬ�����棬��ֱ�ӷ��ػ�������
        if (img.complete) {
            isFunction(options.load) && options.load.call(img);
            return;
        }

        // ���ش������¼�
        img.onerror = function () {
            isFunction(options.error) && options.error.call(img);
            img = img.onload = img.onerror = null;

            //delete img;
        };

        // ��ȫ������ϵ��¼�
        img.onload = function () {
            isFunction(options.load) && options.load.call(img);

            // IE gif������ѭ��ִ��onload���ÿ�onload����
            img = img.onload = img.onerror = null;

            //delete img;
        };
    };


    module.exports = LazyLoading;


})($,window);






