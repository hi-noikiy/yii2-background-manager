<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我的邮箱</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->

    <link href="css/css/mui.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/css/reset.css">
    <link rel="stylesheet" href="css/css/public.css">
    <link rel="stylesheet" href="css/css/index.css">

    <style>
        .mui-bar-nav~.mui-content{
            padding-bottom: 0px;
        }
        .mui-content ul{
            padding:0px 10px;
        }
        .mui-content ul li{
            position: relative;
            border:1px solid #ddd;
            padding:5px 10px;
        }
        .mui-content ul li button{
            position: absolute;
            right:10px;
            top:10px;
        }
        .mui-bar-nav~.mui-content{
            bottom:0px;
        }
        .mui-content ul{
            padding:0 10px;
        }
    </style>
</head>
<body>

<!-- 顶部标题  -->
<header class="mui-bar mui-bar-nav">
    <h1 class="mui-title" v-html="userData.web_daili_title"></h1>
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <p class="game_info">
        <span v-html="userData.current_game"></span>
        <span v-html="userData.play_name"></span>
        <span v-html="userData.cur_level"></span>
    </p>
</header>

<!--内容区域-->
<div class="mui-content">
    <!-- 跑马灯 -->
    <div class="light">
        <marquee  height="20" behavior="scroll" direction="left" scrollamount="2"  onMouseOut="this.start()">
            <ul class="marquee">
                <li></li>
            </ul>
        </marquee>
    </div>
    <ul>
        <li class="mui-text-center">邮箱列表</li>
        <li class="mui-text-center" v-show="mailList.length==0">暂无邮件...</li>
        <li v-for="mail in mailList" v-show="mailList.length!=0">
            <p v-html="mail.CONTENT"></p>
            <p v-html="mail.CREATE_TIME | toBlank"></p>
            <button v-on:click="delMail(mail.MAIL_ID)">删除</button>
        </li>
    </ul>
</div>

<div class="loading">
    <div class="loading_inner">
        <img src="img/loading1.gif" style="width:30px;height:30px;">
    </div>
</div>

<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<script type="text/javascript" src="js/js/md5.js?v=2"></script>
<script type="text/javascript" src="js/js/public.js?v=2"></script>

<script>
    //邮箱时间 + 用 空格代替
    Vue.filter('toBlank', function(value){
        return value.replace('+',' ');
    });

    var vm = new Vue({
        el : 'body',
        ready: function() {
            this.getUserInfo();
            this.marquee();
            this.maillist();
        },
        data : {
            userData : null,
            marqueeData : null,
            mailList : null,
            mailArr : []
        },
        methods : {
            getUserInfo : function(){
                var that = this;
                //获取用户信息
                var signData;
                if( !is_weixin ){    //游戏内 --> 签名处理
                    var str = 'gid='+ loginGid + '&logintime=' + loginTime + '&r=daili/get-user-info'+'&sign='+ loginSign;
                    var md5Str = $.md5(str).toUpperCase();
                    signData = {
                        sign : md5Str,
                        logintime : loginTime
                    }
                }else{
                    signData = '';
                }

                jsonAjax( 'get', base_url + '&r=daili/get-user-info', signData, function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        that.userData = json.data;
                    }
                });
            },
            marquee : function(){
                //跑马灯
                var signData;
                if( !is_weixin ){    //游戏内 --> 签名处理
                    var str = 'gid='+ loginGid + '&logintime=' + loginTime + '&r=daili/get-marquee'+'&sign='+ loginSign;
                    var md5Str = $.md5(str).toUpperCase();
                    signData = {
                        sign : md5Str,
                        logintime : loginTime
                    }
                }else{
                    signData = '';
                }

                $('.marquee').empty();
                jsonAjax( 'get', base_url + '&r=daili/get-marquee', signData, function(res){
                    var json = res;
                    var str = '';
                    if( json.ret_code == 0 ){
                        if( json.data.length != 0 ){
                            for( var i = 0 ; i < json.data.length ; i++){
                                str += '<li>'+ json.data[i] +'</li>';
                            }
                            $('.marquee').empty();
                            $('.marquee').append(str);
                        }
                    }
                    var width = json.data.length * 100 + '%';
                    $('.marquee').css({ 'width' : width});
                });
            },
            maillist : function(){
                var that = this;
                //获取邮件列表
                var signData;
                if( !is_weixin ){    //游戏内 --> 签名处理
                    var str = 'gid='+ Request.gid + '&logintime=' + loginTime + '&r=daili/get-mail'+'&sign='+ loginSign;
                    var md5Str = $.md5(str).toUpperCase();
                    signData = {
                        sign : md5Str,
                        logintime : loginTime
                    }
                }else{
                    signData = '';
                }


                jsonAjax( 'get', base_url + '&r=daili/get-mail', signData, function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        if( json.data.length != 0 ){
                            that.mailList = json.data;

                            //修改邮件阅读状态
                            var arr = [];
                            for( var i = 0; i < json.data.length; i++ ){
                                if( json.data[i].STATUS == 1 ){                        //未读状态邮件
//                                        arr.push(json.data[i].MAIL_ID);
                                    //修改未读邮件状态
                                    var signData;
                                    if( !is_weixin ){
                                        signData = {
                                            gid : Request.gid,
                                            r : 'daili/read-mail',
                                            logintime : loginTime,
                                            mail_id : json.data[i].MAIL_ID
                                        };

                                        var sortdic = signData;

                                        var sortstr = '';
                                        var sdic = Object.keys(sortdic).sort();
                                        for(ki in sdic){
                                            sortstr += "&"+sdic[ki]+"="+signData[sdic[ki]];
                                        }
                                        var result = sortstr.substr(1) + '&sign=' + loginSign;
                                        var md5Str = $.md5(result).toUpperCase();

                                        signData.sign = md5Str;
                                    }else{
                                        signData = {
                                            mail_id : json.data[i].MAIL_ID
                                        };
                                    }

                                    jsonAjax( 'post', base_url + '&r=daili/read-mail', signData, function(res){
                                        var json = res;
                                        if( json.ret_code == 0 ){
                                            //mui.alert('修改成功');
                                        }
                                    });
                                }
                            }

                        }else{
                            that.mailList = '';
                        }
                    }
                },function(){
                    $('.loading').show();
                },function(){
                    $('.loading').hide();
                });

            },
            delMail : function(MAIL_ID){
                var that = this;
                //删除邮件
                var signData;
                if( !is_weixin ) {
                    signData = {
                        gid: Request.gid,
                        r: 'daili/del-mail',
                        logintime: loginTime,
                        mail_id: MAIL_ID
                    };

                    var sortdic = signData;

                    var sortstr = '';
                    var sdic = Object.keys(sortdic).sort();
                    for (ki in sdic) {
                        sortstr += "&" + sdic[ki] + "=" + signData[sdic[ki]];
                    }
                    var result = sortstr.substr(1) + '&sign=' + loginSign;
                    var md5Str = $.md5(result).toUpperCase();

                    signData.sign = md5Str;
                }else{
                    signData = {
                        mail_id: MAIL_ID
                    }
                }

                jsonAjax( 'post', base_url + '&r=daili/del-mail', signData, function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        mui.alert('删除成功','',function(){
                            //再次请求邮箱列表
                            that.maillist();
                        });
                    }
                });

            }
        }
    });

</script>
</body>
</html>