<!--机器人设置百人场-->
<style>
    .title1{text-align:center;line-height:60px;}
    .title2{line-height:40px;padding-left: 30px;}
    .title3{padding: 30px 20px;}
    .r input{border:none}
    .r tr th{width: 20%;}
    .r tr td{padding:0;}
    .subBtn{width:70px;margin:0 auto;line-height: 90px;}
</style>


<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">游戏系统设置</a>
        <a>
            <cite>百人场机器人设置</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>

</div>
<div class="x-body">


<!--<h1 class="title1">机器人设置</h1>-->
<h2 class="title2">机器人庄</h2>
<hr>
<div class="layui-row">
    <form action="" class="layui-form r">
        <div class="layui-col-sm2 title3">
            <h2 style="text-align: center">百人推筒子</h2>
        </div>
        <div class="layui-col-sm6">
            <table id="TTZ" lay-filter="TTZ" class="layui-table">
                <thead>
                <tr>
                    <th>庄机器人ID</th>
                    <th>庄机器人姓名</th>
                    <th>庄机器人头像</th>
                    <th>初始元宝数</th>
                    <th>闲家机器人元宝区间</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><input type="text" class="layui-input" name="ZRobotID" id="sys_player_id"></td>
                    <td><input type="text" class="layui-input" name="ZRobotName" id="sys_nickname"></td>
                    <td><input type="text" class="layui-input" name="ZRobotHead" id="sys_img_url"></td>
                    <td><input type="text" class="layui-input" name="initYB" id="sys_init_yuanbao"></td>
                    <td >
                        <div class="layui-row">
                            <div class="layui-col-sm5">
                                <input type="text" class="layui-input" name="minYB" id="sys_yuanbao_range_1">
                            </div>

                            <div class="layui-col-sm1" style="margin-top:8px;">
                                <span>~</span>
                            </div>
                            <div class="layui-col-sm6">
                                <input type="text" class="layui-input" name="maxYB" id="sys_yuanbao_range_2">
                            </div>
                        </div>
                    </td>
                    <input type="text" class="layui-input" name="sysId" id="sys_id" style="display: none;">

                </tr>
                </tbody>
            </table>
        </div>
        <div class="layui-col-sm2 ">
            <div class="subBtn">
                <div class="layui-btn " lay-submit="" lay-filter="submitTTZ">修改</div>
            </div>
        </div>
    </form>
</div>
    <br>
    <div class="layui-row">
        <form action="" class="layui-form r">
            <div class="layui-col-sm2 title3">
                <h2 style="text-align: center">百人牛牛</h2>
            </div>
            <div class="layui-col-sm6">
                <table id="NN" lay-filter="NN" class="layui-table">
                    <thead>
                    <tr>
                        <th>庄机器人ID</th>
                        <th>庄机器人姓名</th>
                        <th>庄机器人头像</th>
                        <th>初始元宝数</th>
                        <th>闲家机器人元宝区间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="text" class="layui-input" name="ZRobotID" id="nn_sys_player_id"></td>
                        <td><input type="text" class="layui-input" name="ZRobotName" id="nn_sys_nickname"></td>
                        <td><input type="text" class="layui-input" name="ZRobotHead" id="nn_sys_img_url"></td>
                        <td><input type="text" class="layui-input" name="initYB" id="nn_sys_init_yuanbao"></td>
                        <td >
                            <div class="layui-row">
                                <div class="layui-col-sm5">
                                    <input type="text" class="layui-input" name="minYB" id="nn_sys_yuanbao_range_1">
                                </div>

                                <div class="layui-col-sm1" style="margin-top:8px;">
                                    <span>~</span>
                                </div>
                                <div class="layui-col-sm6">
                                    <input type="text" class="layui-input" name="maxYB" id="nn_sys_yuanbao_range_2">
                                </div>
                            </div>
                        </td>
                        <input type="text" class="layui-input" name="sysId" id="nn_sys_id" style="display: none;">

                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="layui-col-sm2 ">
                <div class="subBtn">
                    <div class="layui-btn " lay-submit="" lay-filter="createSysRobot">修改</div>
                </div>
            </div>
        </form>
    </div>
    <br>
    <div class="layui-row">
        <form action="" class="layui-form r">
            <div class="layui-col-sm2 title3">
                <h2 style="text-align: center">龙虎斗</h2>
            </div>
            <div class="layui-col-sm6">
                <table id="LHD" lay-filter="LHD" class="layui-table">
                    <thead>
                    <tr>
                        <th>庄机器人ID</th>
                        <th>庄机器人姓名</th>
                        <th>庄机器人头像</th>
                        <th>初始元宝数</th>
                        <th>闲家机器人元宝区间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="text" class="layui-input" name="ZRobotID" id="lhd_sys_player_id"></td>
                        <td><input type="text" class="layui-input" name="ZRobotName" id="lhd_sys_nickname"></td>
                        <td><input type="text" class="layui-input" name="ZRobotHead" id="lhd_sys_img_url"></td>
                        <td><input type="text" class="layui-input" name="initYB" id="lhd_sys_init_yuanbao"></td>
                        <td >
                            <div class="layui-row">
                                <div class="layui-col-sm5">
                                    <input type="text" class="layui-input" name="minYB" id="lhd_sys_yuanbao_range_1">
                                </div>

                                <div class="layui-col-sm1" style="margin-top:8px;">
                                    <span>~</span>
                                </div>
                                <div class="layui-col-sm6">
                                    <input type="text" class="layui-input" name="maxYB" id="lhd_sys_yuanbao_range_2">
                                </div>
                            </div>
                        </td>
                        <input type="text" class="layui-input" name="sysId" id="lhd_sys_id" style="display: none;">

                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="layui-col-sm2 ">
                <div class="subBtn">
                    <div class="layui-btn " lay-submit="" lay-filter="createLhdSysRobot">修改</div>
                </div>
            </div>
        </form>
    </div>
    <br>
    <hr>
    <br>


