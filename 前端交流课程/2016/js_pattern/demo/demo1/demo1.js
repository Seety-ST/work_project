$(function(){
var getMask=(function(){
	var cache,$_parent,fn_creatDiv,fn_bindEvent;
	$_parent=$("body");
	//获取遮罩DOM
	fn_creatDiv=function(){
		var cc_css,cc_div;
		cc_css={
            "background":'block',
            "width":'100%',
            "height":'100%',
            "position":'fixed',
            "left":0,
            "top":0,
            "zIndex":9999,
            "background":"#000000",
            "opacity":0.5
        };
        div=$("<div class='mask'></div>");
        div.css(cc_css);
        return div;
	};
	fn_bindEvent=function(){
		this.on("event_close",function(){
			this.remove();
			cache=false;
		});
	}
	return function(){
    	if(!cache){//是否已缓存
	        cache=fn_creatDiv();
	        $_parent.css({"position":"relative"}).append(cache);
	        fn_bindEvent.call(cache);
	        console.log("new")
	    }
	    else{
	    	console.log("cache");
	    }
	    return cache;
    };
})();

var cc_mask;
$(".btn_show").click(function(){
	cc_mask=getMask();
})
$(".btn_close").click(function(){
	cc_mask.trigger("event_close");
})
})


