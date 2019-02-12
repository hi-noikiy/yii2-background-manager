<style>
    .title{width:180px;margin:0 auto;}
    .rightBtn{width:20%;float:right;}
    .rightBtn button,.rightBtn a{float:right;}
</style>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">游戏系统设置</a>
        <a>
            <cite>机器人</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>

</div>
<div class="x-body">
<!--    <div class="list1">-->
<!--        <h2 class="title">机器人信息</h2>-->
<!--        <form action="" class="layui-form" >-->
<!--            <div class="layui-form-item">-->
<!--                <label for="" class="layui-form-label">机器人开关</label>-->
<!--                <div class="layui-input-block" style="padding-top: 5px;" >-->
<!--                    <input type="checkbox" class="layui-input" lay-skin="switch" lay-text="ON|OFF" lay-filter="robotSwitch">-->
<!--                </div>-->
<!--            </div>-->
<!--        </form>-->
<!--        <table class="layui-table " lay-data="{url:'/test/t206'}" lay-filter="table1">-->
<!--            <thead>-->
<!--            <tr>-->
<!--                <th lay-data="{type:'numbers', edit: 'text'}">机器人性格</th>-->
<!--                <th lay-data="{field:'username', edit: 'text'}">操作间隔</th>-->
<!--                <th lay-data="{field:'email', edit: 'text'}">抢庄4</th>-->
<!--                <th lay-data="{field:'sex', edit: 'text'}">抢庄3</th>-->
<!--                <th lay-data="{field:'city', edit: 'text'}">抢庄2</th>-->
<!--                <th lay-data="{field:'experience', edit: 'text'}">抢庄1</th>-->
<!--                <th lay-data="{field:'experience', edit: 'text'}">压分4</th>-->
<!--                <th lay-data="{field:'email', edit: 'text'}">压分3</th>-->
<!--                <th lay-data="{field:'sex', edit: 'text'}">压分2</th>-->
<!--                <th lay-data="{field:'city', edit: 'text'}">压分1</th>-->
<!--                <th lay-data="{field:'experience', edit: 'text'}">开牌率</th>-->
<!--                <th lay-data="{field:'experience', edit: 'text'}">看牌率</th>-->
<!--                <th lay-data="{field:'experience', edit: 'text'}">加注率</th>-->
<!--                <th lay-data="{field:'experience', edit: 'text', width:120}">比牌</th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--        </table>-->
<!--    </div>-->
    <div class="title2">
        <h2 class="title">机器人信息</h2>
        <div class="layui-row">
            <div class="layui-col-xs1 layui-col-xs-offset11 rightBtn">
                <a class="layui-btn" data-type="create" style="margin-left: 10px;" href="/game-set/general-robot-character-index">机器人性格</a>
                <button class="layui-btn" data-type="create"  id="create">新增机器人</button>
            </div>
        </div>
        <table class="layui-table" lay-data="{ url:'/game-set/general-robot-list', page:true}" id="table2" lay-filter="table2">
            <thead>
            <tr>
                <th lay-data="{type:'numbers',edit: 'text'}">序号</th>
                <th lay-data="{field:'uid', edit: 'text'}">机器人ID</th>
                <th lay-data="{field:'uid', edit: 'text',templet:function(d){
                    if (d.gameId == 524816) {
                        return '炸金花';
                    }
                    if (d.gameId == 524818) {
                        return '牛牛';
                    }
                }}">游戏</th>
                <th lay-data="{field:'name', edit: 'text'}">名称</th>
                <th lay-data="{field:'headImg', edit: 'text'}">头像ID</th>
                <th lay-data="{field:'ip', edit: 'text'}">机器人IP</th>
                <th lay-data="{field:'character_name', edit: 'text'}">性格</th>
                <th lay-data="{field:'borrowGold', edit: 'text'}">携带元宝</th>
                <th lay-data="{field:'now_coin', edit: 'text'}">当前元宝</th>
                <th lay-data="{field:'borrow_num', edit: 'text'}">借贷次数</th>
                <th lay-data="{field:'borrow_limit', edit: 'text'}">借贷额度</th>
                <th lay-data="{field:'game_num', edit: 'text'}">游戏场次</th>
                <th lay-data="{field:'win_num', edit: 'text'}">赢场次</th>
                <th lay-data="{field:'lose_num', edit: 'text'}">输场次</th>
                <th lay-data="{field:'win_percent', edit: 'text'}">输赢比例</th>
                <th lay-data="{width:178, toolbar: '#barDemo'}">操作</th>
            </tr>
            </thead>
        </table>

        <script type="text/html" id="barDemo">
            <a class="layui-btn layui-btn-xs" lay-event="log">记录</a>
            <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    </div>