<!--<div class="layui-row">-->
<!--    <form action="" class="layui-form">-->
<!--        <div class="layui-col-sm2 title3">-->
<!--            <h2 style="text-align: center">百人牛牛</h2>-->
<!--        </div>-->
<!--        <div class="layui-col-sm6">-->
<!--            <table id="NN" lay-filter="TTZ" class="layui-table r">-->
<!--                <thead>-->
<!--                <tr>-->
<!--                    <th>庄机器人ID</th>-->
<!--                    <th>庄机器人姓名</th>-->
<!--                    <th>庄机器人头像</th>-->
<!--                    <th>初始元宝数</th>-->
<!--                    <th>闲家机器人元宝区间</th>-->
<!--                </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                <tr>-->
<!--                    <td><input type="text" class="layui-input" name="ZRobotID"></td>-->
<!--                    <td><input type="text" class="layui-input" name="ZRobotName"></td>-->
<!--                    <td><input type="text" class="layui-input" name="ZRobotHead"></td>-->
<!--                    <td><input type="text" class="layui-input" name="initYB"></td>-->
<!--                    <td >-->
<!--                        <div class="layui-row">-->
<!--                            <div class="layui-col-sm5">-->
<!--                                <input type="text" class="layui-input" name="minYB" >-->
<!--                            </div>-->
<!---->
<!--                            <div class="layui-col-sm1" style="margin-top:8px;">-->
<!--                                <span>~</span>-->
<!--                            </div>-->
<!--                            <div class="layui-col-sm6">-->
<!--                                <input type="text" class="layui-input" name="maxYB" >-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                </tbody>-->
<!--            </table>-->
<!--        </div>-->
<!--        <div class="layui-col-sm2 ">-->
<!--            <div class="subBtn">-->
<!--                <button class="layui-btn " lay-submit="" lay-filter="submitNN">修改</button>-->
<!--            </div>-->
<!---->
<!--        </div>-->
<!--    </form>-->
<!--</div>-->
<div class="layui-row">
    <div class="layui-col-sm6">
        <h2 class="title2">机器人闲</h2>
    </div>
</div>
<hr>
    <div class="layui-row titleFormStyle">
        <button class="layui-btn" style="float: left;" data-type="createRobot" id="createRobot"><i class="layui-icon">&#xe61f;</i>机器人创建</button>
    </div>
<table id="RobotX" class="layui-table" lay-filter="RobotX"></table>
</div>
</body>

