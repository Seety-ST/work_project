/**
 * vue 列表组件
 */
var Util = require.syncLoad(__moduleId('/modules/global/util/index'));

/**
 * 获取滚动元素
 * @param  {[type]} el [description]
 * @return {[type]}    [description]
 */
function get_scroll_target(el)
{
	while(el.nodeType === 1 && el.tagName !== 'BODY' && el.tagName !== 'HTML')
	{
		var overflowY = getComputedStyle(el).overflowY;
		if(overflowY === 'scroll' || overflowY === 'auto')
		{
			return el;
		}
		el = el.parentNode;
	}

	return window;
}



exports.init = function(options)
{
	options = options || {};

	if(!options.template)
	{
		console.error('缺少 options.template');
		return;
	}
	if(!options.data || typeof options.data != 'function')
	{
		console.error('缺少 options.data 或 类型不为function');
		return;
	}

    var parent_tpl =
    [
        '<div class="moji-list-wrapper">',
        '    <div class="container">',
                options.template,
        '        ',
        '    </div>',
        '    <div class="bottom pt10 pb10 tc color-666">',
        '      <div v-show="data.list.length>0" >{{data.tips}}</div>   ',
        '    </div>',
        '    <div @click="reload" v-show="data.list.length == 0 && !data.has_next_page" class="no-data-type1 color-666 pt10 pb10 tc">',
        '        暂无数据，点击我再刷新一下',
        '        ',
        '    </div>',
        '</div>'
    ].join("");

	var list_options =
	{
		template : parent_tpl,
		data()
        {
            var obj = options.data();

            obj.data.tips = options.data.tips || '努力加载中...';

            return obj;
        },
		methods :
		{
			action()
			{
				var self = this;
				Util.request
        		({
        			url : options.url,
        			data : self.$data.ajax_params || {},
        			beforeSend : function()
        			{
                        self.$emit('beforeSend',{});
        			},
        			success(response)
        			{
        				// 请求成功
                        try
                        {
                            if(typeof response.res == 'undefined')
                            {
                                // 为了兼容其他接口的父字段 result_data
                                response.res = response.result_data;

                                delete response.result_data;
                            }
                        }
                        catch(err)
                        {
                            console.warn('you should change the response parent field');
                        }

        				var res = response.res;

        				var list = res.list;

        				self.$data.ajax_params.page = res.page;

        				self.$data.data.has_next_page = res.has_next_page;

        				if(res.page == 1)
        				{
        					self.$data.data.list = list;
        				}
        				else if(res.page >1)
        				{
        					self.$data.data.list = self.$data.data.list.concat(list);
        				}

                        if(!self.$data.data.has_next_page)
                        {
							//self.$data.data.tips = '您好，我是底线';
                            self.$data.data.tips = '';
                        }

                        self.$emit('success',response);

        			},
        			error(err)
        			{
                        Util.toast({
    						message: '网络异常',
    						position: 'middle',
    						duration: 2000
    					});

	            		console.error(err);

                        self.$emit('error',err);
					},
                    complete()
                    {
                        self.$emit('complete',true);
                    }
        		});
			},
			refresh()
			{
				var self = this;

				self.$data.ajax_params.page = 1;

				self.action();
			},
			load_more()
			{
				var self = this;

				if(!self.$data.data.has_next_page)
				{
					return;
				}

				self.$data.ajax_params.page++;

				self.action();

			},
			scroll()
			{
				var self = this;

				if(self.$el.nodeType !== 1)
				{
					return;
				}

				var vm = self.vm;
				var target = self.target = get_scroll_target(self.$el);

				self.scroll_listenner = function()
				{
					if(!self.$el.clientHeight)
					{
						return;
					}

					if(target == window)
					{
						var scrll_top = Math.max(window.pageYOffset || 0,document.body.scrollTop);

						if(document.documentElement.clientHeight + scrll_top >=document.documentElement.scrollHeight)
						{
							self.load_more();
						}
					}
					else
					{
						if(target.clientHeight + target.scrollTop >= target.scrollHeight)
						{
							self.load_more();
						}
					}
				}

				target.addEventListener('scroll',self.scroll_listenner);
			},
            reload()
            {
                // 暂无数据的重置数据
                var self = this;

                self.refresh();

                Util.loading.open
                ({
                    text : '努力加载中...',
                    timeout:2000
                });
            }


		},
		events :
		{
			/**
			 * 子组件的刷新事件
			 * @param  {[type]} params [description]
			 * @return {[type]}        [description]
			 */
			'list-com:refresh' : function(params)
			{
				var self = this;

				if(params)
				{
					self.$data.ajax_params = params;
				}

				self.refresh();
			},

		},
		mounted()
		{
			var self = this;

			self.scroll();

		}
	};

    // 拷贝方法
    list_options.methods = Util.extend(list_options.methods,options.methods);

	var list_vue = Vue.extend(list_options);

	return list_vue;
}
