//判断是否为微信浏览器
var is_weixin;
var ua = navigator.userAgent.toLowerCase();
if(ua.match(/MicroMessenger/i)=="micromessenger") {
    is_weixin = true;
} else {
    is_weixin = false;
}
//获取url
function UrlSearch() {
    var name, value;
    var str = location.href; //取得整个地址栏
    var num = str.indexOf("?");
    str = str.substr(num + 1); //取得所有参数   stringvar.substr(start [, length ]

    var arr = str.split("&"); //各个参数放到数组里
    for (var i = 0; i < arr.length; i++) {
        num = arr[i].indexOf("=");
        if (num > 0) {
            name = arr[i].substring(0, num);
            value = arr[i].substr(num + 1);
            this[name] = value;
        }
    }
}
var Request = new UrlSearch(); //实例化
var base_url = "https://" + location.host + "/index.php?";

if(is_weixin){
    var sign = "&gid="+Request.gid+"&wx_mp="+Request.wx_mp;
}else{
    var sign = "&gid="+Request.gid+"&logintime="+Request.logintime+"&uid="+Request.uid+"&sign="+Request.sign;
}


/**
 * 页面内跳转用JS
 * @Author   WKein
 * @DateTime 2017-11-15T22:57:30+0800
 * @return   {[type]}                 [description]
 */
function urlto(r,other){
    other = other||'';
	tourl = base_url+'r='+r+sign+other;
	location.href = tourl;
}



 //日期时间计算
function getBeforeDate(n,d) {
    var n = n;
    d = d||0;
    if( d == 0 ){
        d = new Date();
    }
    var year = d.getFullYear();
    var mon = d.getMonth() + 1;
    var day = d.getDate();
    if(day <= n) {
        if(mon > 1) {
            mon = mon - 1;
        } else {
            year = year - 1;
            mon = 12;
        }
    }
    d.setDate(d.getDate() - n);
    year = d.getFullYear();
    mon = d.getMonth() + 1;
    day = d.getDate();
    s = year + "-" + (mon < 10 ? ('0' + mon) : mon) + "-" + (day < 10 ? ('0' + day) : day);
    return s;
}



