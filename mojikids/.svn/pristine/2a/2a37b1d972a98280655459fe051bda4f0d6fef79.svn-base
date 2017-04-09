/**
 * Created by huanggc on 16/6/30.
 *
 * 通用传图组件
 */
 var WXSDK =  require.syncLoad('wap-kids:global/wxsdk/index');
 var App = require.syncLoad('wap-kids:global/app/index');
 var Util = require.syncLoad('wap-kids:global/util/index');
 var Ua = Util.ua;

function yue_topic_upload_class(options)
{
	var self = this;

	var options = options || {} ;
    var ua = Util.ua;

	self.$el = options.$el || $('body') ;
	self.type = options.type || "";
    self.force_type = options.force_type || "";
	self.params = options.params ||{};
	self.wx_params = options.wx_params || {};
	self.app_params = options.app_params || {};
	self.web_params = options.web_params || {};
    self.server_url = options.server_url || 'http://sendmedia-w.yueus.com:8079/upload.cgi'

	if(ua.is_weixin)
	{
		var type = 'wx';
	}
	else if(App.isPaiApp)
	{
		var type = 'app';
	}
	else
	{
		var type = 'web';
	}
    var server_http = window.location.protocol;
    if(self.server_url && server_http == 'https' && /sendmedia-w/.test(self.server_url))
    {
        self.server_url = 'https://sendmedia-w.yueus.com/upload.cgi';
    }

    self.type = type;

    if(self.force_type)
    {
        self.type = self.force_type;
    }

	self.init(options);
}

yue_topic_upload_class.prototype =
{
	init : function(options)
	{
		var self = this;

		// 初始化微信
		self.init_wx(self.wx_params);
		// 初始化app
		self.init_app(self.app_params);
		// 初始化web传图
		self.init_web(self.web_params);

	},
	init_wx : function(params)
	{
		var self = this;

		if(self.type == 'wx')
        {

	        var default_params =
        	{
                 upload_type : "pics", //icon头像 pics作品 默认pics 不懂问荣少“传图接口是不是份头像和作品”
                 choose_trigger_str : "chooseImages_test", //选图事件触发自定义字符串 默认值chooseImages
                 choose_count : 1, //选图张数，拍照微信限制1张,选图默认9张，该值只能调整选图张数
                 server_url : 'http://www.mojikids.com/ajax/upload/wx_image.php',
                 platform : '',
                 choose_success : function(res){
                     //选图完成时调用该方法
                     //alert("success" + JSON.stringify(res))
                     //res.sourceType 两种情况 : 'album' 相册  || 'camera' 相机
                     //res.localIds 回调的图片数组

                     self.$el.trigger('uploadImages_test'); //触发对应上传图事件 默认值uploadImages
                 },
                 choose_cancel : function(res){
                     //取消选图时调用该方法
                 },
                 choose_fail :  function(res){
                     //选图失败时调用该方法
                 },
                 upload_trigger_str : "uploadImages_test",//上传图片url事件触发自定义字符串 默认值uploadImages
                 upload_success : function(resa,index,total_index)
                 {
                     //成功上传完每张图都会调用该方法 index为上传序号 0开始

                 },
                 upload_cancel : function(resa,index,total_index){
                     //取消上传完每张图都会调用该方法 index为上传序号 0开始
                 },
                 upload_fail : function(resa,index,total_index){
                     //上传失败完每张图都会调用该方法 index为上传序号 0开始

                 },
                 upload_success_all : function(media_obj_list,index,total_index){
                     //上传完所有图片会调用该方法 index为上传序号 0开始
                     //media_obj_list:对象数组[{localId:"",mediaId:""},{},{}] mediaId用于下载该图
                     //alert("all" + JSON.st ringify(media_obj_list))
                     self.$el.trigger('getImagesUrl_test'); //触发对应获取url事件 默认值getImagesUrl
                 },
                 get_trigger_str : "getImagesUrl_test",//获取图片url事件触发自定义字符串 默认值getImagesUrl
                 get_imgUrl_beforeSend : function(){
                     //发送图片路径ajax前调用

                 },
                 get_imgUrl_success : function(data){

                    //发送图片路径ajax成功后调用
                    console.info(data);



                 },
                 get_imgUrl_error : function(err){
                     //发送图片路径ajax错误时调用

                 },
                 get_imgUrl_complete : function(){
                     //发送图片路径ajax完成后调用

                 }
             }

            params = Util.extend(default_params,params,true);

            console.log(params)

        	WXSDK.chooseImages_and_uploadImages_and_downloadImages(params);

	    }
	},
	init_app : function(params)
	{
		var self = this;

		var default_params =
		{
            is_async_upload : 0,
            max_selection : 1,
            is_zip : 1
        };

        self.app_params = $.extend(default_params,params,true);
	},
	init_web : function(params)
	{
		var self = this;
        if(!window.WebUploader)
        {
            return
        };
        if(self.type == 'web')
        {
            var poco_id = Util.cookie.get('yue_member_id');
            // 初始化上传组件
            var form_data =
            {
                poco_id : poco_id || '10001',
                sh_type : 'merchandise'
            };
            var name = 'opus';
            if(params.form_data)
            {
                form_data = params.form_data
                name = 'file';
            }

            var mine_types = 'image/*';
            if(!Ua.is_mobile)
            {
                mine_types = '';
            }

            form_data = JSON.stringify(form_data);

            var default_params =
            {
                auto : true,
                file_num_limit : 1,
                fileSizeLimit: 9 * 1024 * 1024,    // 9 M 总文件大小
                fileSingleSizeLimit: 3 * 1024 * 1024,    // 3 M 单文件大小
                pick :
                {
                    id : self.$el.get(0),
                    multiple : true
                },
                duplicate : true,
                chunked: false,
                chunkSize: 512 * 1024,
                server: self.server_url,
                // auto 设置true，选择图片即为自动上传
                cover_img : '',
                runtimeOrder: 'html5',
                headers : {
                    Accept : 'application/json, text/javascript, */*; q=0.01'
                },
                formData :
                {
                    params :  form_data,
                    data : form_data,// 兼容ou传图
                },
                accept:
                {
                    title: 'Images',
                    extensions: 'jpg,jpeg,png',
                    mimeTypes: mine_types
                },
                // 上传表单的name值
                fileVal : name,
                // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
                disableGlobalDnd: true,
                fileNumLimit: 20,
                fileSizeLimit: 200 * 1024 * 1024,    // 200 M
                fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
            };

            params = $.extend(default_params,params,true);

            var upload_obj = WebUploader.create(params);

            if(typeof params.ready_ok == 'function')
            {
            	params.ready_ok.call(this,upload_obj);
            }



        }
	},
	action : function()
	{
		var self = this;

		switch(self.type)
		{
			case "wx":
				self.$el.trigger('chooseImages_test'); //触发对应选图事件
				break;
			case "app":

				var m_type  = self.app_params.m_type || 'multi_img';
				var success = self.app_params.success || function(){};

				// 初始化上传组件
                App.isPaiApp && App.upload_img
                (m_type,self.app_params,function(data)
                {
                    console.log(data);

                    if(typeof self.app_params.success == 'function')
                    {
                    	self.app_params.success.call(this,data);
                    }


                });
				break;
			case "web":
				break;
		}

	}
};

exports.init_com = function(options)
{
	return new yue_topic_upload_class(options);
}