<script type="text/html" id="RobotXOption">
    <div class="layer">
        {{# if (d.state == 0) { }}
        <button class="layui-btn layui-btn-sm layui-btn start changeBtn"  lay-event="change">启用</button>
        <button class="layui-btn layui-btn-sm" lay-event="revise">修改</button>
        <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del">删除</button>
        {{# } else { }}
<!--        <button class="layui-btn layui-btn-sm layui-btn-warm stop changeBtn"  lay-event="change">暂停</button>-->
        {{# } }}

    </div>
</script>
<script>
    layui.use(['table','form','upload'],function () {
        var upload = layui.upload;
        var uploadInst = upload.render({
            elem: '#test1'
            ,url: '/game-set/hundreds-img-upload'
            ,done: function(res){
                //如果上传失败
                if(res.code == 0){
                    $('#robotHeadID').attr('style','height:84px;width:84px;');
                    $('#robotHeadID').attr('src',res.data);
                } else {
                    return layer.msg('上传失败');
                }
            }
        });

        //庄机器人上传头像
        function getSysImg(){
            upload.render({
                elem: '#test2'
                ,url: '/game-set/hundreds-img-upload'
                ,done: function(res){
                    //如果上传失败
                    if(res.code == 0){
                        $('#robotHeadIDSys').attr('style','height:84px;width:84px;');
                        $('#robotHeadIDSys').attr('src',res.data);
                    } else {
                        return layer.msg('上传失败');
                    }
                }
            });
        }
        getSysImg();
        //获取推筒子庄家机器人
        function getSys(){
            $.ajax({
                url:'/game-set/hundreds-robot-sys',
                type:'GET',
                data:{
                    gid:524821
                },
                success:function (res) {
                    console.log('ssss');
                    var sys_robot = res.data;
                    if (sys_robot[0]) {
                        if (sys_robot[0].yuanbao_range) {
                            sys_robot[0].yuanbao_range = (sys_robot[0].yuanbao_range).split(',');
                            $('#sys_yuanbao_range_1').val(sys_robot[0].yuanbao_range[0]);
                            $('#sys_yuanbao_range_2').val(sys_robot[0].yuanbao_range[1]);
                        }
                        $('#sys_id').val(sys_robot[0].id);
                        $('#sys_player_id').val(sys_robot[0].player_id);
                        $('#sys_nickname').val(sys_robot[0].nickname);
                        $('#sys_img_url').val(sys_robot[0].img_url);
                        $('#sys_init_yuanbao').val(sys_robot[0].init_yuanbao);
                    }
                }
            });
        }
        getSys();

        //获取牛牛庄
        function getNnSys(){
            $.ajax({
                url:'/game-set/hundreds-robot-sys',
                type:'GET',
                data:{
                    gid:524823
                },
                success:function (res) {
                    var sys_robot = res.data;
                    if (sys_robot[0]) {
                        if (sys_robot[0].yuanbao_range) {
                            sys_robot[0].yuanbao_range = (sys_robot[0].yuanbao_range).split(',');
                            $('#nn_sys_yuanbao_range_1').val(sys_robot[0].yuanbao_range[0]);
                            $('#nn_sys_yuanbao_range_2').val(sys_robot[0].yuanbao_range[1]);
                        }
                        $('#nn_sys_id').val(sys_robot[0].id);
                        $('#nn_sys_player_id').val(sys_robot[0].player_id);
                        $('#nn_sys_nickname').val(sys_robot[0].nickname);
                        $('#nn_sys_img_url').val(sys_robot[0].img_url);
                        $('#nn_sys_init_yuanbao').val(sys_robot[0].init_yuanbao);
                    }
                }
            });
        }
        getNnSys();

        //获取龙虎斗庄家机器人
        function getLhdSys(){
            $.ajax({
                url:'/game-set/hundreds-robot-sys',
                type:'GET',
                data:{
                    gid:524826
                },
                success:function (res) {
                    console.log('ssss');
                    var sys_robot = res.data;
                    if (sys_robot[0]) {
                        if (sys_robot[0].yuanbao_range) {
                            sys_robot[0].yuanbao_range = (sys_robot[0].yuanbao_range).split(',');
                            $('#lhd_sys_yuanbao_range_1').val(sys_robot[0].yuanbao_range[0]);
                            $('#lhd_sys_yuanbao_range_2').val(sys_robot[0].yuanbao_range[1]);
                        }
                        $('#lhd_sys_id').val(sys_robot[0].id);
                        $('#lhd_sys_player_id').val(sys_robot[0].player_id);
                        $('#lhd_sys_nickname').val(sys_robot[0].nickname);
                        $('#lhd_sys_img_url').val(sys_robot[0].img_url);
                        $('#lhd_sys_init_yuanbao').val(sys_robot[0].init_yuanbao);
                    }
                }
            });
        }
        getLhdSys();

        function robot(url,id=''){
            //var robotNum = $('#robotNum').val();
            var robotID = $('#robotID').val();
            var robotName = $('#robotName').val();
            var robotHeadID = $('#robotHeadID').attr('src');
            var robotIP = $('#robotIP').val();
            var gId = $('#gId').val();
            form.render('select');
            if (!robotName) {
                return layer.msg('请填写机器人名称',{time:1000});
            }
            $.ajax({
                url:url,
                type:'POST',
                data:{
                    "id":id,
                    "player_id":robotID,
                    "nickname":robotName,
                    "img_url":robotHeadID,
                    "ip":robotIP,
                    "gid":gId
                },
                success:function (res) {
                    if (res.code == 0) {
                        layer.msg('成功',{time:1000});
                        table.reload('RobotX', {
                            url:'/game-set/hundreds-robot-index',
                            where:{

                            },
                            page:true
                        });
                    } else {
                        layer.msg('失败',{time:1000});
                        return;
                    }

                },
                error:function (res) {
                    console.log('false');
                }
            })
        };

        var form = layui.form;
        var table = layui.table;

        //推筒子庄机器人
        form.on('submit(submitTTZ)',function (data) {
            console.log(data);
            layer.open({
                type:1
                ,title:"庄机器人"
                ,closeBtn:1
                ,area:['90%','90%']
                ,id:'LAY_layuipro'
                ,btn:['确认','取消']
                ,btnAlign:'c'
                ,moveType:1
                ,content:$('#sysRobot')
                ,success:function () {
                    $('#gIdSys').removeAttr('disabled');
                    $('#gIdSys').val(524821);
                    form.render('select');
                    $('#robotIDSys').val('');
                    $('#robotNameSys').val('');
                    $('#robotHeadIDSys').attr('src','');
                    $('#robotHeadIDSys').attr('style','height:0;width:0;');
                    $('#robotIPSys').val('');
                    if ($('#sys_id').val()) {//是否已存在
                        $('#gIdSys').attr('disabled','disabled');
                        $('#robotIDSys').val(data.field.ZRobotID);
                        $('#robotNameSys').val(data.field.ZRobotName);
                        $('#robotHeadIDSys').attr(data.field.ZRobotHead);
                        $('#robotHeadIDSys').attr('style','height:84px;width:84px;');
                        // $('#robotIPSys').val(data.field.robotIP);
                        $('#initYBSys').val(data.field.initYB);
                        $('#maxYBSys').val(data.field.maxYB);
                        $('#minYBSys').val(data.field.minYB);
                    }
                }
                ,yes:function (index,layero) {
                    if ($('#sys_id').val()) {//是否已存在
                        if (!$('#robotNameSys').val()) {
                            return layer.msg('请填写机器人名称',{time:1000});
                        }
                        $.ajax({
                            url:'/game-set/hundreds-robot-update',
                            type:'POST',
                            data:{
                                "id":$('#sys_id').val(),
                                "player_id":data.field.ZRobotID,
                                "nickname":$('#robotNameSys').val(),
                                "img_url":$('#robotHeadIDSys').attr('src'),
                                "init_yuanbao":$('#initYBSys').val(),
                                "yuanbao_range":$('#minYBSys').val()+','+$('#maxYBSys').val(),
                                "ip":$('#robotIPSys').val(),
                                "is_system":1,
                                "gid":524821
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    layer.msg('成功');
                                    return;
                                } else {
                                    layer.msg('失败');
                                    return ;
                                }
                            },
                            error:function () {
                                layer.msg('出现错误');
                                return;
                            }

                        })
                    } else {
                        if (!$('#robotNameSys').val()) {
                            return layer.msg('请填写机器人名称',{time:1000});
                        }
                        $.ajax({
                            url:'/game-set/hundreds-robot-create',
                            type:'POST',
                            data:{
                                "player_id":'',
                                "nickname":$('#robotNameSys').val(),
                                "img_url":$('#robotHeadIDSys').attr('src'),
                                "init_yuanbao":$('#initYBSys').val(),
                                "yuanbao_range":$('#sys_yuanbao_range_1').val()+','+$('#sys_yuanbao_range_2').val(),
                                "is_system":1,
                                "gid":524821
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    getSys();//创建成功刷新
                                    layer.msg('成功');
                                    return;
                                } else {
                                    layer.msg('失败');
                                    return ;
                                }
                            },
                            error:function () {
                                layer.msg('出现错误',{time:1000});
                                return;
                            }

                        })
                    }
                }
            });
        });

        //牛牛机器人
        form.on('submit(createSysRobot)',function (data) {
            console.log(data);
            layer.open({
                type:1
                ,title:"庄机器人"
                ,closeBtn:1
                ,area:['90%','90%']
                ,id:'LAY_layuipro'
                ,btn:['确认','取消']
                ,btnAlign:'c'
                ,moveType:1
                ,content:$('#sysRobot')
                ,success:function () {
                    $('#gIdSys').removeAttr('disabled');
                    $('#gIdSys').val(524823);
                    form.render('select');
                    $('#robotIDSys').val('');
                    $('#robotNameSys').val('');
                    $('#robotHeadIDSys').attr('src','');
                    $('#robotHeadIDSys').attr('style','height:0;width:0;');
                    $('#initYBSys').val('');
                    $('#maxYBSys').val('');
                    $('#minYBSys').val('');
                    $('#robotIPSys').val('');
                    if ($('#nn_sys_id').val()) {//是否已存在
                        $('#gIdSys').attr('disabled','disabled');
                        $('#robotIDSys').val(data.field.ZRobotID);
                        $('#robotNameSys').val(data.field.ZRobotName);
                        $('#robotHeadIDSys').attr(data.field.ZRobotHead);
                        $('#robotHeadIDSys').attr('style','height:84px;width:84px;');
                        // $('#robotIPSys').val(data.field.robotIP);
                        $('#initYBSys').val(data.field.initYB);
                        $('#maxYBSys').val(data.field.maxYB);
                        $('#minYBSys').val(data.field.minYB);
                    }
                }
                ,yes:function (index,layero) {
                    if ($('#nn_sys_id').val()) {//是否已存在
                        if (!$('#robotNameSys').val()) {
                            return layer.msg('请填写机器人名称',{time:1000});
                        }
                        $.ajax({
                            url:'/game-set/hundreds-robot-update',
                            type:'POST',
                            data:{
                                "id":$('#nn_sys_id').val(),
                                "player_id":data.field.ZRobotID,
                                "nickname":$('#robotNameSys').val(),
                                "img_url":$('#robotHeadIDSys').attr('src'),
                                "init_yuanbao":$('#initYBSys').val(),
                                "yuanbao_range":$('#minYBSys').val()+','+$('#maxYBSys').val(),
                                "ip":$('#robotIPSys').val(),
                                "is_system":1,
                                "gid":524823
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    layer.msg('成功');
                                    return;
                                } else {
                                    layer.msg('失败');
                                    return ;
                                }
                            },
                            error:function () {
                                layer.msg('出现错误');
                                return;
                            }

                        })
                    } else {
                        if (!$('#robotNameSys').val()) {
                            return layer.msg('请填写机器人名称',{time:1000});
                        }
                        $.ajax({
                            url:'/game-set/hundreds-robot-create',
                            type:'POST',
                            data:{
                                "player_id":'',
                                "nickname":$('#robotNameSys').val(),
                                "img_url":$('#robotHeadIDSys').attr('src'),
                                "init_yuanbao":$('#initYBSys').val(),
                                "yuanbao_range":$('#nn_sys_yuanbao_range_1').val()+','+$('#nn_sys_yuanbao_range_2').val(),
                                "is_system":1,
                                "gid":524823
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    getSys();//创建成功刷新
                                    layer.msg('成功');
                                    return;
                                } else {
                                    layer.msg('失败');
                                    return ;
                                }
                            },
                            error:function () {
                                layer.msg('出现错误',{time:1000});
                                return;
                            }

                        })
                    }
                }
            });
        });

        //龙虎斗
        form.on('submit(createLhdSysRobot)',function (data) {
            console.log(data);
            layer.open({
                type:1
                ,title:"庄机器人"
                ,closeBtn:1
                ,area:['90%','90%']
                ,id:'LAY_layuipro'
                ,btn:['确认','取消']
                ,btnAlign:'c'
                ,moveType:1
                ,content:$('#sysRobot')
                ,success:function () {
                    $('#gIdSys').removeAttr('disabled');
                    $('#gIdSys').val(524826);
                    form.render('select');
                    $('#robotIDSys').val('');
                    $('#robotNameSys').val('');
                    $('#robotHeadIDSys').attr('src','');
                    $('#robotHeadIDSys').attr('style','height:0;width:0;');
                    $('#initYBSys').val('');
                    $('#maxYBSys').val('');
                    $('#minYBSys').val('');
                    $('#robotIPSys').val('');
                    if ($('#lhd_sys_id').val()) {//是否已存在
                        $('#gIdSys').attr('disabled','disabled');
                        $('#robotIDSys').val(data.field.ZRobotID);
                        $('#robotNameSys').val(data.field.ZRobotName);
                        $('#robotHeadIDSys').attr(data.field.ZRobotHead);
                        $('#robotHeadIDSys').attr('style','height:84px;width:84px;');
                        // $('#robotIPSys').val(data.field.robotIP);
                        $('#initYBSys').val(data.field.initYB);
                        $('#maxYBSys').val(data.field.maxYB);
                        $('#minYBSys').val(data.field.minYB);
                    }
                }
                ,yes:function (index,layero) {
                    if ($('#lhd_sys_id').val()) {//是否已存在
                        if (!$('#robotNameSys').val()) {
                            return layer.msg('请填写机器人名称',{time:1000});
                        }
                        $.ajax({
                            url:'/game-set/hundreds-robot-update',
                            type:'POST',
                            data:{
                                "id":$('#lhd_sys_id').val(),
                                "player_id":data.field.ZRobotID,
                                "nickname":$('#robotNameSys').val(),
                                "img_url":$('#robotHeadIDSys').attr('src'),
                                "init_yuanbao":$('#initYBSys').val(),
                                "yuanbao_range":$('#minYBSys').val()+','+$('#maxYBSys').val(),
                                "ip":$('#robotIPSys').val(),
                                "is_system":1,
                                "gid":524826
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    layer.msg('成功');
                                    return;
                                } else {
                                    layer.msg('失败');
                                    return ;
                                }
                            },
                            error:function () {
                                layer.msg('出现错误');
                                return;
                            }

                        })
                    } else {
                        if (!$('#robotNameSys').val()) {
                            return layer.msg('请填写机器人名称',{time:1000});
                        }
                        $.ajax({
                            url:'/game-set/hundreds-robot-create',
                            type:'POST',
                            data:{
                                "player_id":'',
                                "nickname":$('#robotNameSys').val(),
                                "img_url":$('#robotHeadIDSys').attr('src'),
                                "init_yuanbao":$('#initYBSys').val(),
                                "yuanbao_range":$('#lhd_sys_yuanbao_range_1').val()+','+$('#lhd_sys_yuanbao_range_2').val(),
                                "is_system":1,
                                "gid":524826
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    getSys();//创建成功刷新
                                    layer.msg('成功');
                                    return;
                                } else {
                                    layer.msg('失败');
                                    return ;
                                }
                            },
                            error:function () {
                                layer.msg('出现错误',{time:1000});
                                return;
                            }

                        })
                    }
                }
            });
        });

        var active = {
            createRobot:function () {
                layer.open({
                    type:1
                    ,title:"创建机器人"
                    ,closeBtn:1
                    ,area:['90%','90%']
                    ,id:'LAY_layuipro'
                    ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#reviseRobot')
                    ,success:function () {
                        $('#gId').removeAttr('disabled');
                        $('#gId').val(524821);
                        form.render('select');
                        $('#robotID').val('');
                        $('#robotName').val('');
                        $('#robotHeadID').attr('src','');
                        $('#robotHeadID').attr('style','height:0;width:0;');
                        $('#robotIP').val('');
                    }
                    ,yes:function (index,layero) {
                        robot('/game-set/hundreds-robot-create/');
                        layer.close(index);
                    }
                });
            }
        };
        $('#createRobot').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        /*$('#createSysRobot').on('click', function(){
            layer.open({
                type:1
                ,title:"庄机器人"
                ,closeBtn:1
                ,area:['90%','90%']
                ,id:'LAY_layuipro'
                ,btn:['确认','取消']
                ,btnAlign:'c'
                ,moveType:1
                ,content:$('#sysRobot')
                ,success:function () {
                    $('#gId').removeAttr('disabled');
                    $('#gId').val(524821);
                    form.render('select');
                    $('#robotID').val('');
                    $('#robotName').val('');
                    $('#robotHeadID').attr('src','');
                    $('#robotHeadID').attr('style','height:0;width:0;');
                    $('#robotIP').val('');
                }
                ,yes:function (index,layero) {
                    robot('/game-set/hundreds-robot-create/');
                    layer.close(index);
                }
            });
        });*/
        table.render({
            elem:'#RobotX'
            ,url:'/game-set/hundreds-robot-index'
            ,page:true
            ,cols:[[
                {field:"id",title:"序号"}
                ,{field:"player_id",title:"机器人ID"}
                ,{field:"gid",title:"游戏",templet:function (d) {
                        if (d.gid == 524821) {
                            return '推筒子';
                        } else if (d.gid == 524823) {
                            return '牛牛';
                        }else if(d.gid == 524826){
                            return '龙虎斗';
                        }
                    }}
                ,{field:"nickname",title:"名称"}
                ,{field:"img_url",title:"头像ID"}
                ,{field:"ip",title:"机器人IP"}
                ,{field:"game_nums",title:"游戏场次"}
                ,{field:"win_nums",title:"赢场次"}
                ,{field:"lose_nums",title:"输场次"}
                ,{field:"win_percent",title:"输赢比例"}
                ,{field:"state",title:"状态",templet:function (d) {
                        if (d.state == 0) {
                            return '暂停';
                        } else if (d.state == 1) {
                            return '待上场';
                        } else if (d.state == 2) {
                            return '在场中';
                        } else if (d.state == -1) {
                            return '删除';
                        }
                    }}
                ,{field:"",title:"操作",toolbar:"#RobotXOption",width:250}
            ]]
        });


        table.on('tool(RobotX)', function(obj){
            var data = obj.data;
            switch(obj.event){
                case 'revise':
                    layer.open({
                        type:1
                        ,title:"修改机器人"
                        ,closeBtn:1
                        ,area:['50%','55%']
                        ,id:'LAY_layuipro'
                        ,btn:['确认','取消']
                        ,btnAlign:'c'
                        ,moveType:1
                        ,content:$('#reviseRobot')
                        ,success:function (layero,index) {
                            // $('#robotID').attr('readonly',true);
                             $('#gId').attr('disabled',true);
                             form.render('select');
                            $.ajax({
                                url:'/game-set/hundreds-robot-detail',
                                type:'GET',
                                data:{
                                    id:data.id
                                },
                                success:function (res) {
                                    var data_ = res.data;
                                    $('#robotID').val(data_.player_id);
                                    $('#robotName').val(data_.nickname);
                                    $('#robotHeadID').attr('src',data_.img_url);
                                    $('#robotHeadID').attr('style','width:84px;height:84px;');
                                    $('#robotIP').val(data_.ip);
                                    $('#gId').val(data_.gid);
                                    form.render('select');
                                }
                            })
                        }
                        ,yes:function (index,layero) {
                            robot('/game-set/hundreds-robot-update',data.id);
                            layer.close(index);
                        }
                    });

                    break;
                case 'change':
                    //获取点击按钮对于的tr数据的唯一值
                    var id = obj.data.number;
                    //获取点击的按钮
                    var $This = $(this);
                    //声明变量保存
                    var turn;


                    // 判断是开启还是暂停
                    $This.hasClass("stop")?turn = "start":turn = "stop";
                    //向后端发送数据并修改按钮状态
                    $.ajax({
                        type: 'POST'
                        , data: {
                            'player_id': data.player_id,
                            'instruc': 2,
                            'gid':data.gid
                        }
                        , url: '/game-set/hundreds-robot-state'
                        //,dataType:'JSON'
                        , success: function (data) {
                            if (turn === "start") {
                                $This.removeClass('stop');
                                $This.removeClass('layui-btn-warm');
                                $This.addClass('start');
                                $This.html('开启');
                            } else {
                                $This.removeClass('start');
                                $This.addClass('stop');
                                $This.addClass('layui-btn-warm');
                                $This.html('暂停');
                            }
                        }
                        ,error:function () {

                        }
                    });
                    break;
                case 'del':
                    layer.open({
                        type:1
                        ,title:false
                        ,closeBtn:1
                        ,area:['30%','25%']
                        ,id:'LAY_layuipro'
                        ,btn:['确认','取消']
                        ,btnAlign:'c'
                        ,moveType:1
                        ,content:$('#del')
                        ,success:function (layero,index) {
                            $('#num').html(data.player_id);
                        }
                        ,yes:function (index,layero) {
                            $.ajax({
                                url:'/game-set/hundreds-robot-del',
                                type:'POST',
                                data:{
                                    'id': data.id,
                                    'gid':data.gid
                                },
                                success:function (res) {
                                    if (res.code == 0) {
                                        layer.msg('成功',{time:1000});
                                        table.reload('RobotX', {
                                            url:'/game-set/hundreds-robot-index',
                                        });
                                    } else {
                                        layer.msg('失败',{time:1000});
                                    }

                                },
                                error:function () {
                                    console.log("失败");

                                }
                            });
                            layer.close(index);
                        }
                    });
                    break;
            }

        });
    })
