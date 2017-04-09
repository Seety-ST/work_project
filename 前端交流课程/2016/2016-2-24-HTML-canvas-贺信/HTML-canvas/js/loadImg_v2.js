define(function(require, exports, module) {
	//var ossurl = "";
	var ossurl = "http://img2.poco.cn/wap_images/new_bugles/";
	return function (imgUrl,loadComplete,setLoadingInfo){
		var _imgOBJ = [];
	    var len = imgUrl.length;
	    var num = 0;
	    var checkLoad = function(){
	        num++;
	        setLoadingInfo && setLoadingInfo(parseInt(num/len*100));
	        if( num == len ){
	            loadComplete && loadComplete(_imgOBJ);
	        }
	    }
	    var checkImg = function(url,_i){
	        var val = ossurl + url;
	    	if( url.indexOf("base64") > -1 ){
	    		val = url;
	    	}
			if( url.indexOf("/model-img/b/") > -1){
				val = url;
			}
	        var img=new Image();
            img.onload=function(){
                if(img.complete==true){
                    checkLoad();
                }
            }
	        img.src=val;
	        _imgOBJ[_i] = img;
	    }

	    for( var i = 0; i < len; i++ ){
	        checkImg(imgUrl[i],i);
	    }
	}
});