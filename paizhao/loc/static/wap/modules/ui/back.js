define('wap-huipai:ui/back', function(require, exports, module){ //  返回首页


$(function() {
    $('[data-role="back"]').on('click', function(event) {
        if(document.referrer)
        {
            window.history.back();
            return ;
        }
        else
        {
            window.location.href = './index.htm' ;
        }
    });
}); 
});