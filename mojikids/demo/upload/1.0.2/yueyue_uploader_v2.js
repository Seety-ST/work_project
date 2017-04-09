/**
 * Created by hudingwen on 15/6/10.
 */

 /*
	@require ./css/style.css
 */

function __create_css_link(url)
{
	 var link = document.createElement("link");
	 link.rel = "stylesheet";
	 link.type = "text/css";
	 link.href = url;
	 document.getElementsByTagName("head")[0].appendChild(link);
}

function __create_js_link(url,sync)
{
    var sync = sync == null ? true : false;

    if(sync)
    {
         var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = url;

        document.getElementsByTagName("head")[0].appendChild(script);
    }
    else
    {
        document.write('<script src="'+url+'" type="text/javascript"></script>');
    }

}

// 引用css文件
__create_css_link('http://static-c.yueus.com/yue_ui/upload/1.0.2/css/style.css');
// 引用js文件
__create_js_link('http://static-c.yueus.com/yue_ui/upload/drag-drop/js/core.js',false);
__create_js_link('http://static-c.yueus.com/yue_ui/upload/drag-drop/js/widget.js',false);
__create_js_link('http://static-c.yueus.com/yue_ui/upload/drag-drop/js/mouse.js',false);
__create_js_link('http://static-c.yueus.com/yue_ui/upload/drag-drop/js/sortable.js',false);

(function(window,undefined)
{
    var yueyue_uploader = function(element,options)
    {
        var self = this;

        options = options || {};

        // 参数设置
        var params = options.params || {};

        var file_count = 0;
        var file_size = 0;

		var form_data =
		{
			poco_id : options.user_id,
			sh_type : 'merchandise',
           role : 'yueseller'
		};

		var name = 'opus';
		if(params.form_data)
		{
			form_data = params.form_data
			name = 'file';
		}

		form_data = $.extend(true,form_data,options.formData);
		form_data = JSON.stringify(form_data);

        var default_params =
        {
            compress:false,
            paste: '#uploader',
            swf: 'http://static-c.yueus.com/mall/seller/static/pc/modules/common/upload/1.0.0/Uploader.swf',
            chunked: false,
            chunkSize: 512 * 1024,
            server: 'https://sendmedia-w.yueus.com/upload.cgi',
            // auto 设置true，选择图片即为自动上传
            auto: false,
            cover_img : '',
            //runtimeOrder: 'flash',
            formData :
            {
                params :  form_data,
				data : form_data,// 兼容ou传图
            },
            accept:
            {
                 title: 'Images',
                 extensions: 'jpg,jpeg,png',
                 mimeTypes: 'image/*'
            },
            // 上传表单的name值
            fileVal : name,
            // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
            disableGlobalDnd: true,
            fileNumLimit: 20,
            fileSizeLimit: 200 * 1024 * 1024,    // 200 M
            fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
        };



        params = $.extend(true,default_params,options);

		// 判断浏览器是否支持图片的base64
        var isSupportBase64 = ( function() {
            var data = new Image();
            var support = true;
            data.onload = data.onerror = function() {
                if( this.width != 1 || this.height != 1 ) {
                    support = false;
                }
            }
            data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
            return support;
        } )();

        // 检测是否已经安装flash，检测flash的版本
        var flashVersion = ( function() {
            var version;

            try {
                version = navigator.plugins[ 'Shockwave Flash' ];
                version = version.description;
            } catch ( ex ) {
                try {
                    version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash')
                        .GetVariable('$version');
                } catch ( ex2 ) {
                    version = '0.0';
                }
            }
            version = version.match( /\d+/g );
            return parseFloat( version[ 0 ] + '.' + version[ 1 ], 10 );
        } )();

        if (!WebUploader.Uploader.support())
        {
            alert( '上传功能不支持您的IE浏览器！，请切换其他浏览器或者更新Flash');

        }

        // 实例化
        var uploader = WebUploader.create(params);

        // 初始化文件数
        uploader.file_count = file_count;
        uploader.file_size = file_size;

        // 传图的状态 可能有pedding, ready, uploading, confirm, done.
        uploader.state = 'pedding';

        uploader.map_obj = {};

        uploader.on('fileQueued',function(file)
        {
            uploader.file_count++;
            uploader.file_size += file.size;

            uploader.map_obj[file.id] = file;

        });

        uploader.on('fileDequeued',function(file)
        {
            uploader.file_count--;
            uploader.file_size -= file.size;

            if(uploader.map_obj[file.id])
            {
                delete uploader.map_obj[file.id];
            }
        });

        uploader.option('start_upload').$el.on('click',function(ev)
        {
            uploader.trigger('startUpload',ev);
        });

        uploader.get_file_by_id = function(file_id)
        {
            return uploader.map_obj[file_id] || '';
        };

        uploader.on('error',function(err)
        {
            switch(err)
            {
                case 'Q_EXCEED_NUM_LIMIT':
                    alert('上传文件数量超出上限了');
                    break;
                case 'Q_EXCEED_SIZE_LIMIT':
                    alert('上传文件大小超出上限了');
                    break;
                case 'Q_TYPE_DENIED':
                    alert('上传文件类型格式错误')
                    break;
                case 'F_EXCEED_SIZE':
                    alert('上传文件大小超出上限了');
                    break;
            }
        });



        return uploader;
    };

    window.yueyue_uploader = yueyue_uploader;


})(window);

