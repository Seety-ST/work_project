$(function(){
    var ind = 0;
    var nav = $("#nav");
    var nav_li = $("#nav .nav-li");
    var index = $("#nav .cur").index();
    var init = $("#nav .nav-li").eq(index);
    var block = $("#nav .block");
    block.css({
        "left": init.position().left,
        'width':init.width()
    });
    nav_li.hover(function() {
        nav_li.stop().animate({'opacity':'0.5'}, 200);
        $(this).stop().animate({'opacity':'1'},200);
        
    }, 
    function() {
        block.stop().animate({
            "left": init.position().left,
            'width':init.width()

        },500);

        nav_li.stop().animate({
            'opacity':'1'
        }, 200);

    });
    $("#nav").slide({
        type: "menu",
        titCell: ".nav-li",
        targetCell: ".sub",
        delayTime: 300,
        triggerTime: 0,
        returnDefault: true,
        defaultIndex: index,
        startFun: function(i, c, s, tit) {
            block.stop().animate({
                "left": tit.eq(i).position().left,
                "width":tit.eq(i).width()
            },
            500);
        }
    });
});


//…Ë÷√