</script>

<div class="x-body" id="reviseRobot" style="display: none;padding-left:20%;">
    <form action="" class="layui-form" >
        <!--<div class="layui-form-item">
            <label for="" class="layui-form-label">序号</label>
            <div class="layui-input-inline">
                <input id="robotNum" name="robotNum" type="text" class="layui-input" readonly >
            </div>
        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">游戏名称</label>
            <div class="layui-input-inline" style="margin-left: 0;" >
                <select name=""  id="gId" lay-filter="">
                    <option value="524821" selected>推筒子</option>
                    <option value="524823">牛牛</option>
                    <option value="524826">龙虎斗</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
<!--            <label for="" class="layui-form-label">机器人ID</label>-->
            <div class="layui-input-inline">
                <input id="robotID" name="robotID" type="text" class="layui-input" style="display: none;">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input id="robotName" name="robotName" type="text" class="layui-input" >
            </div>
        </div>
        <!--<div class="layui-form-item">
            <label for="" class="layui-form-label">头像</label>
            <div class="layui-input-inline">
                <input id="robotHeadID" name="robotHeadID" type="text" class="layui-input" >
            </div>
        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">头像ID</label>
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="test1">上传图片</button>
                <div class="layui-upload-list">
                    <label for="" class="layui-form-label"></label>
                    <img class="layui-upload-img" id="robotHeadID" name="robotHeadID">
                    <p id="demoText2"></p>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">机器人IP</label>
            <div class="layui-input-inline">
                <input id="robotIP" name="robotIP" type="text" class="layui-input" >
            </div>
        </div>
    </form>
