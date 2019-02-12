<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>基本信息</title>
    <link rel="stylesheet" href="../static/lib/layui/css/layui.css">
    <script src="../static/lib/layui/layui.js"></script>
</head>
<style>
    .layui-table-cell{padding:0!important;}
    hr{margin:5px!important;}
    .correctionPosition{padding:9px 10px;}
</style>
<body>
<div style="background-color: #00CCFF;color:#fff;height:50px;width:100%;">
    <a href="index" style="color:#fff;"><i class="layui-icon layui-icon-return" style="float:left;position: relative;left:15px;top:17px;"></i></a>
    <h2 style="line-height: 50px;text-align: center;">基本信息</h2>
</div>
<div class="layui-container" style="margin-top:10px;" >
    <form action="" class="layui-form">
        <div>

            <label for="" class="layui-form-label">昵称</label>
            <div class="layui-input-inline" >
                <p id="myNick" class="correctionPosition"></p>
            </div>
        </div>
        <hr>
        <div>
            <label for="" class="layui-form-label">用户ID</label>
            <div class="layui-input-inline" >
                <p id="userID" class="correctionPosition"></p>
            </div>
        </div>
<!--        <hr>-->
<!--        <div>-->
<!--            <label for="" class="layui-form-label">返利比例</label>-->
<!--            <div class="layui-input-inline" >-->
<!--                <p id="rebate" class="correctionPosition">35%</p>-->
<!--            </div>-->
<!--        </div>-->
<!--        <hr>-->
<!--        <div>-->
<!--            <label for="" class="layui-form-label">上级代理</label>-->
<!--            <div class="layui-input-inline" >-->
<!--                <p id="supAgent" class="correctionPosition"></p>-->
<!--            </div>-->
<!--        </div>-->
        <hr>
        <div>
            <label for="" class="layui-form-label">下级玩家</label>
            <div class="layui-input-inline" >
                <p id="lowerPlayer" class="correctionPosition">0</p>
            </div>
        </div>
        <hr>
        <div>
            <label for="" class="layui-form-label">下级代理</label>
            <div class="layui-input-inline" >
                <p id="lowerAgent" class="correctionPosition">0</p>
            </div>
        </div>
        <hr>
        <div>
            <label for="" class="layui-form-label">手机号</label>
            <div class="layui-input-inline" >
                <p id="phone" class="correctionPosition"></p>
            </div>
            <div class="layui-btn layui-btn-xs layui-btn-warm" style="background-color: #00CCFF" data-method="bindingMobile" id="bindingMobile">绑定手机号</div>
        </div>
        <hr>
        <div>
            <label for="" class="layui-form-label ">真实姓名</label>
            <div class="layui-input-inline" >
                <p id="realName" class="correctionPosition"></p>
            </div>
            <div class="layui-btn layui-btn-xs layui-btn-warm" style="background-color: #00CCFF" data-method="changeRealName" id="changeRealName">修改</div>
        </div>
        <hr>
        <div>
            <label for="" class="layui-form-label">创建时间</label>
            <div class="layui-input-inline" >
                <p id="createTime" class="correctionPosition"></p>
            </div>
        </div>
        <hr>


    </form>
</div>
</body>
<script>
    layui.use(['table','form','laydate','layer'],function () {
        var laydate = layui.laydate;
        var table = layui.table;
        var form = layui.form;
        var layer = layui.layer;
        var $ = layui.$;
        laydate.render({elem:"#startTime"});
        laydate.render({elem:"#endTime"});

        function getBaseInfo(){
            $.ajax({
                url:'/wechat/my-base-info',
                success:function (res) {
                    res = eval('('+res+')');
                    var data = res.data;
                    $('#myNick').html(data.name);
                    $('#userID').html(data.player_id);
                    $('#supAgent').html(data.parent_index);
                    $('#lowerPlayer').html(data.player_count);
                    $('#lowerAgent').html(data.daili_count);
                    $('#phone').html(data.tel);
                    $('#realName').html(data.true_name);
                    $('#createTime').html(data.create_time);
                }
            });
        }
        getBaseInfo();

        table.render({
            elem:"#player"
            ,url:"/test/t206"
            ,page:true
            ,cols:[[
                {field:"playerID",title:'玩家ID'}
                ,{field:"playerNick",title:'玩家昵称'}
                ,{field:"player",title:'返利'}
                ,{field:"surplusYB",title:'剩余元宝'}
                ,{field:"lastLogin",title:'最后登录'}
            ]]
        });
        var active = {
            bindingMobile:function () {
                layer.open({
                    type: 1,
                    title: '您正在进行手机号的绑定',
                    area: ['80%', '35%'],
                    shade: 0.6,
                    content: $('#bindingMobilelayer'),
                    success: function (layero, index) {
                        // $('#getCodeBtn').on('click', function (){console.log('11111')});
                        //设置获取验证码倒计时
                        /*$('#getCodeBtn').on('click', function () {
                            var phoneNum = $("#phoneNum").val();
                            if (phoneNum!==''){
                                //向后端发送tel
                                $.ajax({
                                    url: '/test/t201/',
                                    type: "POST",
                                    data: {'phoneNum': phoneNum},
                                    success: function (data) {
                                        layer.msg("验证码发送成功！",{time:1000})
                                    },
                                    error: function (data) {
                                        layer.msg("验证码发送失败！",{time:1000})
                                    }
                                })
                            }else {
                                layer.msg("请输入手机号！",{time:1000})
                            }

                        })*/
                    }
                })
            },
            changeRealName:function(){
                layer.open({
                    type: 1,
                    title: '修改真实姓名',
                    area: ['80%', '35%'],
                    shade: 0.6,
                    content: $('#changeRealNamelayer'),
                    success: function (layero, index) {
                        $('#trueName').val($('#realName').html());
                    }
                })
            }
        };

        $('#bindingMobile').on('click', function(){
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });
        $('#changeRealName').on('click', function(){
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });
        form.on('submit(search1)',function (data) {
            var agentID = data.field.agentID;
            table.reload('player',{
                url:'/test/t204'
                ,page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    "agentID":agentID
                }
            })
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
                        getBaseInfo();
                        return layer.msg('绑定成功！');
                    }else{
                        return layer.msg('操作失败');
                    }
                }
            });
        });
        $('#changeNameAction').on('click',function () {
            if (!$('#trueName').val()) {
                return layer.msg('请填写真实姓名');
            }
            $.ajax({
                url:'/wechat/update-real-name',
                type:'POST',
                data:{
                    real_name:$('#trueName').val(),
                },
                success:function (res) {
                    res = eval('('+res+')');
                    if (res.code == 1) {
                        layer.closeAll();
                        getBaseInfo();
                        return layer.msg('修改成功');
                    } else {
                        return layer.msg('修改失败');
                    }

                }
            });
        });

    })
</script>
<body>
<!--绑定手机弹出层-->
<style>
    .layui-input-block{margin:0 10%!important;}
</style>
<div style="width:100%;margin:30px auto; display: none;" id="bindingMobilelayer">
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
<div style="width:100%;margin:30px auto; display: none;" id="changeRealNamelayer">
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-block" >
                <input type="text" class="layui-input" placeholder="请输入您的真实姓名" id="trueName">
            </div>
        </div>
        <div  class="layui-form-item">
            <div class="layui-input-block">
                <div class="layui-btn layui-btn-warm layui-btn-fluid" style="background-color: #00CCFF" id="changeNameAction">确认</div>
            </div>
        </div>
    </form>
</div>
</body>