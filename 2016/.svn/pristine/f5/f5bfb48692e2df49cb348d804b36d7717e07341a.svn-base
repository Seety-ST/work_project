var wx_title = "我用美人相机拍了段好玩的视频";
var wx_desc = "时尚女生必备";

var wx_link_url = window.location.href;

//默认图片
var wx_img_url = "http://www1.poco.cn/topic/qing_special/beautycamera_2016/images/play.png";

get_wx();

function replaceAll(str) {
	if (str != null)
		str = str.replace(/&/g, "%26")
			return str;
}

function get_wx() {
	var appid = 'wxcab341c1bbe07bf1';
	var channel = '_beautycamera_2016';
	var url_wei = location.href.split('#')[0];
	url_wei = replaceAll(url_wei);

	$.get('http://www1.poco.cn/topic/interface/weixin_share_api.php', 'appid=' + appid + '&channel=' + channel + '&url=' + url_wei, function (json_data) {

		if (!json_data)
			return;

		var arr_ = JSON.parse(json_data);
		var get_timestamp = arr_['timestamp'];
		var get_nonceStr = arr_['nonceStr'];
		var get_signature = arr_['signature'];

		wx.config({
			debug : false,
			appId : appid,
			timestamp : get_timestamp,
			nonceStr : get_nonceStr,
			signature : get_signature,
			jsApiList : ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ','onMenuShareQZone']
		});
	});
}
