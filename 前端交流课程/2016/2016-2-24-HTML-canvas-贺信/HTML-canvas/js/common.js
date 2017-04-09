define(function(require, exports, module) {
	require("fastclick");
	FastClick.attach(document.body);

	var wxData = {
        share:function(){
            wx.onMenuShareTimeline({
                title: wxData.desc,
                link: wxData.link,
                imgUrl: wxData.imgUrl,
                success: function() {
                    wxData.callback();
                },
                cancel: function() {

                }
            });
            wx.onMenuShareAppMessage({
                title: wxData.title,
                desc: wxData.desc2 || wxData.desc,
                link: wxData.link,
                imgUrl: wxData.imgUrl,
                type: '',
                dataUrl: '',
                success: function() {
                    wxData.callback();
                },
                cancel: function() {
                }
            });
			//qq
			wx.onMenuShareQQ({
			title: wxData.title,
			desc: wxData.desc2 || wxData.desc,
			link: wxData.link,
			imgUrl: wxData.imgUrl,
			success: function () { 
                wxData.callback();
			}
			});	
	
        },
        callback:function(){
            
        }
    };
	
    var boxs = $(".box"),
    mask = $(".mask");
    function _showBox(_boxName){
        var box = boxs.filter(".box_"+_boxName);
        mask.removeClass("hide");
        //boxs.removeClass("show");
        box.removeClass("hide");
        setTimeout(function(){
            mask.addClass("show");
            box.addClass("show");
        },17);
        return box;
    }
    function _hideBox(_boxName){
        window.scrollTo(0,0);
        if(_boxName){
            var box = boxs.filter(".box_"+_boxName);
                box.removeClass("show");
            setTimeout(function(){
                box.addClass("hide");
            },200);
            return box;
        }
        mask.removeClass("show");
        boxs.removeClass("show");
        setTimeout(function(){
            boxs.addClass("hide");
            mask.addClass("hide");
        },500)
    }
    boxs.on("click",function(e){
        e.stopPropagation();
    });
    mask.find(".close").on("click",function(){
        var _boxName = $(this).attr("data-close");
        if( _boxName ){
            _hideBox(_boxName);
            return;
        }
        _hideBox();
    });
    $("[data-box]").on("click",function(e){
        e.stopPropagation();
        e.preventDefault();
        _showBox($(this).attr("data-box"));
    })

    var msgBox = $(".msg_box"),msg = msgBox.children().eq(0);
    function _showMsg(_msg,hide){
        msgBox.addClass("show");
        _msg && msg.html(_msg);
        msgBox.css("margin-top",-msgBox.height()/2);
        hide && setTimeout(_hideMsg,hide);
    }
    function _hideMsg(){
        msgBox.removeClass("show");
    }

	function _showAnim(_box,_self) {
        var $animateDom = $(_box);
        var $element = _self ? $animateDom : $animateDom.find('[data-animation]');
        $element.css({
            '-webkit-animation': 'none',
            'display': 'none'
        });
        $element.each(function(index, element){
            var $element    = $(element),
                $animation  = $element.attr('data-animation'),
                $duration   = $element.attr('data-duration') || 500,
                $timfunc    = $element.attr('data-timing-function') || 'ease',
                $delay      = $element.attr('data-delay') ?  $element.attr('data-delay') : 0,
                $iterate    = $element.attr('data-iterate') ? $element.attr('data-iterate') : 1;
            $element.css({
                'display': 'block',
                '-webkit-animation-name': $animation,
                '-webkit-animation-duration': $duration + 'ms',
                '-webkit-animation-timing-function': 'ease',
                '-webkit-animation-timing-function': $timfunc,
                '-webkit-animation-delay': $delay + 'ms',
                '-webkit-animation-iteration-count': $iterate,
                '-webkit-animation-fill-mode': 'both'
            });

        });

        return $animateDom;
    }

    if(  $(".viewport").length )
    {
        $(".viewport").css({
            "-webkit-transform-origin":"center top",
            "-webkit-transform":"scale("+window.innerHeight/1040+","+window.innerHeight/1040+")"
        });
    }
	
    window.wxData = wxData;
    window.showAnim = _showAnim;
    window.showBox = _showBox;
    window.hideBox = _hideBox;
    window.showMsg = _showMsg;
    window.hideMsg = _hideMsg;
});