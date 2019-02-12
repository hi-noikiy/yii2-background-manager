<style>
    .title{width:180px;margin:0 auto;}
</style>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">游戏系统设置</a>
        <a>
            <cite>普通机器人设置</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>

</div>

<div class="x-body">
    <div class="list1">

        <form action="" class="layui-form titleFormStyle" >
            <div class="layui-form-item">
                <label for="" class="layui-form-label">机器人开关</label>
                <div class="layui-input-inline" style="padding-top: 5px;width:15%;" >
                    <input type="checkbox" class="layui-input" lay-skin="switch" lay-text="ON|OFF" lay-filter="robotSwitch" <?php if ($switch == 1) {echo 'checked';}?>>
                </div>

                <label for="" class="layui-form-label">炸金花开关</label>
                <div class="layui-input-inline" style="padding-top: 5px;width:15%;" >
                    <input type="checkbox" class="layui-input" lay-skin="switch" lay-text="ON|OFF" lay-filter="zjh_switch" <?php if ($zjh_switch == 1) {echo 'checked';}?>>
                </div>
                <label for="" class="layui-form-label">最大局数</label>
                <div class="layui-input-inline" style="padding-top: 5px;width:15%;" >
                    <input type="text" class="layui-input" name="max_jushu" id="max_jushu" <?php echo ' value = "'.$max_jushu.'"';?>>
                </div>
                <div class="layui-btn" id="edit_jushu" lay-submit="" lay-filter="edit_jushu">修改</div>
            </div>
        </form>
        <h2 class="title">机器人概率</h2>
        <table class="layui-table " lay-data="{url:'/game-set/robot-set?type=2'}" lay-filter="table1">
            <thead>
            <tr>
                <th lay-data="{field:'character', edit: 'text'}">机器人性格</th>
                <th lay-data="{field:'op_interval', edit: 'text'}">操作间隔</th>
                <th lay-data="{field:'nn_rob4', edit: 'text'}">(牛)抢庄4</th>
                <th lay-data="{field:'nn_rob3', edit: 'text'}">(牛)抢庄3</th>
                <th lay-data="{field:'nn_rob2', edit: 'text'}">(牛)抢庄2</th>
                <th lay-data="{field:'nn_rob1', edit: 'text'}">(牛)抢庄1</th>
                <th lay-data="{field:'nn_call4', edit: 'text'}">(牛)叫分4</th>
                <th lay-data="{field:'nn_call3', edit: 'text'}">(牛)叫分3</th>
                <th lay-data="{field:'nn_call2', edit: 'text'}">(牛)叫分2</th>
                <th lay-data="{field:'nn_call1', edit: 'text'}">(牛)叫分1</th>
                <th lay-data="{field:'ready', edit: 'text'}">等待时间</th>
                <th lay-data="{field:'leave', edit: 'text'}">观战离桌概率</th>
                <th lay-data="{field:'zjh_see', edit: 'text'}">(金)看牌率</th>
                <th lay-data="{field:'nn_open', edit: 'text'}">(牛)开牌率</th>
                <th lay-data="{field:'zjh_giveup', edit: 'text'}">(金)弃牌率</th>
                <th lay-data="{field:'zjh_heel', edit: 'text'}">(金)跟牌率</th>
                <th lay-data="{field:'zjh_fill', edit: 'text'}">(金)加注率</th>
                <th lay-data="{field:'zjh_compar', edit: 'text'}">(金)比牌率</th>
            </tr>
            </thead>
        </table>
    </div>
    <br>
    <hr/>
    <br>

    <div class="title2">

        <div class="layui-row titleFormStyle">
            <div style="float:left">
                <button class="layui-btn" data-type="create" id="create"><i class="layui-icon">&#xe61f;</i>新增机器人</button>
            </div>
        </div>
        <h2 class="title">机器人信息</h2>
        <table id="table2" class="layui-table" lay-data="{url:'/game-set/robot-info', page:true}" lay-filter="table2">
            <thead>
            <tr>
                <th lay-data="{field:'id', sort: true,edit: 'text'}">序号</th>
                <th lay-data="{field:'player_id', sort: true, edit: 'text'}">机器人ID</th>
                <th lay-data="{field:'nickname', edit: 'text'}">名称</th>
                <th lay-data="{field:'img_url', edit: 'text'}">头像ID</th>
                <th lay-data="{field:'ip', edit: 'text'}">机器人IP</th>
                <th lay-data="{field:'init_yuanbao', edit: 'text'}">初始元宝</th>
                <th lay-data="{field:'xiedai', edit: 'text'}">携带元宝</th>
                <th lay-data="{field:'dangqian', sort: true, edit: 'text'}">当前元宝</th>
                <th lay-data="{field:'recharge', edit: 'text'}">补充次数</th>
                <th lay-data="{field:'all_recharge', edit: 'text'}">补充总额</th>
                <th lay-data="{field:'win_yuanbao', edit: 'text'}">赢总额</th>
                <th lay-data="{field:'lose_yuanbao', edit: 'text'}">输总额</th>
                <th lay-data="{field:'game_count', edit: 'text'}">游戏场次</th>
                <th lay-data="{field:'win_count', sort: true, edit: 'text'}">赢场次</th>
                <th lay-data="{field:'lose_count', sort: true, edit: 'text'}">输场次</th>
                <th lay-data="{field:'win_lose', sort: true, edit: 'text'}">输赢比例</th>
                <th lay-data="{width:178, toolbar: '#barDemo'}">操作</th>
            </tr>
            </thead>
        </table>

        <script type="text/html" id="barDemo">
            <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
            {{#  if(d.status == 1){ }}
            <button class="layui-btn layui-btn-xs layui-btn-warm stop" lay-event="stop">暂停</button>
            {{#  } }}
            {{#  if(d.status == 0){ }}
            <button class="layui-btn layui-btn-xs layui-btn-normal" lay-event="stop">启用</button>
            {{#  } }}
        </script>
    </div>
</div>
<script>
    layui.use(['table','form', 'upload', 'layer'], function(){
        var table = layui.table;
        var form = layui.form;
        var upload = layui.upload;

        form.on('switch(robotSwitch)',function (data) {
            $.ajax({
                url:'/game-set/robot-set?type=1',
                data:{
                    value:data.elem.checked
                },
                success:function () {
                    layer.msg('状态修改成功', {time: 1000});
                }
            })
        })

        //扎金花开关
        form.on('switch(zjh_switch)',function (data) {
            $.ajax({
                url:'/game-set/robot-set?type=3',
                data:{
                    field:'zjh_switch',
                    value:data.elem.checked?1:0
                },
                success:function () {
                    layer.msg('状态修改成功', {time: 1000});
                }
            })
        })

        //最大局数
        form.on('submit(edit_jushu)',function (data) {
            $.ajax({
                url:'/game-set/robot-set?type=3',
                data:{
                    field:'max_jushu',
                    value:$('#max_jushu').val()
                },
                success:function () {
                    layer.msg('状态修改成功', {time: 1000});
                }
            })
        })

        table.on('edit(table1)', function(obj){
            var value = obj.value //得到修改后的值
                ,data = obj.data //得到所在行所有键值
                ,field = obj.field; //得到字段
            $.ajax({
                url:'/game-set/robot-set?type=3',
                data:{
                    field:obj.field,
                    value:obj.value
                },
                success:function () {
                    layer.msg('状态修改成功', {time: 1000});
                }
            })
//            layer.msg('[ID: '+ data.id +'] ' + field + ' 字段更改为：'+ value);
        });
        var active={
            //新增机器人
            create:function () {
                layer.open({
                    type:1
                    ,title:"新建"
                    ,closeBtn:1
                    ,area:['80%','65%']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#createlayer')
                    ,btn:['确认','取消']
                    ,success:function (layero,index) {
                        $('#reset-form').click();
                        $('#avatar').attr('src','');
                        $('#img-show').hide();
                        /*var player_id = 9;
                        for(var i = 1;i <= 8;i++) {
                            player_id += Math.floor(Math.random()*10)+'';
                        }
                        $("[name='randomNum']").attr('value',player_id);*/
                    }
                    ,yes:function (index,layero) {
                        var datas = $("#createlayer input");
                        $.ajax({
                            url:'/game-set/robot-add',
                            type:"POST",
                            data:{
                                //index:datas[0].value,
                                player_id:datas[0].value,
                                nickname:datas[1].value,
                                img_url:$('#avatar').attr('src'),
                                ip:datas[3].value,
                                dizhu:datas[4].value,
                                xiedai:datas[5].value,
                                init_yuanbao:datas[6].value
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    layer.msg('添加成功');
                                    layer.close(index);
                                    table.reload('table2',{
                                        url:'/game-set/robot-info',
                                        page:true,
                                        where:{}
                                    });
                                } else {
                                    layer.msg("添加失败");
                                }
                            },
                            error:function () {
                            }
                        })
                    }
                });
            }
        };
        $('#create').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        table.on('tool(table2)', function(obj){
            var data = obj.data;

            switch(obj.event){
                //详情按钮
                case 'edit':
                    var id = data.number;
                    $.ajax({
                        url:'/game-set/robot-detail',
                        data:{
                            id:data.id
                        },
                        success:function (res) {
                            data = res.data;
                            layer.open({
                                type:1
                                ,title:"修改"
                                ,closeBtn:1
                                ,area:['80%','65%']
                                ,id:'LAY_layuipro'
                                ,btn:['确认','取消']
                                ,btnAlign:'c'
                                ,moveType:1
                                ,content:$('#createlayer')
                                ,success:function (layero,index) {
                                    if (data) {
                                        $('#img-show').show();
                                        $('#avatar').attr('width','92px');
                                        $('#avatar').attr('height','92px');
                                        $('#avatar').attr('src',data.img_url);
                                        $("[name='randomNum']").val(data.player_id);
                                        $("[name='robotIP']").val(data.ip);
                                        $("[name='nickname']").val(data.nickname);
                                        $("[name='xiedai']").val(data.xiedai);
                                        $("[name='dizhu']").val(data.dizhu);
                                        $("[name='initYuanbao']").val(data.init_yuanbao);
                                    }
                                    $("[name='initYuanbao']").attr('disabled','disabled');
                                    /*var inputs = $("#createlayer input");
                                    var i=0;
                                    for (var  key in data){
                                        inputs[i].value = data[key];
                                        console.log(data[key])
                                        i++;
                                    }*/
                                }
                                ,yes:function(index, layero){
                                    $.ajax({
                                        url:'/game-set/robot-add',
                                        type:'post',
                                        data:{
                                            id:data.id,
                                            player_id:$("[name='randomNum']").val(),
                                            img_url:$('#avatar').attr('src'),
                                            ip:$("[name='robotIP']").val(),
                                            nickname:$("[name='nickname']").val(),
                                            xiedai:$("[name='xiedai']").val(),
                                            dizhu:$("[name='dizhu']").val(),
                                            //dangqian:$("[name='dangqian']").val()
                                        },
                                        success:function(res){
                                            if (res.code == 0) {
                                                table.reload('table2', {
                                                    url:'/game-set/robot-info',
                                                    page:true,
                                                    where:{}
                                                });
                                                layer.msg('修改成功',{time:1000});
                                                layer.close(index);

                                            } else {
                                                layer.msg('修改失败',{time:1000});
                                            }
                                        }

                                    })
                                }
                            });
                        }
                    })

                    break;
                //删除按钮
                case 'del':
                    var number = obj.data.player_id;
                    var id = obj.data.id;
                    layer.open({
                        type:1
                        ,title:false
                        ,closeBtn:1
                        ,area:['30%','25%']
                        ,id:'LAY_layuipro'
                        ,btn:['确认','取消']
                        ,btnAlign:'c'
                        ,moveType:1
                        ,content:$('#delGMAll')
                        ,success:function (layero,index) {
                            $('#num').html(number);
                        }
                        ,yes:function (index,layero) {
                            $.ajax({
                                url:'/game-set/robot-del',
                                type:'POST',
                                data:{
                                    'id': id
                                },
                                success:function (data) {
                                    table.reload('table2', {
                                        url:'/game-set/robot-info',
                                        page:true,
                                        where:{}
                                    });
                                    layer.close(index);
                                },
                                error:function () {
                                    layer.msg("删除失败");
                                }
                            });
                        }
                    });
                    break;
                case 'stop':
                    $.ajax({
                        url:'/game-set/robot-status',
                        type:'GET',
                        data:{
                            id:data.id,
                            status:data.status == 1?0:1
                        },
                        success:function(){
                            table.reload('table2', {
                                url:'/game-set/robot-info',
                                page:true,
                                where:{}
                            });
                        }
                    });
                    break;
            }
        });

//        上传文件
        var uploadInst = upload.render({
            elem: '#upload_header'
            ,url: '/game-set/robot-img-upload'
            ,done: function(res) {
                if (res.code == 0) {
                    $('#avatar').attr('width','92px');
                    $('#avatar').attr('height','92px');
                    $('#avatar').attr('src',res.data);
                    $('#img-show').show();
                } else {
                    $('#avatar').attr('src','');
                    $('#img-show').hide();
                    layer.msg('上传失败',{time:1000});
                }
                /*//上传成功
                $('#demo1').attr("src",res.data);*/
            }
        });
    });
</script>
</body>
<style>
    #createlayer th{width:150px;}
    #createlayer td{padding: 0;width:150px;}
    #createlayer td>input{border:0;padding:0;}
    #createlayer .layui-form-label{width:150px!important;}
    .layui-form-item .layui-input-inline{width:30%;}
</style>
<div class="layui-body" style="display:none;" id="createlayer">
    <form action="" class="layui-form">
        <!--<div class="layui-form-item">
            <label for="" class="layui-form-label">序号</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" readonly style="border:none" name="index" value="<?/*= $id; */?>">
            </div>
        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">随机账号</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="randomNum" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">昵称</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="nickname" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">用户头像</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="upload_header">
                    <i class="layui-icon">&#xe67c;</i>上传图片
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="demo1">
                        <p id="demoText"></p>
                    </div>
                </button>
            </div>
        </div>
        <div class="layui-form-item" id="img-show" style="display:none;" >
            <label for="" class="layui-form-label">头像显示</label>
            <div class="layui-input-inline">
                <img class="layui-upload-img" id="avatar" src ="" lay-verify="required"/>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">机器人IP</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="robotIP" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">机器人底注最高范围</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="dizhu" placeholder="2000" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">携带元宝数</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="xiedai" placeholder="2000" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">初始元宝</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="initYuanbao" placeholder="2000" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
<!--            <label for="" class="layui-form-label">当前元宝数</label>-->
            <div class="layui-input-inline">
<!--                <input type="text" class="layui-input" name="dangqian" placeholder="2000" lay-verify="required">-->
                <button type="reset" class="layui-btn layui-btn-primary" style="display: none;" id="reset-form">重置</button>
            </div>
        </div>
    </form>
</div>

<div class="x-body" id="delGMAll"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除编号为<span id="num"></span>的机器人吗？</h2>
</div>