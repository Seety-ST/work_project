define("wap-huipai:global/cookie/index",function(){function e(e){return encodeURIComponent(e)}function n(e){return decodeURIComponent(e.replace(/\+/g," "))}function t(e){return"string"==typeof e&&""!==e}var o="/",i=".yueus.com",r={get:function(e,o){o=o||{};var i,r;return t(e)&&(r=String(c.cookie).match(new RegExp("(?:^| )"+e+"(?:(?:=([^;]*))|;|$)")))&&r[1]&&(i=o.decode?n(r[1]):r[1]),i},set:function(n,r,a){a=a||{};var p=String(a.encode?e(r):r),d=a.expires,s=a.domain||i,m=a.path||o,f=a.secure;return"number"==typeof d&&(d=new Date,d.setTime(d.getTime()+a.expires*u)),d instanceof Date&&(p+="; expires="+d.toUTCString()),t(s)&&(p+="; domain="+s),t(m)&&(p+="; path="+m),f&&(p+="; secure"),c.cookie=n+"="+p,p},del:function(e,n){return this.set(e,"",{expires:-1,domain:n.domain||i,path:n.path||o,secure:n.secure})}},c=window.document,u=864e5;return r});