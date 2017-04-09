//微信分享js 使用文件
// @update 2016-8-5 for xiaohao
;(function() {
    var link_url = window.location.href; //全局设置分享当前连接
    var share_title = "沃尔沃2017款V40&V40 Cross Country 越界车 一路 本色向前";
    var qq_share_title = "沃尔沃2017款V40&V40 Cross Country 越界车";
    var share_desc = "一路 本色向前";
    var share_img = "http://www1.poco.cn/topic/qing_special/volvov40_2016/images_8m/share_200x200.jpg";

    function replaceAll(str) {
        if (str != null)
            str = str.replace(/&/g, "%26");
        return str;
    }

    function get_wx() {

        var appid = 'wxcab341c1bbe07bf1'; //appid与后台的appsecret必须一致.
        var channel = '_volvov40_2016'; //页面分享名称
        var url_wei = location.href.split('#')[0];
        url_wei = replaceAll(url_wei); //根据当前实际地址修改URL必须完全一致，否则返回invalid signature签名错误。


        $.get('http://www1.poco.cn/topic/interface/weixin_share_api.php', 'appid=' + appid + '&channel=' + channel + '&url=' + url_wei, function(json_data) {

            if (!json_data) return;
            //alert(json_data);

            var arr_ = JSON.parse(json_data);
            var get_timestamp = arr_['timestamp'];
            var get_nonceStr = arr_['nonceStr'];
            var get_signature = arr_['signature'];

            wx.config({
                debug: false,
                appId: "wxcab341c1bbe07bf1",
                timestamp: get_timestamp,
                nonceStr: get_nonceStr,
                signature: get_signature,
                jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'chooseImage', 'previewImage', 'uploadImage'] // 功能列表，我们要使用JS-SDK的什么功能
            });

        });


    }


    get_wx();

    wx.ready(function() {
        // 获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
        wx.onMenuShareTimeline({
            title: share_title, // 分享标题
            link: link_url,
            imgUrl: share_img, // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
            }
        });
        // 获取“分享给朋友”按钮点击状态及自定义分享内容接口
        wx.onMenuShareAppMessage({
            title: share_title, // 分享标题
            desc: share_desc, // 分享描述
            link: link_url,
            imgUrl: share_img, // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            success: function() {
                // 用户确认分享后执行的回调函数
            }
        });
        //qq
        wx.onMenuShareQQ({
            title: qq_share_title, // 分享标题
            desc: share_desc, // 分享描述
            link: link_url, // 分享链接
            imgUrl: share_img, // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
            }
        });
        wx.onMenuShareQZone({
            title: qq_share_title, // 分享标题
            desc: share_desc, // 分享描述
            link: link_url, // 分享链接
            imgUrl: share_img, // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
            }
        });

    });
})();