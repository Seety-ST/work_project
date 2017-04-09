/**
* 微信 接口
*/
var Util = require.syncLoad(__moduleId('/modules/global/util/index'));
var WeiXinSDK =
{
    version : 'default'
};

WeiXinSDK.isWeiXin = function ()
{
    return /MicroMessenger/i.test(navigator.userAgent);
};

WeiXinSDK.chooseImage = function(options)
{
    wx.chooseImage({
        count: options.count, // 默认9
        sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
        sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
        success: function (res) {
            //alert("success" + JSON.stringify(res))
            if(options.success && typeof options.success === 'function')options.success(res);
            //var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图
        },
        cancel: function (res) {
            //alert("cancel" + JSON.stringify(res))
            if(options.cancel && typeof options.cancel === 'function')options.cancel(res);
        },
        fail : function(res){
            //alert("fail" + JSON.stringify(res))
            if(options.fail && typeof options.fail === 'function')options.fail(res);
        }
    });
}

WeiXinSDK.upload_imgs = function(options)
{
    var index = 0;

    var pics_length = options.localId.length;

    var ProgressTips =  options.isShowProgressTips || 1;// 默认为1，显示进度提示

    uploadSingle(options);

    var media_list = [];

    function uploadSingle(options)
    {
        wx.uploadImage({
            localId: options.localId[index], // 需要上传的图片的本地ID，由chooseImage接口获得
            isShowProgressTips: ProgressTips,
            success: function (res) {

                // alert("success" + JSON.stringify(res))
                var obj =
                {
                    localId : options.localId[index],
                    media_id : res.serverId
                }

                media_list.push(obj);

                if(options.success_single && typeof options.success_single === 'function')options.success_single(res,index,pics_length);

                index++;

                if(index >= options.localId.length)
                {
                    //结束所有传图后的回调
                    if(options.success_all && typeof options.success_all === 'function')options.success_all(media_list,index,pics_length);
                }
                else
                {
                    uploadSingle(options);
                }
            },
            cancel: function (res) {
                //alert("cancel" + JSON.stringify(res))
                if(options.cancel_single && typeof options.cancel_single === 'function')options.cancel_single(res,index,pics_length);
            },
            fail : function(res){
                //alert("fail" + JSON.stringify(res))
                if(options.fail_single && typeof options.fail_single === 'function')options.fail_single(res,index,pics_length);
            }
        });
    }
}

WeiXinSDK.downloadImage = function(options)
{
    if(!options.media_id)throw 'serverId/media_Id error!';

    wx.downloadImage({
        serverId: options.media_id, // 需要下载的图片的服务器端ID，由uploadImage接口获得
        isShowProgressTips: options.isShowProgressTips || 1, // 默认为1，显示进度提示
        success: function (res) {
            var localId = res.localId; // 返回图片下载后的本地ID
            if(options.success && typeof options.success === 'function')options.success(res);
        },
        cancel: function (res) {
            //alert("cancel" + JSON.stringify(res))
            if(options.cancel && typeof options.cancel === 'function')options.cancel(res);
        },
        fail : function(res){
            //alert("fail" + JSON.stringify(res))
            if(options.fail && typeof options.fail === 'function')options.fail(res);
        }
    });
}

