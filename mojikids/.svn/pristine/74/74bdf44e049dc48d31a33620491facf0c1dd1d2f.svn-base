/**
* App 接口
*/

var Util = require.syncLoad(__moduleId('/modules/global/util/index'));
var ua = Util.ua;
var appBridge = window.PocoWebViewJavascriptBridge;
var isPaiApp = typeof appBridge !== 'undefined';

if(ua.is_yue_app)
{
    isPaiApp = true;
}
else if(ua.is_yue_seller)
{
	isPaiApp = true;
}
else if(window.SUPE_APP && window.SUPE_APP.isApp)
{
	isPaiApp = true;
}
else
{
	isPaiApp = false;
}

// app 跳转协议
var app_protocol = 'yueyue://goto';

var prefix = 'poco.yuepai.';

if (isPaiApp) {
	appBridge.init();

	// 留接口比app调用
	window.pocoAppEventTrigger = function() {
		return pocoAppEventTrigger.apply(pocoAppEventTrigger, arguments);
	};
}

var App = {
	isPaiApp: isPaiApp,
	/**
	 * 私信
	 * @param options
	 */
	chat : function(options)
	{
		var self = this;

		appBridge.callHandler(prefix+'function.chat', options, function()
		{
			typeof options.callback == 'function' && options.callback.call(this);
		});
	},
	/**
	 * 扫二维码
	 */
	qrcodescan : function(options)
	{
        alert('Error:methods is undefined');
		return true;
	},
	/**
	 * 检查是否登录
	 * @param callback
	 */
	check_login : function(callback)
	{


		appBridge.callHandler(prefix+'info.login',
		{


		}, function(poco_id,locationid)
		{
			callback.call(this,poco_id,locationid);
		});
	},
	/**
	 * 登录
	 */
	login : function(options)
	{
		var self = this;

		var options = options || {};

		appBridge.callHandler(prefix+'function.login',
		{
			pocoid : options.pocoid,
			username : options.username,
			icon : options.icon,
			token : options.token,
			token_expirein : options.token_expirein

		}, function()
		{


		});
	},
	/**
	 *  网页登陆通知app
		poco.yuepai.function.waplogin
		传入参数：
		pocoid：登陆的用户id
		username：登陆的用户名
		icon：登陆用户的头像
		token：用于跟服务器接口通讯的token
		token_expirein：token过期时间
		refresh_token：token刷新
		cellphone：手机号码
		location_id: 所在地区

		返回参数：无
	 * @param  {[type]} options [description]
	 * @return {[type]}         [description]
	 */
	waplogin : function(options)
	{
		var self = this;

		var options = options || {};

		console.log('APP waplogin');

		appBridge.callHandler(prefix+'function.waplogin',options, function(data)
		{
			console.log('======支付宝支付回调参数======');
			console.log(data);
		});
	},
	/**
	 * 退出登录
	 * @param options
	 */
	logout : function()
	{
		var self = this;

		console.log('APP logout');

		appBridge.callHandler(prefix+'function.logout',
		{

		}, function()
		{


		});
	},

	/**
	 * 获取聊天信息
	 * @param callback
	 */
	get_chat_info : function(callback)
	{
		var self = this;

		appBridge.callHandler(prefix+'info.chat',{},
		function(data)
		{
			if(typeof callback == 'function')
			{
				callback.call(this,data);
			}

		});
	},
	/**
	 * 获取登录信息
	 * @param success
	 */
	get_login_info : function(success)
	{
		var self = this;

		appBridge.callHandler(prefix+'info.login',{},
		function(data)
		{
			if(typeof success == 'function')
			{
				success.call(this,data);
			}


		});
	},
	/**
	 * 支付宝支付
	 * @param params
	 */
	alipay : function(params,callback)
	{
		var self = this;

		if(!params)
		{
			alert('no params');

			return;
		}

		console.log(params);

		appBridge.callHandler(prefix+'function.alipay',params,
		function(data)
		{
			console.log('======支付宝支付回调参数======');
			console.log(data);

			if(typeof callback == 'function')
			{
				callback.call(this,data);
			}
		});
	},
	/**
	 * 微信支付
	 * @param params
	 */
	wxpay : function(params,callback)
	{
		var self = this;

		if(!params)
		{
			alert('no params');

			return;
		}

		console.log(params);

		appBridge.callHandler(prefix+'function.wxpay',params,
			function(data)
			{
				console.log('======微信支付回调参数======');
				console.log(data);

				if(typeof callback == 'function')
				{
					callback.call(this,data);
				}
			});
	},
	/**
	 * 银联支付
	 * @param  {[type]}   params   [description]
	 * @param  {Function} callback [description]
	 * @return {[type]}            [description]
	 */
	unionpay : function(params,callback)
	{
		var self = this;

		if(!params)
		{
			alert('no params');

			return;
		}

		console.log(params);

		appBridge.callHandler(prefix+'function.unionpay',params,
			function(data)
			{
				console.log('======银联支付回调参数======');
				console.log(data);

				if(typeof callback == 'function')
				{
					callback.call(this,data);
				}
			});
	},
	/**
	 * 上传图片
	 * @param params
	 * @param callback
	 */
	upload_img : function(type,params,callback)
	{
		var self = this;

		console.log('upload img');

		var url = '';
		var photosize = params.photosize || 640;

		//var domain = 'http://imgup-yue.yueus.com';

		var is_wifi = window.APP_NET_STATUS == 'wifi'?'-wifi':'';

		console.log('net status:'+window.APP_NET_STATUS);

		var icon_domain = 'http://sendmedia-w'+is_wifi+'.yueus.com:8078/';
		var pics_domain = 'http://sendmedia-w'+is_wifi+'.yueus.com:8079/';

		if(!params)
		{
			alert('no params');

			return;
		}

		if(!type)
		{
			alert('no type');

			return;
		}

		var operation = '';
		var src_opts = '';

		//url = domain + '/ultra_upload_service/yueyue_upload_user_icon_act.php';

		switch (type)
		{
			case 'header_icon':

				url = icon_domain + 'icon.cgi';
				operation = 'modify_headicon';
				src_opts = 'camera_album';
				break;
			case 'single_img':
				url = pics_domain + 'upload.cgi';
				src_opts = 'camera_album';
				break;
			case 'modify_cardcover':
				url = pics_domain + 'upload.cgi';
				operation = 'modify_cardcover';
				src_opts = 'camera_album';
				break;
			case 'multi_img':
				 /*if(params.is_zip == 1)
				 {
					 url = domain + '/ultra_upload_service/yueyue_multi_upload_act.php';
				 }
				 else
				 {
					url = domain +  '/ultra_upload_service/yueyue_upload_act.php';
				 }*/

				url = pics_domain + 'upload.cgi';
				src_opts = 'camera_album';

				break;
		}


		params = Util.extend(params,{url:url,photosize : photosize,operation:operation,src_opts : src_opts});

		console.log('-----upload img params-----');
		console.log(params);

		appBridge.callHandler(prefix+'function.uploadpic',params,
			function(data)
			{
				callback.call(this,data);
			});




	},
	/**
	 * 打开聊天列表
	 */
	show_chat_list : function()
	{
        alert('Error:methods is undefined');
		return true;
	},
	/**
	 * 调试模式
	 * url：待调试的首页链接
	   cache_onoff：是否开启缓存，0关闭，1开启
	   debug：0/1 ,是否启用调试模式，0关闭，1开启
	 */
	debug : function(options)
	{
        alert('Error:methods is undefined');
		return true;
	},
	/**
	 * 放大图
     * data => Array
     * data =
     * {
     *     img_arr:
     *     [
     *        {
     *          url : '',
     *          text : ''
     *        },...
     *     ]
     *     index : '' 当前图片索引值
     * };
	 * @param data
	 */
	show_alumn_imgs : function(data)
	{
		var data = data || {};

		appBridge.callHandler(prefix+'function.show_album_imgs',data,
		function()
		{

		});
	},
	/**
	 * 获取网络状态
	 * @param callback
	 */
	get_netstatus : function(callback)
	{

		appBridge.callHandler(prefix+'info.netstatus',{},
		function(data)
		{
			console.log("===========调用 App 获取网络状态===========");
			console.log(data);
			//type off、wifi、mobile

			if(typeof callback == 'function')
			{
				callback.call(this,data);
			}
		});
	},
	/**
	 * 第三方登录
	 * @param params
	 * @param callback
	 * 传入参数：
	   platform：qzone/sina
	   返回参数：
	   code：0000-成功，1000-失败
	   message：错误信息
	   uid：用户id
	   token：令牌
	   tokensecret：
	 */
	sso_login : function(params,callback)
	{

		appBridge.callHandler(prefix+'function.bind_account',params,
		function(data)
		{
			if(typeof callback == 'function')
			{
				callback.call(this,data);
			}
		});
	},
	/**
	 * 打电话
	 * @param params
	 * number : 电话号码
	 *
	 */
	call_phone : function(params,callback)
	{
		appBridge.callHandler(prefix+'function.callphone',params,
		function(data)
		{
			if(typeof callback == 'function')
			{
				callback.call(this,data);
			}
		});
	},
	get_gps : function(params,callback)
	{
        alert('Error:methods is undefined');
		return true;
	},
	set_setting : function(params,callback)
	{
        alert('Error:methods is undefined');
		return true;
	},
	get_setting : function(params,callback)
	{
        alert('Error:methods is undefined');
		return true;
	},
	/**
	 * 点击过去清除缓存
	 */
	clear_cache : function()
	{
		appBridge.callHandler(prefix+'function.clearcache',{},
			function(data)
			{


			});
	},

	show_bottom_bar : function(show)
	{
        alert('Error:methods is undefined');
		return true;
	},
	check_update : function()
	{
        alert('Error:methods is undefined');
		return true;
	},
	/**
	 *   模拟app返回键
		 poco.yuepai.function.back
		 传入参数：无
		 返回参数：无
	 */
	app_back : function()
	{
		console.log('调用 App Back Function');

		window.AppCanPageBack = true;

		//alert('测试 调用 App Back Function');

		appBridge.callHandler(prefix+'function.back',{},
			function(data)
			{


			});
	},
	/**
	 * 获取app信息 主要用于显示红点
	 */
	app_info : function(callback)
	{
		console.log('App app-info');

		appBridge.callHandler(prefix+'info.app',{},
		function(data)
		{
			console.log(data)

			if(typeof callback == 'function')
			{
				callback.call(this,data);
			}
		});
	},
	/**
	 * 跳转页面
	 * @param options
	 * @param callback
	 */
	switchtopage : function(options,callback)
	{
		console.log('App switchtopage');

		appBridge.callHandler(prefix+'function.switchtopage',options,
			function(data)
			{
				console.log(data)

				if(typeof callback == 'function')
				{
					callback.call(this,data);
				}
			});
	},
	/**
	 * 分享参数
	 * @param options
	 * @param callback
	 */
	share_card : function(options,callback)
	{
		var self = this;

		var option = options || {};

		console.log("share_card请求前参数：");
		console.log(option);
		appBridge.callHandler(prefix+'function.sharecard',
			{
				url : option.url,
				pic : option.pic || option.img,
				img : option.pic || option.img,
				content : option.content,
				title : option.title,
				sinacontent : option.sinacontent,
				sina_conent : option.sina_conent,
				userid : option.userid,
				qrcodeurl : option.qrcodeurl,
				jscbfunc  : option.jscbfunc || function(){},
				sourceid  : option.sourceid || 0

			},
			function(data)
			{
				console.log("share_card回调：");
				console.log(data);
				if(typeof callback == 'function')
				{
					callback.call(this,data);
				}
			});
	},

	analysis : function(tj_type,options,callback)
	{
        alert('Error:methods is undefined');
		return true;
	},
	/**
	 * 导航到app页面方法
	 * @param params
	 */
	nav_to_app_page : function(params)
	{
        alert('Error:methods is undefined');
		return;
	},
	/**
	 * 网页跳转app接口
	 * @param  {[type]} url [description]
	 * @return {[type]}     [description]
	 */
	openlink : function(url)
	{
		console.log('App openlink');

		if(!url)
		{
			alert('请传入合法url');
			return;
		}

		appBridge.callHandler(prefix+'function.openlink',{url : url},
		function(data)
		{
			console.log('openlink 回调数据:'+data);

			if(typeof callback == 'function')
			{
				callback.call(this,data);
			}
		});
	},

	/**
	 * 打开tt 页面
	 * @param callback
	 */
	openttpayfinish : function(callback)
	{
		console.log('App ttpayfinish');

		appBridge.callHandler(prefix+'function.ttpayfinish',{},
			function(data)
			{
				console.log(data)

				if(typeof callback == 'function')
				{
					callback.call(this,data);
				}
			});
	},
	/**
	 * 关闭webview
	 * @param callback
	 */
	close_webview : function(callback)
	{
		console.log('App close_webview');

		appBridge.callHandler(prefix+'function.close',{},
			function(data)
			{
				console.log(data)

				if(typeof callback == 'function')
				{
					callback.call(this,data);
				}
			});
	},
    /**
     *
     * @param options
     * @param callback
     * return
     * code：0000-登陆成功，1001-取消
       locationid：location id
       city：城市名
     */
    show_selectcity : function(options,callback)
    {
        console.log('App selectcity');

        appBridge.callHandler(prefix+'function.selectcity',options,
            function(data)
            {
                console.log(data)

                if(typeof callback == 'function')
                {
                    callback.call(this,data);
                }
            });
    },
    /**
     * 用app查看二维码
     * 传入参数：
     qrcodelist：二维码列表，json格式
     格式：
     qrcodes:
     [{
            url : 'xxxx',
            name: 'xxxx'
            number:'xxxx'
         },{
            url : 'xxxx',
            name: 'xxxx'
            number:'xxxx'
         }]
     index：跳到哪一张二维码

     * @param callback
     */
    qrcodeshow : function(qrcodes,index,callback)
    {
        console.log('App qrcodeshow');

        index = index || 0;

        appBridge.callHandler(prefix+'function.qrcodeshow',{qrcodes : qrcodes,index:index},
            function(data)
            {
                console.log(data)

                if(typeof callback == 'function')
                {
                    callback.call(this,data);
                }
            });
    },
    /**
     * 对头部菜单调用
     * @param options
     * @param callback
     * type：显示类型，0：不显示，1：显示，无“菜单”按钮， 2：显示，有“菜单”按钮
     */
    showtopmenu : function(is_show,options,callback)
    {
        console.log('App showtopbar');

        options = options || {};

        options.type = is_show ? 2 : 1;

        if(options.show_bar)
        {
            options.type = 0;
        }

        appBridge.callHandler(prefix+'function.showtopbar',options,
            function(data)
            {
                console.log(data)

                if(typeof callback == 'function')
                {
                    callback.call(this,data);
                }
            });
    },
    /**
     * 支付成功通知
     * @param  {[type]}
     * @param  {Function}
     * @return {[type]}
     */
    payfinish : function(options,callback)
    {
    	options = options || {};

    	appBridge.callHandler(prefix+'function.payfinish',options,
            function(data)
            {
                console.log(data)

                if(typeof callback == 'function')
                {
                    callback.call(this,data);
                }
            });
    },

    /**
     * 调用公共支付平台
     * @param  {[type]}   options  [description]
     * @param  {Function} callback [description]
     * @return {[type]}            [description]
     */
    open_paypage : function(options,callback)
    {
    	options = options || {};

    	console.log('==== open_paypage options ====');
    	console.log(options);

    	appBridge.callHandler(prefix+'function.pay',options,
            function(data)
            {
                console.log(data)

                if(typeof callback == 'function')
                {
                    callback.call(this,data);
                }
            });
    }




};
module.exports = App;
