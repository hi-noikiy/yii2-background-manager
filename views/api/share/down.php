<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>一拳娱乐</title>
    <script type="text/javascript">
        function UrlSearch(){
            var name,value;
            var str=location.href; //取得整个地址栏
            var num=str.indexOf("?")
            str=str.substr(num+1); //取得所有参数   stringvar.substr(start [, length ]

            var arr=str.split("&"); //各个参数放到数组里
            for(var i=0;i < arr.length;i++){
                num=arr[i].indexOf("=");
                if(num>0){
                    name=arr[i].substring(0,num);
                    value=arr[i].substr(num+1);
                    this[name]=value;
                }
            }
        }
        var Request=new UrlSearch(); //实例化

        // 获取终端的相关信息
        var Terminal = {
            // 辨别移动终端类型
            platform : function(){
                var u = navigator.userAgent, app = navigator.appVersion;

                return {
                    // android终端或者uc浏览器
                    android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1,
                    // 是否为iPhone或者QQHD浏览器
                    iPhone: u.indexOf('iPhone') > -1 ,
                    // 是否iPad
                    iPad: u.indexOf('iPad') > -1
                };
            }(),
            // 辨别移动终端的语言：zh-cn、en-us、ko-kr、ja-jp...
            language : (navigator.browserLanguage || navigator.language).toLowerCase()
        }

        var urltype = (Request.urltype == 2)?2:1;
        // 根据不同的终端，跳转到不同的地址
        var theUrl = '#';
        var timestamp =Date.parse(new Date());  //获取当前时间戳，毫秒部分为000

        if(Terminal.platform.android){
            theUrl = '/api/share/android?random='+timestamp;
        }else if(Terminal.platform.iPhone){
            theUrl = '/api/share/ios?random=' + timestamp;
        }else if(Terminal.platform.iPad){
            theUrl = '/api/share/ios?random=' + timestamp;
        }

        // theUrl = '/api/share/ios?random=' + timestamp;
        //theUrl = 'http://llmj.cdn.xianyugame.com/down/neimeng/android_download.html?random='+timestamp;
        location.href = theUrl;

    </script>
</head>
<body>
</body>

</html>