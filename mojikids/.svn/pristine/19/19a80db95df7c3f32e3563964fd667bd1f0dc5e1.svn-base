$(function()
{

    // ============= 传图组件 =============       
	window.uploader_class = function(options)
	{
		var self = this;
		options = options || {};

		return self.init(options);        
	}

	uploader_class.prototype = 
    {
        init : function(options)
        {
            var self = this;

            if(!options.pick)
            {
                alert('上传组件初始化失败，缺少pick参数');
                return;
            }

            // 提交参数
            var form_data = 
            {
                poco_id : "100000",  
                sh_type : 'merchandise',
                role : 'yueseller'
            };

            form_data = $.extend(true,form_data,options.formData);
            form_data = JSON.stringify(form_data);

            var uploader = WebUploader.create
            ({

                // 不压缩image
                resize: false,
                auto : true,
                // swf文件路径
                swf: options.BASE_URL || 'http://static-c.yueus.com/yue_ui/upload/1.0.1/' + '/Uploader.swf',

                // 文件接收服务端。
                server: options.server || 'http://sendmedia-w.yueus.com:8079/upload.cgi',
                // 表单对象
                formData :
                {
                    params : form_data
                },
                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: options.pick 
            });            

            // 初始化文件数
            uploader.file_count = 0;
            uploader.file_size = 0;

            uploader.group_name = options.group_name +'[]' || 'group' +'[]'

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

            uploader.on( 'startUpload', function( file )
            {        
                // todo 全局回调                
            });
            uploader.on( 'uploadSuccess', function( file ,response) 
            {
                // todo 全局回调
            });

            uploader.on( 'uploadComplete', function( file ) 
            {
                // todo 全局回调
            });
            
            
            return uploader;
        }
    };
    // ============= 传图组件 =============           
})