</div>
<div class="x-body" id="del"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除编号为<span id="num"></span>的机器人吗？</h2>
</div>
<!--创建庄机器人-->
<div class="x-body" id="sysRobot" style="display: none;padding-left:20%;">
    <form action="" class="layui-form" >
        <!--<div class="layui-form-item">
            <label for="" class="layui-form-label">序号</label>
            <div class="layui-input-inline">
                <input id="robotNum" name="robotNum" type="text" class="layui-input" readonly >
            </div>
        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">游戏名称</label>
            <div class="layui-input-inline" style="margin-left: 0;" >
                <select name=""  id="gIdSys" lay-filter="">
                    <option value="524821" selected>推筒子</option>
                    <option value="524823">牛牛</option>
                    <option value="524826">龙虎斗</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <!--            <label for="" class="layui-form-label">机器人ID</label>-->
            <div class="layui-input-inline">
                <input id="robotIDSys" name="robotID" type="text" class="layui-input" style="display: none;">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input id="robotNameSys" name="robotName" type="text" class="layui-input" >
            </div>
        </div>
        <!--<div class="layui-form-item">
            <label for="" class="layui-form-label">头像</label>
            <div class="layui-input-inline">
                <input id="robotHeadID" name="robotHeadID" type="text" class="layui-input" >
            </div>
        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">头像ID</label>
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="test2">上传图片</button>
                <div class="layui-upload-list">
                    <label for="" class="layui-form-label"></label>
                    <img class="layui-upload-img" id="robotHeadIDSys" name="robotHeadID">
                    <p id="demoText2"></p>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">机器人IP</label>
            <div class="layui-input-inline">
                <input id="robotIPSys" name="robotIPSys" type="text" class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">初始元宝数</label>
            <div class="layui-input-inline">
                <input id="initYBSys" name="robotInitYB" type="text" class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">闲家机器人元宝区间</label>
                <div class="layui-row layui-input-inline">
                    <div class="layui-col-sm5" style="margin-right: 30px;">
                        <input type="text" class="layui-input"  name="minYB" id="minYBSys">
                    </div>
                    <div class="layui-col-sm5">
                        <input type="text" class="layui-input" name="maxYB" id="maxYBSys">
                    </div>
                </div>
            <!--<div class="layui-input-inline">
                <input id="robotIP" name="robotIP" type="text" class="layui-input" >
            </div>-->
        </div>
    </form>
</div>