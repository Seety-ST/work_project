define("pc-huipai:header_search/index",function(){function e(){var e=$.trim($('[header-s-data="key"]').val());return e?(a||(a="list"),void(window.location.href=window.__HUIPAI_GLOBAL.PAGE_URL.index+"search/?tp="+a+"&kw="+e)):(i.removeClass("on"),void s.removeClass("fn-hide"))}var a,d=$('[header-s-data="pop"]'),n=$('[header-s-data="title"]'),t=$('[header-s-data="click"]'),i=($('[header-s-data="show"]'),$('[data-role="header-s-search"]')),s=$('[header-s-data="click-first"]');s.on("click",function(e){e.stopPropagation(),$(this).addClass("fn-hide"),i.addClass("on")}),$('[header-s-data="btn"]').hover(function(){d.removeClass("fn-hide")},function(){d.addClass("fn-hide")}),d.find("li").on("click",function(){a=$(this).attr("header-s-data-role"),d.addClass("fn-hide");var e=$(this).html();n.html(e)}),t.on("click",function(){e()}),document.onkeydown=function(a){if(i.hasClass("on")){var a=a||event,d=a.keyCode||a.which||a.charCode;13==d&&e()}},$(document).click(function(e){var a=$(e.target);a.parents('[data-role="header-s-search"]').length||(i.removeClass("on"),s.removeClass("fn-hide"))})});