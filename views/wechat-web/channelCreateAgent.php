<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>开通代理</title>
    <link rel="stylesheet" href="../static/lib/layui/css/layui.css">
    <script src="../static/lib/layui/layui.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
</head>
<style>
    .layui-table-cell{padding:0!important;}
    .notice{color:red;}
    .layui-form-label{width: 90px!important;}
    .layui-tab-brief>.layui-tab-title .layui-this{color:#3574E5;}
    .x-body{width:1155px!important;margin:0 auto;}
    .abtn{color:red;}
    #openLayer form{width:350px!important;margin:0 auto;}
    #openLayer .layui-form-label{width:80px!important;}
    #delLayer h2{text-align: center;}
    #delLayer{padding:20px 0;}
</style>
<body>
<div style="background-color: #F4900F;color:#fff;height:50px;width:100%;">
    <a href="index" style="color:#fff;"><i class="layui-icon layui-icon-return" style="float:left;position: relative;left:15px;top:17px;"></i></a>
    <h2 style="line-height: 50px;text-align: center;">开通代理</h2>
</div>
<iframe align="center" width="100%" height="200" src="channel-info"  frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe><div class="layui-container" style="margin-top:10px;" >
    <!--tab标签-->
    <div class="layui-tab layui-tab-card" lay-filter="tabs">
        <ul class="layui-tab-title" >
            <li class="layui-this" lay-id="withdrawCash">开通下级代理</li>
            <li lay-id="search">下级代理管理</li>
        </ul>
        <div class="layui-tab-content">
            <!--提现标签下的内容-->
            <div class="layui-tab-item layui-show">
                <div style="border:1px solid #E6E6E6">
                    <p >可开通代理个数：<span class="notice canCreate"></span>（已开通：<span class="notice alreadyCreate"></span>），增加下级代理数量请添加客服微信：<span class="notice">PPPK0317</span></p>
                </div>

                <table class="layui-table" id="createAgent" lay-filter="createAgent"></table>

            </div>
            <!--提现查询标签下的内容-->
            <div class="layui-tab-item ">
                <div style="border:1px solid #E6E6E6">
                    <p >可开通代理个数：<span class="notice canCreate">0</span>（已开通：<span class="notice alreadyCreate">0</span>），增加下级代理数量请添加客服微信：<span class="notice">PPPK0317</span></p>
                </div>

                <table class="layui-table" id="agentManage" lay-filter="agentManage"></table>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/html" id="openAgent">
    <a lay-event="open" class="abtn">开通</a>
</script>
<script type="text/html" id="agentManagetool">
    <a lay-event="del" class="abtn">移除</a>
</script>
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
            elem:"#createAgent"
            ,url:"/wechat-web/player-list"
            ,page:true
            ,cols:[[
                {field:"u_id",title:'我的玩家ID',width:80}
                ,{field:"weixin_nickname",title:'昵称'}
                ,{field:"income",title:'贡献收益',templet:function () {
                        return 0;
                    }}
                ,{field:"last_login_time",title:'登录时间'}
                //,{field:"operate",title:'操作'}
                ,{field:"operate",title:"操作",toolbar:"#openAgent",align:'center'}
            ]]
            ,done:function (data) {
                //$('.canCreate').html(data.count);
            }
        });
        table.render({
            elem:"#agentManage"
            ,url:"/wechat-web/member-daili-list"
            ,page:true
            ,cols:[[
                {field:"player_id",title:'代理ID',align:'center'}
                ,{field:"name",title:'姓名',align:'center'}
                ,{field:"all_pay_back_gold",title:'总收入',align:'center'}
                ,{field:"pay_back_gold",title:'未提现收入',width:80,align:'center'}
                ,{field:"",title:"操作",toolbar:"#agentManagetool",align:'center'}
            ]]
            ,done:function (data) {
                $('.alreadyCreate').html(data.count);
                $.ajax({
                    url:'/wechat/my-info',
                    success:function (res) {
                        res = eval('('+res+')');
                        if (res.code == 0) {
                            var data_ = res.data;
                            $('.canCreate').html(data_.open_num);
                        }
                    }
                });
            }
        });

        table.on('tool(createAgent)',function (obj) {
            var data = obj.data;
            console.log(obj.data);
            var layEvent = obj.event;
            if (layEvent == 'open') {
                $.ajax({
                    url:'/wechat-web/open-daili',
                    type:'POST',
                    data:{
                        user_id:data.u_id
                    },
                    success:function (res) {
                        res = eval('('+res+')');
                        if (res.code == 200) {
                            table.reload('createAgent',{
                                url:"/wechat-web/player-list"
                                ,page: {
                                    curr: 1 //重新从第 1 页开始
                                }
                            });
                            table.reload('agentManage',{
                                url:"/wechat-web/member-daili-list"
                                ,page: {
                                    curr: 1 //重新从第 1 页开始
                                }
                            });
                            return layer.msg('开通成功',{time:1000});
                        } else if (res.code == -3) {
                            return layer.msg('参数错误',{time:1000});
                        } else if (res.code == -51) {
                            return layer.msg('非下级玩家',{time:1000});
                        } else if (res.code == -62) {
                            return layer.msg('代理已存在',{time:1000});
                        } else if (res.code == -63) {
                            return layer.msg('已达开通上限',{time:1000});
                        } else {
                            return layer.msg('开通失败',{time:1000});
                        }
                    }
                });
                /*layer.open({
                    type:1
                    ,title:"开通代理"
                    ,closeBtn:1
                    ,anim:3
                    ,maxmin:true
                    ,area:['30%','30%']
                    ,id:'LAY_layuipro'
                    ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#openLayer')
                    ,success:function (layero,index) {

                    }
                    ,yes:function (index,layero) {

                    }
                });*/
            }
        })

        table.on('tool(agentManage)',function (obj) {
            var layEvent = obj.event;
            var data = obj.data;
            if (layEvent == 'del') {
                layer.confirm('确认移除代理?', function(index){
                    $.ajax({
                        url:'/wechat-web/del-daili',
                        type:'POST',
                        data:{
                            user_id:data.player_id
                        },
                        success:function (res) {
                            res = eval('('+res+')');
                            if (res.code == 200) {
                                table.reload('createAgent',{
                                    url:"/wechat-web/player-list"
                                    ,page: {
                                        curr: 1 //重新从第 1 页开始
                                    }
                                });
                                table.reload('agentManage',{
                                    url:"/wechat-web/member-daili-list"
                                    ,page: {
                                        curr: 1 //重新从第 1 页开始
                                    }
                                });
                                return layer.msg('删除成功',{time:1000});
                            } else if (res.code == -58) {
                                return layer.msg('没有代理关系',{time:1000});
                            } else if (res.code == -3) {
                                return layer.msg('参数错误',{time:1000});
                            } else if (res.code == -59 || res.code == -60) {
                                return layer.msg('下级代理已有返利，不能移除',{time:1000});
                            } else if (res.code == -65) {
                                return layer.msg('存在下级代理',{time:1000});
                            } else if (res.code == -1) {
                                return layer.msg('移除失败',{time:1000});
                            }
                        }
                    });

                    layer.close(index);
                });
                /*layer.open({
                    type:1
                    ,title:"取消代理"
                    ,closeBtn:1
                    ,anim:3
                    ,maxmin:true
                    ,area:['30%','30%']
                    ,id:'LAY_layuipro'
                    ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#delLayer')
                    ,success:function (layero,index) {

                    }
                    ,yes:function (index,layero) {

                    }
                });*/
            }
        })
    })
</script>

<div  style="display:none;" id="openLayer">
    <!--<h2>玩家</h2>-->
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">玩家ID</label>
            <div class="layui-input-inline">
                <div id="ID" class="notice"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">玩家昵称</label>
            <div class="layui-input-inline">
                <div id="nickname" class="notice"></div>
            </div>
        </div>
    </form>
</div>
<div  style="display:none;" id="delLayer">
    <h2>是否取消代理？</h2>
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">代理ID</label>
            <div class="layui-input-inline">
                <div id="ID2" class="notice"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">代理昵称</label>
            <div class="layui-input-inline">
                <div id="nickname2" class="notice"></div>
            </div>
        </div>
    </form>
</div>
</html>