<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>我的信息</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />
    <meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->

    <link rel="stylesheet" href="css/css/mui.min.css">

    <link rel="stylesheet" href="css/css/reset.css">
    <link rel="stylesheet" href="css/css/public.css">

    <style>
        .mui-bar-nav~.mui-content{
            padding-bottom: 0px;
        }
        .info{
            padding:0 10px;
        }
    </style>

</head>
<body class="mui-fullscreen">
<!-- 顶部标题  -->
<header class="mui-bar mui-bar-nav">
    <h1 class="mui-title" v-html="userData.user_info.web_daili_title"></h1>
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <p class="game_info">
        <!--<span v-html="userData.user_info.current_game"></span>-->
        <span v-html="userData.user_info.play_name"></span>
        <span v-html="userData.user_info.cur_level"></span>
    </p>
</header>

<div class="mui-content">
    <!-- 跑马灯 -->
    <div class="light">
        <marquee  height="20" behavior="scroll" direction="left" scrollamount="2"  onMouseOut="this.start()">
            <ul class="marquee">
                <li></li>
            </ul>
        </marquee>
    </div>
    <div class="info">
        <!-- 基本信息 -->
        <table class="mui-table">
            <tr>
                <th colspan="2">我的基本信息</th>
            </tr>
            <tr>
                <td>帐号ID</td>
                <td v-html="userData.user_info.user_id"></td>
            </tr>
            <tr>
                <td>银行卡号</td>
                <td v-html="userData.user_info.bank_account"></td>
            </tr>
            <tr>
                <td>真实姓名</td>
                <td v-html="userData.user_info.true_name" v-show="!flag"></td>
                <td v-show="flag">
                    <input type="text" v-model="name" id="name" style="margin:0px;width:85%;">
                </td>
            </tr>
            <tr>
                <td>手机号</td>
                <td v-html="userData.user_info.tel" v-show="!flag"></td>
                <td v-show="flag">
                    <input type="text" v-model="tel" id="tel" style="margin:0px;width:85%;">
                </td>
            </tr>
            <tr>
                <td>推广级别</td>
                <td v-html="userData.user_info.cur_level"></td>
            </tr>
            <tr>
                <td>会员数</td>
                <td v-html="userData.user_info.member_num"></td>
            </tr>
            <tr>
                <td>名下代理</td>
                <td v-html="userData.user_info.daili_num"></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:5px 0;">
                    <input type="button" value="信息修改" v-on:tap="tips()" v-show="!flag">
                    <input type="button" value="修改" v-on:tap="editBtn()" v-show="flag">
                    <input type="button" value="返回" v-on:tap="goback()"  v-show="flag">
                </td>
            </tr>
        </table>
        <!-- 等级显示 -->
        <ul class="mui-table-view" id="level_des">
            <li class="mui-table-view-cell mui-media">
                <h5 class="mui-text-center">
                    <span>当前等级：<strong v-html="userData.user_info.cur_level"></strong></span>
                    <span v-show="userData.user_info.daili_level!='1'">下一等级：<strong v-html="userData.user_info.next_level" style="color:#FF3232;"></strong></span>
                </h5>
            </li>
        </ul>
        <!--进度条-->
        <ul class="mui-table-view" id="show_progress" v-show="userData.user_info.current_game!='昭通棋牌'">
            <li class="mui-table-view-cell mui-media" v-for="val in dailiUpData" v-show="dailiUpData.length!=0">
                <div class="mui-text-center">
                    <p>
                        <span v-html="val.content"></span>:
                        <span v-html="val.done" style="color:#FF3232;"></span>
                        <span v-show="userData.user_info.daili_level!='1'">/<span v-html="val.target"></span></span>
                    </p>
                    <p class="mui-progressbar mui-progressbar-in" data-progress="{{(val.done)/(val.target)*100}}">
                        <span></span>
                        <strong><i v-html="(val.done)/(val.target)*100 | currency '' ''"></i>%</strong>
                    </p>
                </div>
            </li>
            <li class="mui-text-center" v-show="dailiUpData.length==0">
                暂未开放，详情请联系客服咨询！
            </li>
            <li class="mui-table-view-cell mui-media">
                <h5 class="mui-text-center">
                    <span>当前分成：<strong v-html="userData.cur_per"></strong>%</span>
                    <span v-show="userData.user_info.daili_level!='1'">升级后分成：<strong v-html="userData.target_per" style="color:#FF3232;"></strong>%</span>
                </h5>
            </li>
            <li class="mui-table-view-cell mui-media mui-text-center" style="color:#ddd;" v-show="userData.user_info.special_game_desc!=''">
                <span v-html="userData.user_info.special_game_desc"></span>
            </li>
        </ul>
    </div>