</div>
<div style="display: none" id="searchTable">
    <table class="layui-table" id="DRZJ" ></table>
</div>
<script>
    layui.use(['table','form','upload'], function(){
        var table = layui.table;
        var form = layui.form;
        var $=layui.jquery,layer=layui.layer;
        var upload = layui.upload;
        form.verify({
            required:function (val,demo) {
                if (!val) {
                    console.log(demo);
                    return '缺少必填项';

                }
            }
        })
        form.on('switch(robotSwitch)',function (data) {
            $.ajax({
                url:'/test/t206/',
                data:{
                    value:data.elem.checked
                },
                success:function () {
                    console.log(data.elem.checked)
                }
            })
        })
        table.on('edit(table1)', function(obj){
            var value = obj.value //得到修改后的值
                ,data = obj.data //得到所在行所有键值
                ,field = obj.field; //得到字段
            layer.msg('[ID: '+ data.id +'] ' + field + ' 字段更改为：'+ value);
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
                        //获取性格列表
                        $('#reset').click();
                        $("#headerID").attr('src','');
                        $("#headerID").attr('style','');
                        $("#gameIdDiv").show();
                        $.ajax({
                            url:'general-robot-character-list',
                            type:'GET',
                            success:function (res) {
                                var character = res.data;
                                $("[name='robotCharacter']").html('');

                                for (var i=0;i<character.length;i++) {
                                    $("[name='robotCharacter']").append('<option value="'+character[i].id+'">'+character[i].commont+'</option>') ;
                                }
                                form.render('select');
                            }
                        })

                    }
                    ,yes:function (index,layero) {
                        var datas = $("#createlayer input");
                        if ($("#gameId").val().length == 0) {
                            return layer.msg('请选择游戏id',{time:1000});
                        }
                        if ($("[name='nickName']").val().length == 0) {
                            return layer.msg('请填写用户名',{time:1000});
                        }
                        if ($("[name='longitude']").val().length == 0 || $("[name='latitude']").val().length == 0) {
                            return layer.msg('请填写经纬度',{time:1000});
                        }
                        $.ajax({
                            url:'/game-set/general-robot-create',
                            type:"POST",
                            data:{
                                nickname:$("[name='nickName']").val(),
                                img_url:$("#headerID").attr('src'),
                                ip:$("[name='robotIP']").val(),
                                latitude:$("[name='latitude']").val(),
                                longitude:$("[name='longitude']").val(),
                                character_id:$("[name='robotCharacter']").val(),
                                bet:$("[name='bet']").val(),
                                take_coin:$("#take_coin").val(),
                                gid:$('#gameId').val()
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    layer.close(index);

                                    table.reload('table2',{
                                        url:'/game-set/general-robot-list',
                                        where:{
                                        }
                                    });
                                } else {
                                    return layer.msg('失败');
                                }

                            },
                            error:function () {
                                console.log("false")
                            }
                        })
                    }
                });
            },
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

                            $('#reset').click();
                            $("#headerID").attr('src','');
                            $("#headerID").attr('style','');
                            $('#gameIdDiv').attr('style','display:none;');
                            $.ajax({
                                url:'general-robot-character-list',
                                type:'GET',
                                success:function (res) {
                                    var character = res.data;
                                    $("[name='robotCharacter']").html('');
                                    for (var i=0;i<character.length;i++) {
                                        if (character[i].id == data.characterId) {
                                            $("[name='robotCharacter']").append('<option value="'+character[i].id+'" selected>'+character[i].commont+'</option>') ;
                                        } else {
                                            $("[name='robotCharacter']").append('<option value="'+character[i].id+'">'+character[i].commont+'</option>') ;
                                        }
                                    }
                                    form.render('select');
                                }
                            })
                            $('#headerID').attr('style','height:84px;width:84px;');
                            $("[name='nickName']").val(data.name);
                            $("#headerID").attr('src',data.headImg);
                            $("[name='robotIP']").val(data.ip);
                            $("[name='latitude']").val(data.latitude),
                            $("[name='longitude']").val(data.longitude),
                            //$("[name='robotCharacter']").val(data.character_id);
                            $("[name='bet']").val(data.bet);
                            $("#take_coin").val(data.borrowGold);
                            $("#now_coin").val(data.now_coin);
                            $("#borrow_num").val(data.borrow_num);
                            $("#borrow_limit").val(data.borrow_limit);
                            
                            $("#game_num").val(data.game_num);
                            $("#win_num").val(data.win_num);
                        }
                        ,yes:function (index,layero) {
                            if ($("[name='nickName']").val().length == 0) {
                                return layer.msg('请填写用户名',{time:1000});
                            }
                            if ($("[name='longitude']").val().length == 0 || $("[name='latitude']").val().length == 0) {
                                return layer.msg('请填写经纬度',{time:1000});
                            }
                            $.ajax({
                                url:'/game-set/general-robot-create',
                                type:"POST",
                                data:{
                                    id:data.id,
                                    machine_code:data.machineCode,
                                    nickname:$("[name='nickName']").val(),
                                    img_url:$("#headerID").attr('src'),
                                    ip:$("[name='robotIP']").val(),
                                    latitude:$("[name='latitude']").val(),
                                    longitude:$("[name='longitude']").val(),
                                    character_id:$("[name='robotCharacter']").val(),
                                    bet:$("[name='bet']").val(),
                                    take_coin:$("#take_coin").val(),
                                    gid:data.gid
                                },
                                success:function (res) {
                                    if (res.code == 0) {
                                        layer.close(index);
                                        table.reload('table2',{
                                            url:'/game-set/general-robot-list',
                                            where:{
                                            }
                                        });
                                    } else {
                                        return layer.msg('失败');
                                    }

                                },
                                error:function () {
                                    console.log("false")
                                }
                            })
                        }
                    });
                    break;
                //删除按钮
                case 'del':
                    var data = obj.data;
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
                            //$('#num').html(dataid);
                        }
                        ,yes:function (index,layero) {
                            $.ajax({
                                url:'/game-set/general-robot-del',
                                type:'POST',
                                data:{
                                    machine_code:data.machineCode,
                                },
                                success:function (res) {
                                    if (res.code == 0) {
                                        table.reload('table2',{
                                            url:'/game-set/general-robot-list',
                                            where:{
                                            }
                                        });
                                    } else {
                                        layer.msg('失败');
                                        return;
                                    }
                                    layer.close(index);
                                },
                                error:function () {
                                    console.log("失败");
                                }
                            });
                        }
                    });
                    break;
                case 'log':
                    layer.open({
                        type:1
                        ,title:""
                        ,closeBtn:1
                        ,area:['80%','65%']
                        ,id:'LAY_layuipro'
                        // ,btn:['确认','取消']
                        ,btnAlign:'c'
                        ,moveType:1
                        ,content:$('#searchTable')
                        ,success:function (layero,index) {
                            if (obj.data.uid == 0) {
                                return layer.msg('机器人尚未被使用',{time:1000});
                            }

                            table.render({
                                elem:'#DRZJ'
                                ,url:'/game-set/signal-general-robot-day-stat'
                                ,where:{
                                    id:obj.data.uid
                                }
                                ,page:true
                                ,cols:[[
                                    {field:"date",title:"日期"}
                                    ,{field:"nickname",title:"机器人名称"}
                                    ,{field:"player_id",title:"机器人ID"}
                                    ,{field:"init_gold",title:"当日初始元宝"}
                                    ,{field:"final_gold",title:"当日结算元宝"}
                                    ,{field:"borrow_count",title:"借贷次数"}
                                    ,{field:"borrow_limit",title:"借贷额度"}
                                    ,{field:"game_count",title:"游戏场次"}
                                    ,{field:"win_count",title:"赢场次"}
                                    ,{field:"lose_count",title:"输场次"}
                                    ,{field:"win_num",title:"输赢额度"}
                                ]]
                            });
                        }
                    });

                    break;
            }
        });


        //头像图片上传
        var uploadInst = upload.render({
            elem: '#test1'
            ,url: '/game-set/robot-img-upload'
            ,before: function(obj){
                /*//预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#demo1').attr('src', result); //图片链接（base64）
                });*/
            }
            ,done: function(res){
                //如果上传失败
                if(res.code != 0){
                    return layer.msg('上传失败');
                }
                //上传成功
                $('#headerID').attr('style','width:84px;height:84px;');
                $('#headerID').attr("src",res.data);
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
            ,accept:'images'
            ,size:10240
        });

        //监听性格选择框
        form.on('select(robotCharacter)', function(data){
            $("[name='robotCharacter']").val(data.value);
            console.log(data.elem);
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
<div class="layui-body" style="display:none;" id="createlayer" class="layui-form">
    <form action="" class="layui-form">
        <!--<div class="layui-form-item">
            <label for="" class="layui-form-label">序号</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" readonly style="border:none" name="index">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">随机号</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" readonly style="border:none" name="randomNum">
            </div>
        </div>-->
        <div class="layui-form-item" id="gameIdDiv">
            <label for="" class="layui-form-label">游戏id</label>
            <div class="layui-input-inline">
                <select id="gameId" lay-verify="my_gameId">
                    <option value="">请选择游戏</option>
                    <option value="524816">炸金花</option>
                    <option value="524818">牛牛</option>
                    <option value="524822">斗地主</option>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">用户名</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="nickName" required  lay-verify="required">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">选择图片</label>
            <div class="layui-input-inline" >
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="headerID" required  lay-verify="required">
                        <p id="demoText"></p>
                    </div>
                </div>
            </div>
        </div>

        <!--<div class="layui-form-item">
            <label for="" class="layui-form-label">用户头像ID</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="headerID">
            </div>
        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">机器人IP</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="robotIP" required  lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">经度</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="longitude" required  lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">纬度</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="latitude" required  lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">机器人性格</label>
            <div class="layui-input-inline">
                <select name="robotCharacter" lay-filter="robotCharacter">
                </select>
            </div>
        </div>
        <!--<div class="layui-form-item">
            <label for="" class="layui-form-label">机器人底注最高范围</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="bet" placeholder="2000" required  lay-verify="required">
            </div>
        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">机器人随机属性</label>
            <div class="layui-input-inline" style="width: 80%;">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>携带元宝</th>
                        <th>当前元宝</th>
                        <th>借贷次数</th>
                        <th>借贷额度</th>
                        <th>游戏场次</th>
                        <th>赢场次</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="text" class="layui-input" id="take_coin" required  lay-verify="required"></td>
                        <td><input type="text" class="layui-input" id="now_coin" readonly></td>
                        <td><input type="text" class="layui-input" id="borrow_num" readonly></td>
                        <td><input type="text" class="layui-input" id="borrow_limit" readonly></td>
                        <td><input type="text" class="layui-input" id="game_num" readonly></td>
                        <td><input type="text" class="layui-input" id="win_num" readonly></td>
                    </tr>
                    </tbody>

                </table>
            </div>
        </div>
        <button type="reset" id="reset" class="layui-btn layui-btn-primary" style="display: none;">重置</button>
    </form>
</div>

<div class="x-body" id="delGMAll"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除编号为<span id="num"></span>的机器人吗？</h2>
</div>