(function(window,$)
{

    window.yue_upload_class = function(options)
    {
        this.init(options);

        return this;
    };

    yue_upload_class.prototype =
    {
        init : function(options)
        {
            var self = this;

            self.options = options || {};

			if(!self.options.gid)
			{
				alert('必须传入图片组ID！');
				return;
			}

			if(!self.options.user_id)
			{
				alert('缺少用户ID！');
				return;
			}

			self.init_img_group();


        },
        tpl : '<div class="ui-item">' +
                '<div class="upload-page-content" data-role="first-page" >'+
                '<div id="first_file_picker" class="choose-img-btn" data-role="choose-img-btn">选择图片</div>'+
                '<div class="upload-img-btn" >'+
                '</div>'+
                '<p>按住Ctrl可多选图片</p>'+
                '</div>'+
                '<div class="webuploader-element-invisible" data-role="sec-page" id="go-on-add-page">' +
                '<div class="upload-page-content" style="padding: 10px 0 0 10px;" >'+

                '</div>'+
                '<div class="upload-start-continue">'+
                '<a class="upload-start upload-btn" id="start-upload-btn" href="javascript:void(0);">开始上传</a>'+
                '<div style="display: inline-block;margin-left:10px;" id="page-show" data-role="go-on-add-btn" >继续添加</div>'+
                '<div class="upload-img-btn2">'+
                '</div>'+
                '<span class="upload-tips" data-role="upload-tips"></span>'+
                '</div>'+
                '</div>'+
                '</div>',

        init_img_group : function()
        {
            var self = this;
            var options  = self.options;

            /***** 初始化添加图片组件 *****/
            var $upload_container = options.upload_container || false;

            var imgs = options.imgs || [];

            self.add_item_img_obj = {};
            self.upload_total_limit = options.upload_total_limit || 9;

			// 执行初始化图片组件
			self.add_item_img_obj = self.add_item_img($upload_container,
			{
				imgs : imgs
			});

        },
        add_item_img : function($el,params)
        {
			var self = this;
            var imgs = params.imgs || [];
            var limit = self.upload_total_limit;
            var imgs_str = [];

            var default_img_style = 'width: 130px;height: 130px;'
            var default_img_parent_class = '';

            // 封面图特殊处理 hudw 2015.9.15
            if(self.options.upload_logic_key == 'front')
            {
                default_img_style = 'height: 130px;'
                default_img_parent_class = 'front';
            }

            if(self.options.upload_container)
			{
                self.uuid = 'data-popup'+new Date().getTime();

				var add_img_tpl = '<div class="ui-btn-add ui-btn disable-drag" data-role="add-btn">'+
                    '<a href="javascript:void(0);" class="ui-bf" title="添加图片" '+self.uuid+' ></a>'+
                    '</div>';

				if(imgs.length>0)
				{
					for(var i=0;i<imgs.length;i++)
					{
						var url = imgs[i];

                        // 封面图特殊处理 hudw 2015.9.15
                        if(self.options.upload_logic_key == 'front')
                        {
                            url = matching_img_size(url,320);
                        }

						var tpl = '<div class="ui-upload ui-btn bg-color" data-role="ui-success-upload-img"  >'+
								'<a  class="'+default_img_parent_class+'" href="javascript:;">' +
								'   <img style="'+default_img_style+'" src="'+url+'" title=""/>' +
								'   <input type="hidden" name="'+self.options.gid+'[]" value="'+url+'" />' +
								'</a>'+
								'<div class="ui-upload-shadow"><a class="close-img"></a></div>'+
								'</div>';

						imgs_str[i] = tpl;
					}


				}
				if($el.length && !$el.find('[data-role="add-btn"]').length)
				{
					imgs_str.push(add_img_tpl);
				}



				var $temp = $(imgs_str.join(''));

                if($el.length )
                {
                    if(!$el.children().length)
                    {
                        $el.append($temp);
                    }
                    else
                    {
                        $el.find('[data-role="add-btn"]').before($temp)
                    }
                }




				// 鼠标经过显示预览图按钮
                $el.length && $el.find('[data-role="ui-success-upload-img"]').hover(function()
				{
					$(this).addClass('show');
				},function()
				{
					$(this).removeClass('show');
				});

				// 绑定点击事件
				$temp.find('.close-img').on('click',function(ev)
				{

					// 删除事件
					var $cur_btn = $(ev.currentTarget);

					var $img = $cur_btn.parents('.ui-btn');

					$img.remove();

					refresh();
				});

                function refresh()
                {
                    if(!$el.length)
                    {
                        return;
                    }

                    var $add_btn = $el.find('[data-role="add-btn"]');

                    if(!$el.find('[data-role="add-btn"]').length)
                    {
                        // 没有超过最大上限数
                        if($el.find('[data-role="ui-success-upload-img"] img').length < limit)
                        {
                            //$el.append(add_img_tpl);
                            $add_btn.removeClass('webuploader-element-invisible');
                        }
                        else
                        {
                            $add_btn.addClass('webuploader-element-invisible');
                        }

                    }
                    else
                    {
                        if($el.find('[data-role="ui-success-upload-img"] img').length >= limit)
                        {
                            $add_btn.addClass('webuploader-element-invisible');
                        }
                        else
                        {
                            $add_btn.removeClass('webuploader-element-invisible');
                        }
                    }
                }

				refresh();


			}

            // 初始化拖拉
            self.init_sortable();



            return {
                get_cur_img_list : function()
                {
                    var _imgs = ($el.length && $el.find('[data-role="ui-success-upload-img"] img')) || [];
                    var _temp_arr = [];

                    for(var i=0;i<_imgs.length;i++)
                    {
                        _temp_arr[i] = _imgs.eq(i).attr('src');
                    }

                    return _temp_arr;
                },
                refresh : function()
                {
                    //todo 执行刷新函数
                }
            }
        },
        /**
         * 排序缩略图
         * @return {[type]} [description]
         */
        sort_thumb_list : function($el)
        {
            var self = this;

            var $list = $el;

            if($list.length)
            {
                $list.find('[data-file-id]').each(function(i)
                {
                    $(this).find('[data-role="add-num"]').html(i+1);
                });
            }
        },
        init_sortable : function()
        {
            var self = this;

            if(!self.options.sortable)
            {
                return;
            }

            self.options.upload_container.sortable
            ({
                items: "div:not(.disable-drag)",
				cancel : ".ui-upload-shadow",
                activate : function(event, ui)
                {
                    console.log(ui);
                }
            })
			.bind(function(ev)
			{
				self.options.sortable_callback && self.options.sortable_callback.call(this,ev);
			});

            self.options.upload_container.disableSelection();
        },
        init_upload : function($el)
        {
            var self = this;
            var options  = self.options;

            /***** 初始化上传组件 *****/
            var uploader_obj = null;
            var first_layer = null;

            // 上传图片容器模板
            var init_upload_str = self.tpl;

            var show_add_num =  self.options.threads == 1;

            if(show_add_num)
            {
                show_add_num = '<label class="add-num" data-role="add-num"></label>'
            }
            else
            {
                show_add_num = '';
            }

            // 上传图片子项
            var item_img = function(params)
            {
                var tpl = '<div class="ui-upload ui-btn bg-color" data-file-id="'+params.id+'" id="upload-img-"'+params.id+'>'+
                        '<a href="javascript:void(0);" class="" data-role="drag-a">' +
                        '   <img src="'+params.url+'" title=""/>' +
                        '<div class="ui-upload-progress" style="display: none;" data-role="progress">上传中...</div>'+
                        '</a>'+
                        show_add_num+
                        '<div class="ui-upload-shadow"><a  class="close-img"></a></div>'+
                        '</div>';

                var $temp = $(tpl);

                // 鼠标经过显示预览图按钮
                $temp.hover(function()
                {
					if($temp.find('[data-role="progress"]').css('display') != 'none')
					{
						return;
					}
                    $(this).addClass('show');
                },function()
                {
                    $(this).removeClass('show');
                });

                // 绑定点击事件
                $temp.find('.close-img').on('click',function(ev)
                {

                    // 删除事件
                    var $cur_btn = $(ev.currentTarget);

                    var $img = $cur_btn.parents('[data-file-id]');

                    var file_id = $img.attr('data-file-id');

                    var file = uploader_obj.get_file_by_id(file_id);

                    uploader_obj.removeFile(file);

                    $img.remove();
                });


                return $temp;
            };

            var $temp_container = $(init_upload_str);

            var $first_page = $el.find('[data-role="first-page"]');
            var $sec_page = $el.find('[data-role="sec-page"]');
            var $preview_img_list = $sec_page.find('.upload-page-content');
            var $upload_tips = $sec_page.find('[data-role="upload-tips"]');

            var file_num_limit = self.upload_total_limit - self.add_item_img_obj.get_cur_img_list().length;

            if(!self.options.multiple)
            {
                file_num_limit = 1;
            }

			var details_params = {
                        auto : false,
                        fileNumLimit : file_num_limit,
                        pick :
                        {
                            id : '#first_file_picker',
                            label : '选择图片'
                        },
                        start_upload :
                        {
                            $el : $sec_page.find('#start-upload-btn')
                        },
                        formData : self.options.form_data
                    };

			details_params = $.extend(true,details_params,self.options);

            uploader_obj = yueyue_uploader($temp_container.find('#upload-wrapper'),details_params);

            uploader_obj.imgs = [];

            // 缩略图的size
            var thumbnailWidth = 130;
            var thumbnailHeight = 130;

            // 开始上传按钮
            uploader_obj.on('startUpload',function()
            {
                if(uploader_obj.state == 'uploading')
                {
                    return;
                }

                if($preview_img_list.length && self.options.threads == 1)
                {
                    $preview_img_list.find('[data-file-id]').each(function()
                    {
                        var file_id = $(this).data('file-id');
                        var add_num = $(this).find('[data-role="add-num"]').html();

                        uploader_obj.getFile(file_id).add_num = add_num;
                    });

                    uploader_obj.sort(function(a,b)
                    {
                        if(a.add_num < b.add_num)
                        {
                            return -1;
                        }
                        if(a.add_num > b.add_num)
                        {
                            return 1;
                        }

                        return 0;
                    });
                }

                uploader_obj.upload();
            });

			if(options.upload_total_limit > 1)
			{
				// 添加“添加文件”的按钮，
				uploader_obj.addButton
				({
					id: '[data-role="go-on-add-btn"]',
					label: '继续添加'
				});
			}



            // 当有文件添加进来的时候
            uploader_obj.on('fileQueued', function(file)
            {
                console.log(file);

                if(uploader_obj.file_count >= 1)
                {
                    $first_page.addClass('webuploader-element-invisible');
                    $sec_page.removeClass('webuploader-element-invisible');

                    // 当选择的文件数等于1时，可以进行继续选择
                    uploader_obj.refresh();

                }
                else
                {
                    // todo 异常处理
                    return;
                }


                // 更新状态
                uploader_obj.state = 'ready';

                update_status(uploader_obj.state,$upload_tips);

                // 创建缩略图
                // 如果为非图片文件，可以不用调用此方法。
                // thumbnailWidth x thumbnailHeight 为 100 x 100
                uploader_obj.makeThumb(file, function( error, src )
                {
                    if(error)
                    {
                        // 不能预览时的处理
                        return;
                    }

                    // 预览图片的路径
                    console.log(src);

                    var preview_img = item_img
                    ({
                        id : file.id,
                        url : src
                    });

                    $preview_img_list.append(preview_img);

                    if(self.options.threads == 1)
                    {
                        self.sort_thumb_list($preview_img_list);

                        $preview_img_list.sortable
                        ({
                            change : function()
                            {
                                setTimeout(function()
                                {
                                    self.sort_thumb_list($preview_img_list);
                                },500);
                            }
                        });
                    }



                }, thumbnailWidth, thumbnailHeight );
            });

            // 当文件被移除队列后触发。
            uploader_obj.on( 'fileDequeued', function(file)
            {
                if(!uploader_obj.file_count)
                {
                    // 更新状态
                    uploader_obj.state = 'pedding';

                    $first_page.removeClass('webuploader-element-invisible');
                    $sec_page.addClass('webuploader-element-invisible');

                    // 当选择的文件数等于1时，可以进行继续选择
                    uploader_obj.refresh();
                }



                update_status(uploader_obj.state,$upload_tips);

                console.log('remove file');
            });

            // 文件开始上传前触发，一个文件只会触发一次。
            uploader_obj.on('uploadStart', function(file)
            {
                var file_id = file.id;

                var $img = $('[data-file-id="'+file_id+'"]');

                $img.find('[data-role="progress"]').show();
            });

            // 文件上传过程中创建进度条实时显示。
            uploader_obj.on('uploadProgress', function(file, percentage)
            {
                console.log(percentage);

                var file_id = file.id;

                var $img = $('[data-file-id="'+file_id+'"]');

                var percentage = parseInt(percentage *100,10);

                $img.find('[data-role="progress"]').html(percentage+'%');
            });

            // 文件上传成功。
            uploader_obj.on( 'uploadSuccess', function(file,response)
            {
                // response 为服务端返回的数据
                // console.log(response);
				//
                // if(response.url instanceof Array)
                // {
                //     var url = response.url[0] || '';
                // }
                // else
                // {
                //     var url = response.url || '';
                // }
				//
                // var file_id = file.id;
				//
                // var $img = $('[data-file-id="'+file_id+'"]');
				//
                // $img.find('[data-role="progress"]').text('已完成');
				//
                // uploader_obj.imgs.push(url);

				// response 服务端返回数据
				if(response.code == 0)
			   {
				   $.ajax
				   ({
					   url : '//www.mojikids.com/ajax/upload/get_access_token.php',
					   success : function(response)
					   {
						   var res = response.res;

						   upload_obj.option('formData',{data:JSON.stringify(res.upload_params)});
						   upload_obj.option('server',res.server_url);

						   console.log(yue_upload_obj.init_img_url);
						   // 重新赋值，为了区分第一次读取的图片地址
						   yue_upload_obj.init_img_url = res.img_url+'?'+new Date().getTime();

					   }
				   })

			   }

            });

            // 文件上传失败，显示上传出错。
            uploader_obj.on( 'uploadError', function(file)
            {
                uploader_obj.imgs = [];
            });

            // 文件上传失败，显示上传出错。
            uploader_obj.on( 'uploadComplete', function(file)
            {
                //uploader_obj.imgs = [];
            });

            // 完成上传
            uploader_obj.on( 'uploadFinished', function(file)
            {


                if(uploader_obj.imgs.length>0)
                {
                    alert('上传成功');
                }
                else
                {
                    alert('上传失败，请检查网络是否正常');
                }

                if(self.options.upload_container)
                {
                    self.add_item_img_obj = self.add_item_img(self.options.upload_container,
                    {
                        imgs : uploader_obj.imgs
                    });
                }



            });

            uploader_obj.on( 'all', function( type )
            {
                var stats;
                switch( type )
                {
                    case 'uploadFinished':
                    case 'uploadError':
                        uploader_obj.state = 'confirm';
                        $('[data-role="go-on-add-btn"]').removeClass('webuploader-element-invisible ');
                        break;

                    case 'startUpload':
                        uploader_obj.state = 'uploading';

                        $('[data-role="go-on-add-btn"]').addClass('webuploader-element-invisible ');

                        break;

                    case 'stopUpload':
                        uploader_obj.state = 'paused';
                        break;


                }
            });

            /**
             * 更新状态
             * @param state
             * @param $el
             * @param options
             */
            function update_status(state,$el,options)
            {
                var text = '';
                var stats;
                var fileCount = uploader_obj.file_count;
                var fileSize = uploader_obj.file_size;

                if (state === 'ready')
                {
                    text = '选中' + uploader_obj.file_count + '张图片，共' +
                            WebUploader.formatSize( fileSize ) + '。';
                }
                else if ( state === 'confirm' )
                {
                    stats = uploader_obj.getStats();

                    if ( stats.uploadFailNum )
                    {
                        text = '已成功上传' + stats.successNum+ '张照片至相册，'+
                                stats.uploadFailNum + '张照片上传失败，<a class="retry" href="#">重新上传</a>失败图片或<a class="ignore" href="#">忽略</a>'
                    }

                }
                else
                {
                    stats = uploader_obj.getStats();
                    text = '共' + fileCount + '张（' +
                            WebUploader.formatSize( fileSize )  +
                            '），已上传' + stats.successNum + '张';

                    if ( stats.uploadFailNum )
                    {
                        text += '，失败' + stats.uploadFailNum + '张';
                    }
                }

                $el.html( text );
            }

            return uploader_obj;





        },
        get_img_list : function()
        {
            var self = this;

            var $el = self.options.upload_container;

            var arr = [];

            $el.find('[data-role="ui-success-upload-img"]').each(function(i)
            {
                var url = $(this).find('img').attr('src');
                if(url)
                {
                    arr.push(url)
                }
            });

            return arr;
        }
    };

})(window,$);

/**
 * 切换图片size
 * @param img_url
 * @param size
 * @returns {*}
 */
function matching_img_size(img_url,size)
{
    var size_str = '';

    size = size || '';

    size = parseInt(size,10);

    if($.inArray(size, [120,320,165,640,600,145,440,230,260]) != -1)
    {
        size_str = '_' +size;
    }
    else
    {
        size_str = '';
    }
    // 解析img_url

    var url_reg = /^http:\/\/(img|image)\d*(-c|-wap|-d)?(.poco.cn.*|.yueus.com.*)\.jpg|gif|png|bmp/i;

    var reg = /_(32|64|86|100|145|165|260|320|440|468|640).(jpg|png|jpeg|gif|bmp)/i;

    if (url_reg.test(img_url))
    {
        if(reg.test(img_url))
        {
            img_url = img_url.replace(reg,size_str+'.$2');

        }
        else
        {
            img_url = img_url.replace('/(\.\d*).jpg|.jpg|.gif|.png|.bmp/i', size_str+".jpg");//两个.jpg为兼容软件的上传图片名

        }
    }



    return img_url;
}
