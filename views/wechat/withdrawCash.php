<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>提现</title>
    <link rel="stylesheet" href="../static/lib/layui/css/layui.css">
    <script src="../static/lib/layui/layui.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
</head>
<style>
    .layui-input-block{margin:0 10%!important;}
    .notice{color:red;text-align: center;}
    .layui-form-label{width: 90px!important;}
    .layui-tab-brief>.layui-tab-title .layui-this{color:#3574E5;}
</style>
<body>
<div style="background-color: #00CCFF;color:#fff;height:50px;width:100%;">
    <a href="index" style="color:#fff;">
        <i class="layui-icon layui-icon-return" style="float:left;position: relative;left:15px;top:17px;"></i>
    </a>
    <h2 style="line-height: 50px;text-align: center;">提现</h2>
</div>
<iframe id="myInfo" align="center" width="100%" height="160" src="agentinfo"  frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
<div class="layui-container" style="margin-top:10px;" >
    <div class="layui-tab layui-tab-card" lay-filter="tabs">
        <ul class="layui-tab-title" style="text-align: center">
            <li class="layui-this" lay-id="withdrawCash">提现</li>
            <li lay-id="search" >记录</li>
        </ul>

        <div class="layui-tab-content">
            <!--提现标签下的内容-->
            <div class="layui-tab-item layui-show">
                <p class="notice">可提现时间为每日2:00至22:00</p>
                <hr>
                <form action="" class="layui-form">
                    <div class="layui-form-item">
                        <label for="" class="layui-form-label">可提现金额：</label>
                        <div class="layui-input-inline">
                            <p style="color:red;font-weight: 600;padding:9px;" id="pay_money">￥0.00</p>
                        </div>
                    </div>
                    <hr>
                    <label for="" class="layui-form-label ">提现金额：</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="text" class="layui-input " placeholder="0.00" name="cashAmount " id="cashAmount">
                    </div>
                    <div class="layui-btn" style="background-color: #00CCFF" id="getBackNum">全部</div>
                    <hr>
                    <label for="" class="layui-form-label">真实姓名：</label>
                    <div class="layui-input-inline" style="width: 150px;">
                        <input type="text" style="border: hidden" class="layui-input" placeholder="请填写真实姓名" name="realName" id="realName">
                    </div>
                    <hr>
                    <p class="notice" >注：请您务必正确填写真实姓名，否则提现会失败,如需更改，请移步至我的信息页面(首次更改可直接在此输入)</p>
                    <div class="layui-btn layui-btn-warm layui-btn-fluid" style="margin-top:10px;background-color: #00CCFF" id="getMyCash">提现</div>
                </form>


            </div>

            <!--记录标签下的内容-->
            <div class="layui-tab-item">
                <div class="layui-row" >
                    <form action="" class="layui-form">
                        <div class="layui-input-inline layui-col-xs4" style="margin:5px;">
                            <input type="text" class="layui-input" placeholder="开始时间" id="startTime" name="startTime">
                        </div>
                        <div class="layui-input-inline layui-col-xs4" style="margin:5px;">
                            <input type="text" class="layui-input" placeholder="结束时间" id="endTime" name="endTime">
                        </div>
                        <div class="layui-btn layui-btn-warm layui-col-xs3" style="margin-top:5px;background-color: #00CCFF" layui-submit="" lay-filter="search1" id="search1">查询</div>
                    </form>
                </div>
                <div class="main">
                    <table class="layui-table" id="player"lay-even lay-skin="line" lay-size="lg">
                        <caption>提现次数：<span id="payCount" style="color:red;">0</span>提现总额：<span id="payTotal" style="color:red;">0</span></caption>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="width:100%;display: none;" id="bindingMobilelayer">
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-block" >
                <input type="text" class="layui-input" placeholder="请输入您的手机号" id="phoneNum">
            </div>
        </div>
        <div  class="layui-form-item">
            <div class="layui-input-block" style="position: relative;">
                <input type="text" class="layui-input" id="verifyCode" placeholder="请输入验证码">
                <div style=" float:right;position: absolute;top:9px;right:5px;color:red;" id="getCodeBtn" >获取验证码</div>
            </div>
        </div>
        <div  class="layui-form-item">
            <div class="layui-input-block">
                <div class="layui-btn layui-btn-warm layui-btn-fluid" style="background-color: #00CCFF" id="bindPhone">绑定</div>
            </div>
        </div>
    </form>
</div>
</body>
<script>
 layui.use(['table','form','laydate','element'],function () {
     var element = layui.element;
     var laydate = layui.laydate;
     var table = layui.table;
     var form = layui.form;
     var $ = layui.$;

     //日期
     laydate.render({elem:"#startTime"});
     laydate.render({elem:"#endTime"});
    //表格数据渲染
     table.render({
         elem:"#player"
         ,url:"/wechat/get-take-money-order"
         ,page:true
         ,size: 'lg'
         ,cols:[[
             {field:"PAY_MONEY",title:'提现金额', align: 'center', width: '25%', templet:function(d){
                 return d.PAY_MONEY/100;
     }}
             ,{field:"PAY_STATUS",title:'提现状态', align: 'center', width: '25%', templet:function (d) {
                     if (d.PAY_STATUS == 0) {
                         return '处理中';
                     } else if (d.PAY_STATUS == 1) {
                         return '成功';
                     } else if (d.PAY_STATUS == 2) {
                         return '失败';
                     } else if (d.PAY_STATUS == 3) {
                         return '已解冻';
                     } else {
                         return '异常';
                     }
                 }}
             ,{field:"CREATE_TIME", align: 'center', title:'提现时间', width: '50%'}
         ]]
         ,done:function (res, curr, count) {
             //res = eval('('+res+')');
             $('#payCount').html(res.count);
             $('#payTotal').html(0);
             $.ajax({
                 url:"/wechat/get-take-money-time-order"
                 ,data:{
                    start_time:$('#startTime').val(),
                    end_time:$('#endTime').val()
                 }
                 ,success:function (res) {
                     res  = eval('('+res+')');
                     if (res.code == 200) {
                         $('#payTotal').html(res.data);
                     }
                 }
             });

         }
     });

     $('#getCodeBtn').on('click',function () {
         if (!$('#phoneNum').val()) {
             return layer.msg('请填写手机号');
         }
         $.ajax({
             url:'/wechat/get-phone-code',
             data:{
                 phone:$('#phoneNum').val()
             },
             success:function (res) {
                 res = eval('('+res+')');
                 if(res.code == 0){
                     return layer.msg('验证码已发送');
                 }else{
                     return layer.msg(res.msg);
                 }


             }
         });
     });

     function getMyInfo(){
         $.ajax({
             url:'/wechat/my-info',
             success:function (res) {
                 res = eval('('+res+')');
                 if (res.code == 0) {
                     var data = res.data;
                     $('#pay_money').html('￥'+data.pay_back_gold);
                     if(data.true_name){
                         $('#realName').val(data.true_name).attr('readonly','readonly');
                     }

                     $('#getBackNum').on('click',function () {
                         $.ajax({
                             url:'/wechat/my-info',
                             success:function (res) {
                                 res = eval('('+res+')');
                                 if (res.code == 0) {
                                     var data = res.data;
                                     $('#cashAmount').val(Math.floor(data.pay_back_gold));
                                 }
                             }
                         })

                     })
                 }
             }
         })
     }
     $("#realName").blur(function () {
         var isRead = document.getElementById("realName").readOnly;
         if(!isRead){
//             layer.msg('您已成功将您的真是姓名同步至您的个人信息。',{time:3000});
         }
     });
     getMyInfo();

     $('#getMyCash').on('click',function () {
         if ($('#cashAmount').val().length == 0) {
            return layer.msg('请填写提现金额',{time:1000});
         }
         if ($('#realName').val().length == 0) {
             return layer.msg('请填写真实姓名',{time:1000});
         }
         $.ajax({
             url:'/wechat/take-money',
             type:'POST',
             data:{
                 cash:$('#cashAmount').val(),
                 real_name:$('#realName').val()
             },
             success:function (res) {
                res = eval('('+res+')');
                 if (res.code == 1) {
                     table.reload('player',{
                         url:"/wechat/get-take-money-order"
                         ,page: {
                             curr: 1 //重新从第 1 页开始
                         }
                     });
                     getMyInfo();
                     //更新我的信息iframe
                     var _body = window.parent;
                     var _iframe1=_body.document.getElementById('myInfo');
                     _iframe1.contentWindow.location.reload(true);
                     return layer.msg('提现金额将在两小时到账',{time:1000});
                } else if (res.code == -1) {
                     return layer.msg('提现失败',{time:1000});
                 } else if (res.code == -64) {
                     return layer.msg('请先在我的信息填写真实姓名',{time:1000});
                 }else if(res.code == -70){
                     layer.open({
                         type: 1,
                         title: '您正在进行手机号的绑定',
                         area: ['80%', '35%'],
                         shade: 0.6,
                         content: $('#bindingMobilelayer'),
                         success: function (layero, index) {

                         }
                     })
                 } else {
                     return layer.msg(res.msg,{time:1000});
                 }

             }
         });
     });
     //查询按钮
     $('#search1').on('click',function () {
         table.reload('player',{
             url:"/wechat/get-take-money-order"
             ,page: {
                 curr: 1 //重新从第 1 页开始
             }
             ,where:{
                 start_time:$('#startTime').val(),
                 end_time:$('#endTime').val()
             }
         });
     });
     $('#bindPhone').on('click',function () {
         if (!$('#phoneNum').val()) {
             return layer.msg('请填写手机号');
         }
         if (!$('#verifyCode').val()) {
             return layer.msg('请填写验证码');
         }
         $.ajax({
             url:'/wechat/verify-code',
             type:'POST',
             data:{
                 phone:$('#phoneNum').val(),
                 code:$('#verifyCode').val()
             },
             success:function (res) {
                 console.log(res);
                 res = eval('('+res+')');
                 if (res.code == -56) {
                     return layer.msg('验证码错误');
                 } else if (res.code == -57) {
                     return layer.msg('验证码超时');
                 } else if (res.code == 1) {
                     layer.closeAll();
                     return layer.msg('绑定成功！');
                 }else{
                     return layer.msg('操作失败');
                 }
             }
         });
     });
 })
</script>
</html>