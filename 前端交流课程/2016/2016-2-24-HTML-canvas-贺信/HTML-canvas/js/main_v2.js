define(function(require, exports, module) {
	require("common");
	require("megapix-image");
	require("exif");
	var touch = require("touch.min");


	var loadImg = require("loadImg_v2");	
	var imgArr = ["images-v4/test/640x640.png","images-v4/bg-img/0.jpg","images-v4/bg-img/3.jpg","images-v4/ll-img-6.png","images-v4/ll-img-9.png","images-v4/ll-img-10.png","images-v4/ll-img-7.png","images-v4/ll-img-5.png","images-v4/model-img/s/1.jpg","images-v4/model-img/s/2.jpg","images-v4/model-img/s/3.jpg","images-v4/model-img/s/4.jpg","images-v4/model-img/b/1.png","images-v4/model-img/b/2.png","images-v4/model-img/b/3.png","images-v4/model-img/b/4.png"];
	
	var loading = showAnim(".loading-page"),
	
	loadingData = $("#loading_num");



 	loadImg(imgArr,onLoaded,function(_per){
	
		$(".upload-page").hide();
		loadingData.html(_per+"%");

	});

	function GetRandomNum (Min,Max)
    {   
        var Range = Max - Min;   
        var Rand = Math.random();   
        return(Min + Math.round(Rand * Range));   
    }

	function onLoaded(){
		$(".upload-page").show();
		loading.remove();
		
	}

	var drawBox = $("#upload_picture");
	var drawImg = drawBox.children().eq(0)[0];
	
	var drawMask = $("#photo_frame").children().eq(0);

	$(".draw_file").on("change",onChange);
	$(".draw_file2").on("change",onChange);
	
	
	function onChange(){
		
		if( this.value == "" ) return;
		getFile(this);
		
		if($("#hade_img").val() == "1"){
			hasSource = true;
		}
		$(".draw_file").addClass("hide");
	}

	var hasSource = false;	//是否已选图
	
	var updateIng = false;	//是否在上传
	
	$("#btn_nextstep").on("click",function(){
		if( !hasSource ){
			$("#please_update").show();
			setTimeout(hide_please,1500);
			return;
		}else{
			$("#please_waiting").show();
			$(".fade-op0").show();
			$(".draw_file2").addClass("hide");
			
			
			updateIng = true;
					
			$("#btn_submit").trigger("click");
			
		}
	});

    function hide_please(){
        $("#please_update").hide();
    }
	

	$("#s_list li").on("click",function(){
	
		var click_num = $("#s_list li").index(this) + 1;
		_hmt.push(['_trackEvent', '相框点击'+click_num, 'click']);
		
		if( !updateIng ){
			$(this).addClass("cur").siblings().removeClass("cur");
			var bg_img = $(this).attr('data-mask');
			$("#photo_frame").children().eq(0).attr("src",bg_img);
			
			$("#bg_picture").children().eq(0).attr("src",bg_img);
		}
		
		if( !hasSource ){
			var _demo = $(this).attr("data-demo");
			if( _demo ){
				drawBox.children().attr("src",_demo);
				return;
			}
		}
	});

	function chooseSource(){
		$(".draw_file").val("").trigger("click");
	}

	function getFile(source){
		var file = source.files[0];
		var mpImg = new MegaPixImage(file);
		var can = document.createElement("canvas");
		
		$("#please_wait").show();
		
		EXIF.getData(file, function() {
		    var _info = EXIF.getTag(this,'Orientation');
			mpImg.render(can, {maxWidth: 830, maxHeight: 830, quality: 0.9, orientation: _info },function(){
				
				$("#please_wait").hide();
				
				$("#hade_img").val("1");
				hasSource = true;
				$(".img-mod").width($("#photo_frame").width());
				$(".img-mod").height($("#photo_frame").height());
				
		    	drawImg.src = can.toDataURL("image/jpg",0.9);
				
		    	setSourceEdit();
			});
		});
	}

	function setKitEdit(_id){
		var img_offx = 0,img_offy = 0;
	    var initialScale = 1;
		var currentScale = 1;
		var angle = 0;
		var img_info = [0,0,1,0];
		var $element = $("#"+_id);
		var element = $element[0];
		!function(){
			var dx, dy;
			$element.on("touchstart touchmove touchend",function(e){
				e.preventDefault();
			});
			$element.find(".kit_close").on("touchend",function(){
				$("#chooser2 li").eq($(this).parent().attr("data-index")).trigger("click");
			})
			element.style.webkitTransform = "translate3d(" + img_info[0] + "px," + img_info[1] + "px,0) scale("+img_info[2]+","+img_info[2]+") rotate("+img_info[3]+"deg)";

			touch.on("#"+_id, 'dragstart', function(ev){
				$element.addClass("edit").siblings().removeClass("edit");
			});
			touch.on("#"+_id, 'drag', function(ev){
				dx = dx || 0;
				dy = dy || 0;
				img_info[0] = dx + ev.x;
				img_info[1] = dy + ev.y;
				element.style.webkitTransform = "translate3d(" + img_info[0] + "px," + img_info[1] + "px,0) scale("+img_info[2]+","+img_info[2]+") rotate("+img_info[3]+"deg)";
			});
			touch.on("#"+_id, 'dragend', function(ev){
				dx += ev.x;
				dy += ev.y;
			});
			touch.on("#"+_id, 'pinch', function(ev){
				if( ev.scale ){
					currentScale = ev.scale - 1;
					currentScale = initialScale + currentScale/12;
					currentScale = currentScale > 2 ? 2 : currentScale;
					currentScale = currentScale < 0.75 ? 0.75 : currentScale;
					img_info[2] = currentScale;
					element.style.webkitTransform = "translate3d(" + img_info[0] + "px," + img_info[1] + "px,0) scale("+img_info[2]+","+img_info[2]+") rotate("+img_info[3]+"deg)";
					initialScale = currentScale;
				}
			});
			touch.on("#"+_id, 'rotate', function(ev){
				if( Math.abs(ev.rotation) < 5 ){
					return;
				}
			    var totalAngle = img_info[3] + ev.rotation;
			    if(ev.fingerStatus === 'end'){
			    	img_info[3] = totalAngle;
			    }
				element.style.webkitTransform = "translate3d(" + img_info[0] + "px," + img_info[1] + "px,0) scale("+img_info[2]+","+img_info[2]+") rotate("+totalAngle+"deg)";
			});
		}();
	}

	function setSourceEdit(){
		var img_offx = 0,img_offy = 0;
	    var initialScale = 1;
		var currentScale = 1;
		var angle = 0;
		var img_info = [0,0,1,0];
		!function(){
			var dx, dy;
			$(".img-item").on("touchstart touchmove touchend",function(e){
				e.preventDefault();
			});
			drawImg.style.webkitTransform = "translate3d(" + img_info[0] + "px," + img_info[1] + "px,0) scale("+img_info[2]+","+img_info[2]+") rotate("+img_info[3]+"deg)";
			touch.on('.img-item', 'drag', function(ev){
				dx = dx || 0;
				dy = dy || 0;
				img_info[0] = dx + ev.x;
				img_info[1] = dy + ev.y;
				drawImg.style.webkitTransform = "translate3d(" + img_info[0] + "px," + img_info[1] + "px,0) scale("+img_info[2]+","+img_info[2]+") rotate("+img_info[3]+"deg)";
			});
			touch.on('.img-item', 'dragend', function(ev){
				dx += ev.x;
				dy += ev.y;
			});
			touch.on('.img-item', 'pinch', function(ev){
				if( ev.scale ){
					currentScale = ev.scale - 1;
					currentScale = initialScale + currentScale/12;
					currentScale = currentScale > 2 ? 2 : currentScale;
					currentScale = currentScale < 0.1 ? 0.1 : currentScale;
					img_info[2] = currentScale;
					drawImg.style.webkitTransform = "translate3d(" + img_info[0] + "px," + img_info[1] + "px,0) scale("+img_info[2]+","+img_info[2]+") rotate("+img_info[3]+"deg)";
					initialScale = currentScale;
				}
			});
			touch.on('.img-item', 'rotate', function(ev){
				if( Math.abs(ev.rotation) < 5 ){
					return;
				}
			    var totalAngle = img_info[3] + ev.rotation;
			    if(ev.fingerStatus === 'end'){
			    	img_info[3] = totalAngle;
			    }
				drawImg.style.webkitTransform = "translate3d(" + img_info[0] + "px," + img_info[1] + "px,0) scale("+img_info[2]+","+img_info[2]+") rotate("+totalAngle+"deg)";
			});
		}();
	}

	function savePhoto(cb){
		drawBox.off("touchstart touchmove touchend");
		var can = document.createElement("canvas");
		var ctx = can.getContext("2d"); 
    	var canWidth = can.width = $("#photo_frame").width();
		var canHeight = can.height = $("#photo_frame").height(); 

		
		var _imgList_2 = [];
			_imgList_2.push(drawBox.children().eq(0));	
			_imgList_2.push(drawMask);	
			
		
		var _imgUrl = [];
		var _imgConfig = [];

		$.each( _imgList_2,function(i){
			
			var _config = [], _trans = _imgList_2[i][0].style.webkitTransform;		
			if( $(_imgList_2[i]).hasClass("kits") ){
				_imgUrl.push($(_imgList_2[i]).attr("src"));
			}else{
				_imgUrl.push(_imgList_2[i][0].src);
			}
			
			_trans = _trans.match(/translate3d\((.*?)\) scale\((.*?)\) rotate\((.*?)\)/);
			_config.push(_trans ? parseInt(_trans[1].split(",")[0]):0);
			_config.push(_trans ? parseInt(_trans[1].split(",")[1]):0);
			_config.push(_trans ? _trans[2].split(",")[0]:1);
			_config.push(_trans ? parseInt(_trans[3].split(",")[0]):0);
			_imgConfig.push(_config);
			
		});
		
		loadImg(_imgUrl,function(_img){
			ctx.fillStyle="#FFFFFF";
			ctx.fillRect(0,0,canWidth,canHeight);
			$.each(_img,function(i){
				var _x = _imgConfig[i][0];
				var _y = _imgConfig[i][1];
				var _w = _img[i].width;
				var _h = _img[i].height;
				if( i > 1 ){
					_x += 100;
					_y += 100;
				}
				ctx.save();
				ctx.translate(_x+_w*0.5,_y+_h*0.5);
				ctx.rotate(_imgConfig[i][3]*Math.PI/180);
				ctx.scale(_imgConfig[i][2],_imgConfig[i][2]);
				if( i == 0){
					ctx.drawImage(_img[i],_w*-0.5,_h*-0.5,_w,_h);
				}else if( i == 1){
					ctx.drawImage(_img[i],_w*-0.5,_h*-0.5,canWidth,canHeight);	
				}
				
				ctx.restore();
			});
			var _data = can.toDataURL("image/jpg",0.9);
			

			cb && cb(_data);
		})
	}

	return {
		savePhoto : savePhoto
	}


});