</div>

<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<script type="text/javascript" src="js/js/md5.js?v=2"></script>
<script type="text/javascript" src="js/js/public.js?v=2"></script>

<script>

    var vm = new Vue({
        el : 'body',
        ready: function() {
            this.getUserInfo();
            this.marquee();
        },
        data : {
            userData : null,
            dailiUpData : [],
            marqueeData : null,
            pay_back_monney : 0,
            member_num : 0,

            flag : false,
            name : '',
            tel : '',
            true_name : '',
            user_tel : ''
        },
        methods : {
            getUserInfo : function(){
                var that = this;
                //获取用户信息
                var signData;
                if( !is_weixin ){    //游戏内 --> 签名处理
                    var str = 'gid='+ loginGid+ '&logintime=' + loginTime + '&r=daili/get-user-info'+'&sign='+ loginSign;
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

                        //代理级别判断
                        var cur_per       = '';    //当前分成
                        var target_per    = '';    //下一分成

                        switch(json.data.daili_level) {
                            case '1':
                                cur_per     =  json.data.daili_rule.rule1;
                                break;
                            case '2':
                                cur_per     =  json.data.daili_rule.rule2;
                                target_per  =  json.data.daili_rule.rule1;
                                break;
                            case '3':
                                cur_per     =   json.data.daili_rule.rule3;
                                target_per  =   json.data.daili_rule.rule2;
                                break;
                        }

                        var data = {
                            user_info : json.data,
                            cur_per: parseInt(cur_per*100),
                            target_per : parseInt(target_per*100)
                        };

                        vm.$set('userData', data);
                        vm.$set('dailiUpData', json.data.daili_up);

                        setTimeout(function(){
                            mui(".mui-progressbar").each(function () {
                                mui(this).progressbar({progress:this.getAttribute("data-progress")}).show();
                            });
                        },2000);


                        //修改个人信息 真实姓名，手机号
                        that.true_name = json.data.true_name;
                        that.user_tel = json.data.tel;

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
  //
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
            tips : function(){
                this.flag = true;
                this.name = this.true_name;
                this.tel  = this.user_tel;
            },
            editBtn : function(){
                var that = this;

                if( $('#name').val() == '' ){
                    mui.alert('真实姓名不能为空');
                }else if( $('#tel').val() == '' ){
                    mui.alert('手机号不能为空');
                }else if( !/^1[3|4|5|7|8][0-9]\d{4,8}$/.test($('#tel').val()) ){
                    mui.alert('手机号格式有误');
                }else{
                    //修改姓名 手机号
                    var signData;
                    if( !is_weixin ){    //游戏内 --> 签名处理
                        signData = {
                            gid : Request.gid,
                            r : 'daili/updata-daili-info',
                            logintime : loginTime,
                            PLAYER_INDEX : this.userData.user_info.user_id,
                            TRUE_NAME : $('#name').val(),
                            TEL  : $('#tel').val()
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
                            PLAYER_INDEX : this.userData.user_info.user_id,
                            TRUE_NAME : $('#name').val(),
                            TEL  : $('#tel').val()
                        };
                    }


                    jsonAjax( 'post', base_url + '&r=daili/updata-daili-info', signData, function(res){
                        var json = res;
                        if( json.ret_code == 0 ){
                            mui.alert('修改成功',function(){
                                that.flag = false;
                                that.getUserInfo();
                            });
                        }else{
                            mui.alert(json.ret_msg);
                        }
                    });
                }
            },
            goback : function(){
                this.flag = false;
            }
        }
    });
</script>
</body>
</html>