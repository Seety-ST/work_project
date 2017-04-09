
/**
    by 汤圆
    头部搜索
    2016.10.28
 **/


var header_s_data ;
var $header_s_data_pop = $('[header-s-data="pop"]');
var $header_s_data_title = $('[header-s-data="title"]');
var $header_s_data_click = $('[header-s-data="click"]');
var $header_s_data_show = $('[header-s-data="show"]');
var $header_s_search = $('[data-role="header-s-search"]');
var $header_s_click_first = $('[header-s-data="click-first"]');


$header_s_click_first.on('click', function(event) {
    event.stopPropagation();
    $(this).addClass('fn-hide');
    // $header_s_search.removeClass('fn-hide');
    $header_s_search.addClass('on');

});


$('[header-s-data="btn"]').hover(function() {
    $header_s_data_pop.removeClass('fn-hide');
}, function() {
    $header_s_data_pop.addClass('fn-hide');
});

$header_s_data_pop.find('li').on('click', function(event) {
    header_s_data = $(this).attr('header-s-data-role');
    $header_s_data_pop.addClass('fn-hide');
    var txt = $(this).html();
    $header_s_data_title.html(txt);
});




$header_s_data_click.on('click', function(event) {
    search();
});
document.onkeydown = function(e){
    if ($header_s_search.hasClass('on')) 
    {


        var e = e || window.event;  
        var currKey=e.keyCode||e.which||e.charCode;
        if(currKey==13)
        {
            search();
        }
    }
}



// 搜索事件
function search()
{
    var val = $.trim($('[header-s-data="key"]').val());
    if (!val) 
    {
        // alert('请输入关键字!');
        $header_s_search.removeClass('on');
        $header_s_click_first.removeClass('fn-hide')
        return ;
    }
    if (!header_s_data) 
    {
        header_s_data = 'list';
    }
    window.location.href = window.__HUIPAI_GLOBAL['PAGE_URL'].index + 'search/?tp=' + header_s_data + '&kw=' + encodeURIComponent(val);
}



/*点击任何地方关闭层*/
$(document).click(function(event){
    var $tar = $(event.target);
    if($tar.parents('[data-role="header-s-search"]').length)
    {
    }
    else
    {
        // $header_s_search.addClass('fn-hide');
        $header_s_search.removeClass('on');
        $header_s_click_first.removeClass('fn-hide')
    }
});