WeiXinSDK.chooseImages_and_uploadImages_and_downloadImages = function(options)
{

    var upload_type = options.upload_type || "pics",
        $el = options.$el || $('body'),
        choose_trigger_str = options.choose_trigger_str || "chooseImages",
        choose_count = parseInt(options.choose_count) || 9,
        choose_success = options.choose_success || function(){},
        choose_cancel = options.choose_cancel || function(){},
        choose_fail = options.choose_fail || function(){},
        choose_success_change_count = options.choose_success_change_count || choose_count,
        upload_trigger_str = options.upload_trigger_str || "uploadImages",
        upload_success = options.upload_success || function(){},
        upload_cancel = options.upload_cancel || function(){},
        upload_fail = options.upload_fail || function(){},
        upload_success_all = options.upload_success_all || function(){},
        get_trigger_str = options.get_trigger_str || "getImagesUrl",
        get_imgUrl_beforeSend = options.get_imgUrl_beforeSend || function(){},
        get_imgUrl_success = options.get_imgUrl_success || function(){},
        get_imgUrl_error = options.get_imgUrl_error || function(){},
        get_imgUrl_complete = options.get_imgUrl_complete || function(){},
        platform = options.platform || ''

    var choosen_list = [];

    var media_list = [];

    $el.on(choose_trigger_str,function()
    {
        var pic_list = [];

        wx.chooseImage
        ({
            count : choose_count, //选图张数，拍照默认1张
            success : function(res)
            {
                //alert("success" + JSON.stringify(res))
                //res.sourceType 两种情况 : 'album' 相册  || 'camera' 相机
                //res.localIds 回调的图片数组
                pic_list = res.localIds;

                console.log(res);
                choosen_list = pic_list;

                if(choose_success && typeof choose_success === 'function')choose_success(res);

                if(choose_success_change_count && typeof choose_success_change_count === 'function')choose_count = choose_success_change_count.call(this,res);
            },
            cancel : function(res)
            {
                if(choose_cancel && typeof choose_cancel === 'function')choose_cancel(res);
            },
            fail : function(res)
            {
                if(choose_fail && typeof choose_fail === 'function')choose_fail(res);
            }
        });
    })

    $el.on(upload_trigger_str,function()
    {
        if(choosen_list.length != 0)
        {
            WeiXinSDK.upload_imgs
            ({
                localId : choosen_list, //传入localId数组[]
                success_single : function(resa,index)
                {
                    var serverId = resa.serverId; // 返回图片的服务器端ID
                    //alert("success" + JSON.stringify(resa))
                    if(upload_success && typeof upload_success === 'function')upload_success(resa,index,choosen_list.length);
                },
                cancel_single : function(resa,index)
                {
                    if(upload_cancel && typeof upload_cancel === 'function')upload_cancel(resa,index,choosen_list.length);
                },
                fail_single : function(resa,index)
                {
                    if(upload_fail && typeof upload_fail === 'function')upload_fail(resa,index,choosen_list.length);
                },
                success_all : function(media_obj_list,index)
                {
                    media_list = media_obj_list;
                    console.log('mdeia_list');
                    console.log(media_list);
                    //返回对象数组[{localId:"",mediaId:""},{},{}] mediaId用于下载该图
                    //alert("all" + JSON.stringify(media_obj_list))
                    if(upload_success_all && typeof upload_success_all === 'function')upload_success_all(media_obj_list,index,choosen_list.length);

                }
            })
        }
    })

    $el.on(get_trigger_str,function()
    {
        if(media_list.length != 0)
        {
            var user_id = Util.cookie.get('yue_member_id') || '10001';

            $.ajax
            ({
                url: 'http://www.mojikids.com/ajax/upload/wx_image.php',
                data : {obj_list:media_list,upload_type:upload_type,platform:platform,user_id:user_id},
                type: 'POST',
                cache: false,
                timeout : 30000,
                beforeSend: function()
                {
                    if(get_imgUrl_beforeSend && typeof get_imgUrl_beforeSend === 'function')get_imgUrl_beforeSend();
                },
                success: function(data)
                {
                    if(get_imgUrl_success && typeof get_imgUrl_success === 'function')get_imgUrl_success(data);
                },
                error: function(err)
                {
                    if(get_imgUrl_error && typeof get_imgUrl_error === 'function')get_imgUrl_error(err);
                },
                complete: function()
                {
                    if(get_imgUrl_complete && typeof get_imgUrl_complete === 'function')get_imgUrl_complete();
                }
            });

        }
    })

    this.set_choose_count = function(count)
    {
        choose_count = count
    }

    return this
}

module.exports = WeiXinSDK;
