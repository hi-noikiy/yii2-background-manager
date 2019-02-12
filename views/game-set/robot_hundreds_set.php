<!--百人场推筒子-->
<body>
<style>
    .w{
        background-color: #e2e2e2;
    }
    #count{
        margin:0 auto;
        color:red;
    }
    #count p{
        display:inline-block;
        margin:0 50px;
        padding:5px 0;
    }
    .fz{
        font-size:25px;
    }
    .layui-row{
        margin:10px;
    }
    .layui-input-inline{
        margin:0 20px;
        width:130px;
    }
    .box{
        margin:30px;
    }
    .distance{ padding:9px 20px 9px 30px!important;}
    .merge .inputborder .layui-input-inline{
        margin: 0!important;
        width:75px!important;
    }
    .merge .inputborder input{
        border:0;

    }
    .inputborder{
        width:180px;
        border:1px solid #EEEEEE;
    }
    .labelWidth{width: 100px;}
    .perDiv{
        border: 1px solid #EEEEEE;height:38px;
    }
    .perInput{
        width: 90%;border: none;float: left;
    }
    .perSign{
        float: right;position: relative;top: 10px;right:3px;
    }
</style>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">游戏系统设置</a>
        <a>
            <cite>百人场设置</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>

</div>
<div class="x-body">
    <div class="layui-tab">
        <form class="layui-form">
            <div class="layui-form">
                <div class="layui-form-item">
                    <div class="layui-input-inline" style="margin-left: 0;" >
                        <select name=""  id="changeGame" lay-filter="changeGame">
                            <option value="524821" selected>推筒子</option>
                            <option value="524823">牛牛</option>
                            <option value="524826">龙虎斗</option>
                            <option value="524827">红黑大战</option>
                            <option value="524828">实时彩</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    <ul class="layui-tab-title">
        <li class="layui-this">数据查询</li>
        <li>配表设置</li>
    </ul>

    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form class="layui-form">
                <div class="layui-form">
                    <div class="layui-form-item">
                        <!--<div class="layui-input-inline">
                            <select name=""  id="gameName">
                                <option value="">游戏名称</option>
                                <option value="524821">推筒子</option>
                                <option value="524823">牛牛</option>
                            </select>
                        </div>-->
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" placeholder="开始日期" id="startDate">
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" placeholder="结束日期" id="endDate">
                        </div>
                        <div class="layui-btn" data-type="search" id="search">查询</div>
                    </div>
                </div>
            </form>
            <div style="height:140px;">
                <div class="layui-field-box">
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-body">
                                <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                                    <ul class="layui-row layui-col-space10 layui-this ">
                                        <li class="layui-col-md2">
                                            <div class="x-admin-backlog-body">
                                                <p><cite id="JCED">0</cite></p>
                                                <div>当前奖池额度</div>
                                            </div>
                                        </li>
                                        <li class="layui-col-sm2">
                                            <div class="x-admin-backlog-body">
                                                <p><cite id="LJSY">0</cite></p>
                                                <p>累计输赢</p>
                                            </div>
                                        </li>
                                        <li class="layui-col-sm2">
                                            <div class="x-admin-backlog-body">
                                                <p><cite id="YBK">0</cite></p>
                                                <div>元宝库</div>
                                            </div>
                                        </li>
                                        <li class="layui-col-sm2">
                                            <div class="x-admin-backlog-body">
                                                <p><cite id="JRSY">0</cite></p>
                                                <div>今日输赢</div>
                                            </div>
                                        </li>
                                        <li class="layui-col-md2">
                                            <div class="x-admin-backlog-body">
                                                <p><cite id="JRYH">0</cite></p>
                                                <div>今日用户</div>
                                            </div>
                                        </li>
                                        <li class="layui-col-sm2">
                                            <div class="x-admin-backlog-body">
                                                <p><cite id="JRWJXH">0</cite></p>
                                                <p>今日玩家消耗</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="container" style="height: 300px;width: 70%;"></div>
            <table class="layui-table" id="TTZData" lay-filter="TTZData"></table>

            <!--<div class="layui-row w">-->
                <!--<div class="layui-col-xs10 layui-col-xs-offset2">-->
                    <!--<div id="count" ><p>【统计】玩法：<span id="play"></span></p><span id="countdate"></span><p>玩家人数：<span id="playNum"></span></p><p>平台收益：<span id="platfromincome"></span>元宝</p></div>-->
                <!--</div>-->
            <!--</div>-->
        </div>
        <div class="layui-tab-item">
            <!--<form class="layui-form">
                <div class="layui-form">
                    <div class="layui-form-item">
                        <div class="layui-input-inline">
                            <select name=""  id="gameName1">
                                <option value="">游戏名称</option>
                                <option value="1">三张牌</option>
                                <option value="2">内蒙打大A</option>
                                <option value="3">山西麻将</option>
                                <option value="4">内蒙麻将</option>
                                <option value="5">牛牛</option>
                                <option value="6">跑得快</option>
                                <option value="7">推筒子</option>
                                <option value="8">三公</option>
                            </select>
                        </div>
                        <div class="layui-btn" data-type="search" id="search1">确定</div>
                    </div>
                </div>
            </form>-->

            <div style="height:140px;">
                <div class="layui-field-box">
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-body">
                                <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                                    <ul class="layui-row layui-col-space10 layui-this ">
                                        <li class="layui-col-sm3">
                                            <div class="x-admin-backlog-body">
                                                <p><cite id="">1</cite></p>
                                                <div>奖池ID</div>
                                            </div>
                                        </li>
                                        <li class="layui-col-sm3">
                                            <div class="x-admin-backlog-body">
                                                <p><cite id="totalGoldPoolFinal"></cite></p>
                                                <div>奖池初始额度</div>
                                            </div>
                                        </li>
                                        <li class="layui-col-sm3">
                                            <div class="x-admin-backlog-body">
                                                <p><cite id="totalGoldPool"></cite></p>
                                                <div>当前奖池额度</div>
                                            </div>
                                        </li>
                                        <li class="layui-col-sm3">
                                            <div class="x-admin-backlog-body">
                                                <p><cite id="recoveryPool"></cite></p>
                                                <div>库内收益元宝</div>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="layui-row">
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                    <label for="" class="layui-form-label labelWidth">奖池增减操作</label>
                                    <div class="layui-input-inline">
                                        <input type="text" class="layui-input" lay-verify="required|number" id="changeGoldPool">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs3">
                                <div class="layui-input-inline">
                                    <button class="layui-btn" lay-submit="changePool" lay-filter="changePool">保存</button>
                                </div>
                            </div>
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                    <label for="" class="layui-form-label labelWidth">踢出玩家操作</label>
                                    <div class="layui-input-inline">
                                        <input type="text" class="layui-input" lay-verify="required|number" placeholder="请填写玩家id" id="kickPlayer">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs3">
                                <div class="layui-input-inline">
                                    <button class="layui-btn" lay-submit="kick" lay-filter="kick">踢出</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fz">游戏设置</div>
            <hr>
            <div class="box">
                    <form action="" class="layui-form">

                        <div class="layui-form-item">
                            <label for="" class="layui-form-label labelWidth" >入库开关</label>
                            <div class="layui-input-block" style="padding-top: 6px;">
                                <input  type="checkbox" lay-skin="switch" lay-filter="winAscription" id="winAscription">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="" class="layui-form-label labelWidth">机器人开关</label>
                            <div class="layui-input-block" style="padding-top: 6px;">
                                <input  type="checkbox" lay-skin="switch" lay-filter="robotSwitch" id="robotSwitch">
                            </div>
                        </div>
                        <div class="layui-row">
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                    <label for="" class="layui-form-label labelWidth">入场元宝数</label>
                                    <div class="layui-input-inline">
                                        <input type="text" class="layui-input" lay-verify="required|number" id="enterTable">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                <label for="" class="layui-form-label labelWidth">离场元宝数</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" lay-verify="required|number" id="leaveTable">
                                </div>
                                </div>
                            </div>
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                    <label for="" class="layui-form-label labelWidth">上座元宝数</label>
                                    <div class="layui-input-inline">
                                        <input type="text" class="layui-input" lay-verify="required|number" id="zuoUp">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                    <label for="" class="layui-form-label labelWidth">下座元宝数</label>
                                    <div class="layui-input-inline">
                                        <input type="text" class="layui-input" lay-verify="required|number" id="zuoDown">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-row">
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                <label for="" class="layui-form-label labelWidth">上庄元宝</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" lay-verify="required|number" id="zhuangUp">
                                </div>
                                </div>
                            </div>
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                <label for="" class="layui-form-label labelWidth">下庄元宝</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" lay-verify="required|number" id="zhuangDown">
                                </div>
                                </div>
                            </div>
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                <label for="" class="layui-form-label labelWidth">连庄局数</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" lay-verify="required|number" id="continuZhuangNum">
                                </div>
                                </div>
                            </div>
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                    <label for="" class="layui-form-label labelWidth">台费比例</label>
                                    <div class="layui-input-inline">
                                        <input type="text" class="layui-input" lay-verify="required|number" id="serviceFee">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-row">
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                    <label for="" class="layui-form-label labelWidth">不触发换牌总额度</label>
                                    <div class="layui-input-inline">
                                        <input type="text" class="layui-input" lay-verify="required|number" id="minTouchChange">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-xs3">
                                <div class="layui-form-item">
                                    <label for="" class="layui-form-label labelWidth">单门触发换牌百分比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" class="layui-input" lay-verify="required|number" id="oneTouchChange">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="layui-form-item">
                            <label for="" class="layui-form-label labelWidth" >投注档位金额</label>
                            <div class="layui-input-inline" >
                                <input  type="type" class="layui-input" lay-verify="required|number" id="betNum1">
                            </div>
                            <div class="layui-input-inline" >
                                <input  type="type" class="layui-input" lay-verify="required|number" id="betNum2">
                            </div>
                            <div class="layui-input-inline" >
                                <input  type="type" class="layui-input" lay-verify="required|number" id="betNum3">
                            </div>
                            <div class="layui-input-inline" >
                                <input  type="type" class="layui-input" lay-verify="required|number" id="betNum4">
                            </div>
                            <div class="layui-input-inline" >
                                <input  type="type" class="layui-input" lay-verify="required|number" id="betNum5">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="" class="layui-form-label labelWidth" >玩家杀门数概率</label>
                            <div class="layui-input-inline" >
                                <input  type="type" class="layui-input" lay-verify="required|number" id="playerBlock1">
                            </div>
                            <div class="layui-input-inline" >
                                <input  type="type" class="layui-input" lay-verify="required|number" id="playerBlock2">
                            </div>
                            <div class="layui-input-inline" >
                                <input  type="type" class="layui-input" lay-verify="required|number" id="playerBlock3">
                            </div>
                            <div class="layui-input-inline" >
                                <input  type="type" class="layui-input" lay-verify="required|number" id="playerBlock4">
                            </div>
                            <div class="layui-input-inline" >
                                <input  type="type" class="layui-input" lay-verify="required|number" id="playerBlock5">
                            </div>
                        </div>
                        <!--<div class="layui-form-item">
                            <label for="" class="layui-form-label labelWidth" >警戒线</label>
                            <div class="layui-input-inline" >
                                <div class="perDiv">
                                    <input  type="type" class="layui-input perInput" lay-verify="required|number">
                                    <span class="perSign">%</span>
                                </div>

                            </div>
                            <div class="layui-input-inline" >
                                <div class="perDiv">
                                    <input  type="type" class="layui-input perInput" lay-verify="required|number">
                                    <span class="perSign">%</span>
                                </div>
                            </div>
                            <div class="layui-input-inline" >
                                <div class="perDiv">
                                    <input  type="type" class="layui-input perInput" lay-verify="required|number">
                                    <span class="perSign">%</span>
                                </div>
                            </div>
                            <div class="layui-input-inline" >
                                <div class="perDiv">
                                    <input  type="type" class="layui-input perInput" lay-verify="required|number">
                                    <span class="perSign">%</span>
                                </div>
                            </div>

                        </div>-->
                        <div class="layui-form-item" style="margin-top: 30px;position: relative;left: 10%;">
                            <div class="layui-input-block">
                                <div class="layui-btn" lay-submit lay-filter="saveSetting">保存</div>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
            <div id="xwcfbl">
                <div class="fz">行为触发比例</div>
                <hr>
                <div style="width: 607px;">
                    <table id="actionScale" class="layui-table" lay-filter="actionScale">
                    </table>
                </div>
            </div>
            <div class="fz">换牌概率</div>
            <hr>
            <div style="width: 607px;">
                <table id="changeBlock" class="layui-table" lay-filter="changeBlock">
                </table>
            </div>
        </div>
    </div>
    </div>
</div>
</body>
<script type="text/html" id="searchBtn">
    <button class="layui-btn layui-btn-sm" lay-event="search">查询</button>
</script>
<script>
    layui.use(['table','layer','laydate','form'],function () {
        var laydate = layui.laydate;
        var  $ = layui.$;
        var form = layui.form;
        laydate.render({
            elem:'#startDate'
        });
        laydate.render({
            elem:'#endDate'
        });
        var table = layui.table;
        //获取配表设置
        function getHundresSet(){
            $.ajax({
                url:'/game-set/hundreds-index',
                type:'GET',
                data:{
                    gid:$('#changeGame').val()
                },
                success:function (res) {
                    var data = res.data;
                    $('#totalGoldPool').html(data.totalGoldPool);
                    $('#totalGoldPoolFinal').html(data.totalGoldPoolFinal);
                    $('#recoveryPool').html(data.recoveryPool);
                    $('#winAscription').val(data.winAscription);
                    $('#robotSwitch').val(data.robotSwitch);
                    $('#enterTable').val(data.enterTable);
                    $('#leaveTable').val(data.leaveTable);
                    $('#zuoUp').val(data.zuoUp);
                    $('#zuoDown').val(data.zuoDown);
                    $('#zhuangUp').val(data.zhuangUp);
                    $('#zhuangDown').val(data.zhuangDown);
                    $('#continuZhuangNum').val(data.continuZhuangNum);
                    $('#serviceFee').val(data.serviceFee);
                    $('#minTouchChange').val(data.minTouchChange);
                    $('#oneTouchChange').val(data.oneTouchChange);
                    $('#betNum1').val(data.betNum1);
                    $('#betNum2').val(data.betNum2);
                    $('#betNum3').val(data.betNum3);
                    $('#betNum4').val(data.betNum4);
                    $('#betNum5').val(data.betNum5);
                    var playerBlock = data.playerBlockList.split(',');
                    for(var i = 0; i < playerBlock.length; i++) {
                        $('#playerBlock'+(i+1)).val(playerBlock[i]);
                    }
                    if ($('#winAscription').val() == 1) {
                        $('#winAscription').attr("checked", true);
                    }else{
                        $('#winAscription').attr("checked", false);
                    }
                    if ($('#robotSwitch').val() == 1) {
                        $('#robotSwitch').attr("checked", true);
                    }else{
                        $('#robotSwitch').attr("checked", false);
                    }
                    form.render();
                }

            })
        }
        getHundresSet();

        //数据查询当天数据
        function getToday(){
            $.ajax({
                url:'/game-set/hundreds-today',
                data:{
                    gid:$('#changeGame').val()
                },
                type:'GET',
                success:function (res) {
                    var data = res.data;
                    //var len = res.data[0].length;
                    /*if (len > 0) {
                    $('#JRWJXH').html(data[0][len-1].service_fee);//今日玩家消耗
                    //$('#JRYH').html(data[0][len-1].player_num);//今日用户
                    }*/
                    $('#JRSY').html(data[1].today_win);//今日输赢
                    $('#YBK').html(data[1].recoveryPool);//元宝库
                    $('#LJSY').html(data[1].totalGoldPool-data[1].totalGoldPoolFinal);//累计输赢
                    $('#JCED').html(data[1].totalGoldPool);//当前奖池额度
                }
            })

        }
        getToday();

        form.on('submit(changePool)',function () {
            var changeGoldPool = $('#changeGoldPool').val();
            if (!changeGoldPool) {
                return layer.msg('请输入值',{time:1000});
            }
            $.ajax({
                url:'/game-set/hundreds-index',
                type:'GET',
                data:{
                    gid:$('#changeGame').val()
                },
                success:function (res) {
                    if (res.code == 0) {
                        var data = res.data;
                        if (typeof data.changeGoldPool != "undefined") {
                            if (data.changeGoldPool != 0) {
                                return layer.msg('请等待上次操作生效',{time:1000});
                            }
                            if (changeGoldPool < 0) {
                                if (Math.abs(changeGoldPool) > data.totalGoldPool) {
                                    return layer.msg('不能超过奖池额度',{time:1000});
                                }
                            } else {
                                if (changeGoldPool > data.recoveryPool) {
                                    return layer.msg('不能超过回收池额度',{time:1000});
                                }
                            }
                        }
                    }
                    $.ajax({
                        url:'/game-set/hundreds-change-gold-pool',
                        type:'POST',
                        data:{
                            change:changeGoldPool,
                            gid:$('#changeGame').val()
                        },
                        success:function (res) {
                            if (res.code == 0) {
                                $('#changeGoldPool').val('');
                                return layer.msg('修改成功',{time:1000});
                            }
                        }
                    })
                }
            })
        });

        //踢出玩家操作
        form.on('submit(kick)',function () {
            var kickPlayer = $('#kickPlayer').val();
            if (!kickPlayer) {
                return layer.msg('请输入玩家ID',{time:1000});
            }
            $.ajax({
                url:'/game-set/hundreds-kick-player',
                type:'POST',
                data:{
                    player_id:kickPlayer,
                    gid:$('#changeGame').val()
                },
                success:function (res) {
                    res = eval('('+res+')');
                    if (res.code == 0) {
                        return layer.msg('踢出成功',{time:1000});
                    } else {
                        return layer.msg('踢出失败',{time:1000});
                    }
                }
            });
        });

        //游戏设置
        form.on('submit(saveSetting)',function () {
            var playerBlock = [];
            for(var i = 1; i <= 5; i++) {
                playerBlock.push($('#playerBlock'+i).val());
            }
            $.ajax({
                url:'/game-set/hundreds-set',
                type:'POST',
                data:{
                    robotSwitch:$('#robotSwitch').val(),
                    winAscription:$('#winAscription').val(),
                    enterTable:$('#enterTable').val(),
                    leaveTable:$('#leaveTable').val(),
                    zuoDown:$('#zuoDown').val(),
                    zuoUp:$('#zuoUp').val(),
                    zhuangUp:$('#zhuangUp').val(),
                    zhuangDown:$('#zhuangDown').val(),
                    continuZhuangNum:$('#continuZhuangNum').val(),
                    serviceFee:$('#serviceFee').val(),
                    oneTouchChange:$('#oneTouchChange').val(),
                    minTouchChange:$('#minTouchChange').val(),
                    betNum1:$('#betNum1').val(),
                    betNum2:$('#betNum2').val(),
                    betNum3:$('#betNum3').val(),
                    betNum4:$('#betNum4').val(),
                    betNum5:$('#betNum5').val(),
                    playerBlockList:playerBlock.join(','),
                    gid:$('#changeGame').val()
                },
                success:function(res){
                    if (res.code == 0) {
                        return layer.msg('修改成功',{time:1000});
                    } else {
                        return layer.msg('修改失败',{time:1000});
                    }
                }
            })
        });
        //监听开关
        form.on('switch(robotSwitch)',function (data) {
            if (data.elem.checked) {
                $('#robotSwitch').val(1);
            } else {
                $('#robotSwitch').val(0);
            }
        });

        form.on('switch(winAscription)',function (data) {
            if (data.elem.checked) {
                $('#winAscription').val(1);
            } else {
                $('#winAscription').val(2);
            }
        })
//数据查询每日统计
        function getDayStat(){
            var gid = $('#changeGame').val();
            if (gid == 524826) {
                table.render({
                    elem:'#TTZData'
                    ,url:'/game-set/hundreds-day-stat'
                    ,page:true
                    ,type:'GET'
                    ,where:{
                        gid:gid
                    }
                    ,cols:[[
                        {field:"date",title:"日期"}
                        ,{field:"game_count",title:"实际参与场次"}
                        ,{field:"gold_pool",title:"结算金额"}
                        ,{field:"income_gold",title:"元宝库收入"}
                        ,{field:"service_fee",title:"元宝消耗"}
                        ,{field:"player_num",title:"玩家人数",templet:function (d) {
                                if (new Date().setHours(0, 0, 0, 0) == new Date(d.date).getTime()) {
                                    $('#JRYH').html(d.player_num);
                                    $('#JRWJXH').html(d.service_fee);//今日玩家消耗
                                }
                                return d.player_num;
                            }}
                        ,{field:"total_lose",title:"人总输"}
                        ,{field:"total_win",title:"人总赢"}
                        ,{field:"",title:"操作",toolbar:"#searchBtn",width:110}
                    ]]
                });
            }else{
                table.render({
                    elem:'#TTZData'
                    ,url:'/game-set/hundreds-day-stat'
                    ,page:true
                    ,type:'GET'
                    ,where:{
                        gid:gid
                    }
                    ,cols:[[
                        {field:"date",title:"日期"}
                        ,{field:"game_count",title:"游戏场次"}
                        ,{field:"gold_pool",title:"结算金额"}
                        ,{field:"income_gold",title:"元宝库收入"}
                        ,{field:"service_fee",title:"元宝消耗"}
                        ,{field:"tian_men",title:"天门投注比"}
                        ,{field:"di_men",title:"地门投注比"}
                        ,{field:"shun_men",title:"顺门投注比"}
                        ,{field:"player_num",title:"玩家人数",templet:function (d) {
                                if (new Date().setHours(0, 0, 0, 0) == new Date(d.date).getTime()) {
                                    $('#JRYH').html(d.player_num);
                                    $('#JRWJXH').html(d.service_fee);//今日玩家消耗
                                }
                                return d.player_num;
                            }}
                        ,{field:"zhuang_count",title:"上庄人数"}
                        ,{field:"zhuang_num",title:"上庄次"}
                        ,{field:"total_lose",title:"人总输"}
                        ,{field:"total_win",title:"人总赢"}
                        ,{field:"",title:"操作",toolbar:"#searchBtn",width:110}
                    ]]
                });
            }
        }
        getDayStat();

        //数据查询中查询列表按钮
        $('#search').on('click',function () {
            table.reload('TTZData',{
                where:{
                    gid:$('#changeGame').val(),
                    start_time:$('#startDate').val(),
                    end_time:$('#endDate').val(),
                }
                ,page: {
                    curr: 1 //重新从第 1 页开始
                }
            })
        });

        table.on('tool(TTZData)',function (obj) {
            if (obj.event === 'search') {
                layer.open({
                    type:1
                    ,title:""
                    ,closeBtn:1
                    ,area:['90%','80%']
                    ,id:'LAY_layuipro'
                    // ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#searchTable')
                    ,success:function (layero,index) {
                        var gid = $('#changeGame').val();
                        if(gid == 524826){
                            table.render({
                                elem:'#DRZJ'
                                ,url:'/game-set/hundreds-stat'
                                ,where:{
                                    date:obj.data.date,
                                    gid:$('#changeGame').val()
                                }
                                ,page:true
                                ,cols:[[
                                    {field:"date",title:"日期"}
                                    ,{field:"win_2",title:"龙区",templet:function (d) {
                                            if (d.win_2 == 1) {
                                                return '赢';
                                            } else {
                                                return '输';
                                            }
                                        }
                                    }
                                    ,{field:"poker_str_2",title:"牌型"}
                                    ,{field:"win_num_2",title:"输赢额"}
                                    ,{field:"win_3",title:"虎区",templet:function (d) {
                                            if (d.win_3 == 1) {
                                                return '赢';
                                            } else {
                                                return '输';
                                            }
                                        }}
                                    ,{field:"poker_str_3",title:"牌型"}
                                    ,{field:"win_num_3",title:"输赢额"}
                                    ,{field:"win_4",title:"和区",templet:function (d) {
                                            if (d.win_4 == 1) {
                                                return '赢';
                                            } else {
                                                return '输';
                                            }
                                        }}
                                    ,{field:"win_num_4",title:"输赢额"}
                                    ,{field:"robot_change_gold",title:"机器人输赢"}
                                    ,{field:"service_fee",title:"消耗"}
                                ]]
                            });
                        }else{
                            table.render({
                                elem:'#DRZJ'
                                ,url:'/game-set/hundreds-stat'
                                ,where:{
                                    date:obj.data.date,
                                    gid:$('#changeGame').val()
                                }
                                ,page:true
                                ,cols:[[
                                    {field:"date",title:"日期"}
                                    ,{field:"player_id",title:"庄ID"}
                                    ,{field:"poker_str_1",title:"牌型"}
                                    ,{field:"take_gold",title:"所带元宝"}
                                    ,{field:"win_2",title:"顺门",templet:function (d) {
                                            if (d.win_2 == 1) {
                                                return '赢';
                                            } else {
                                                return '输';
                                            }
                                        }
                                    }
                                    ,{field:"poker_str_2",title:"牌型"}
                                    ,{field:"win_num_2",title:"输赢额"}
                                    ,{field:"win_3",title:"天门",templet:function (d) {
                                            if (d.win_3 == 1) {
                                                return '赢';
                                            } else {
                                                return '输';
                                            }
                                        }}
                                    ,{field:"poker_str_3",title:"牌型"}
                                    ,{field:"win_num_3",title:"输赢额"}
                                    ,{field:"win_4",title:"地门",templet:function (d) {
                                            if (d.win_4 == 1) {
                                                return '赢';
                                            } else {
                                                return '输';
                                            }
                                        }}
                                    ,{field:"poker_str_4",title:"牌型"}
                                    ,{field:"win_num_4",title:"输赢额"}
                                    ,{field:"robot_change_gold",title:"机器人输赢"}
                                    ,{field:"service_fee",title:"消耗"}
                                ]]
                            });
                        }
                    }
                });
            }
        })

//获取行为比例
        function getBehaviorPercent(){
            $('#xwcfbl').show();
            var gid = $('#changeGame').val();
            if (gid == 524821) {
                table.render({
                    elem:'#actionScale'
                    ,url:'/game-set/behavior-percent'
                    ,where:{
                        gid:gid
                    }
                    ,cols:[[
                        {field:"block",title:"奖池额度比",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType1",title:"随机概率",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType2",title:"通赔概率",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType3",title:"通杀",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType4",title:"杀一门概率",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType5",title:"杀两门概率",edit:"text",minWidth:100,align:"center"}
                    ]]
                });
            }else if(gid == 524826){
                //龙虎斗没有行为触发比例
                $('#xwcfbl').hide();
            }
            else {
                table.render({
                    elem:'#actionScale'
                    ,url:'/game-set/behavior-percent'
                    ,where:{
                        gid:gid
                    }
                    ,cols:[[
                        {field:"block",title:"奖池额度比",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType1",title:"随机概率",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType2",title:"通赔概率",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType3",title:"通杀",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType4",title:"杀一门概率",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType5",title:"杀两门概率",edit:"text",minWidth:100,align:"center"}
                        ,{field:"killType6",title:"杀三门概率",edit:"text",minWidth:100,align:"center"}
                    ]]

                });
            }

        }
        getBehaviorPercent();

        table.on('edit(actionScale)', function(obj){
            var data = obj.data;
            data.LAY_TABLE_INDEX = obj.tr[0].dataset.index;
            data['gid'] = $('#changeGame').val();
            $.ajax({
                url:'/game-set/behavior-percent-update',
                type:'POST',
                data:data,
                success:function (res) {
                    if (res.code == 0) {
                        return layer.msg('修改成功',{time:1000});
                    } else {
                        return layer.msg('修改失败',{time:1000});
                    }
                }
            });
        });

        //换牌概率
        function getChangePercent(){
            table.render({
                elem:'#changeBlock'
                ,url:'/game-set/change-percent'
                ,where:{
                    gid:$('#changeGame').val()
                }
                ,cols:[[
                    {field:"changeBlock",title:"奖池额度比",edit:"text",minWidth:100,align:"center"}
                    ,{field:"change",title:"换牌概率",edit:"text",minWidth:100,align:"center"}
                ]]

            });
        }
        getChangePercent();

        table.on('edit(changeBlock)', function(obj){
            var data = obj.data;
            data.LAY_TABLE_INDEX = obj.tr[0].dataset.index;
            data['gid'] = $('#changeGame').val();
            $.ajax({
                url:'/game-set/change-percent-update',
                type:'POST',
                data:data,
                success:function (res) {
                    if (res.code != 0) {
                        layer.msg('修改失败');
                        return ;
                    } else {
                        layer.msg('修改成功');
                        return ;
                    }
                }
            });
        });


        //查询
        var active = {

        };
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //切换游戏调取方法
        form.on('select(changeGame)', function(data){
            console.log(data.elem); //得到select原始DOM对象
            console.log(data.value); //得到被选中的值
            console.log(data.othis); //得到美化后的DOM对象
            getTodayData();
            getBehaviorPercent();
            getChangePercent();
            getDayStat();
            getToday();
            getHundresSet();
        });
    })
</script>
<script type="text/javascript" src="/js/echarts.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-gl/echarts-gl.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-stat/ecStat.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/dataTool.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/china.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/world.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ZUONbpqGBsYGXNIYHicvbAbM"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/bmap.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/simplex.js"></script>
<script type="text/javascript">
    var dom = document.getElementById("container");
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    app.title = '坐标轴刻度与标签对齐';

    //获取当天实时数据图
    function getTodayData(){
        var gid = $('#changeGame').val();
        $('#container').show();
        if (gid == 524821) {
            $.ajax({
                type: 'get',
                url: '/game-set/hundreds-cost-echarts',
                data:{
                    gid:$('#changeGame').val()
                },
                success: function(res) {
                    var data_x = [];
                    var data_y = [];

                    for (var i = 0;i<res.data[0].length;i++) {
                        if (typeof res.data[0][i].time == "undefined") {
                            data_x.push('');
                        } else {
                            data_x.push(res.data[0][i].time);
                        }
                        data_y.push(res.data[0][i].today_win)
                    }
                    option = {
                        color: ['#3398DB'],
                        tooltip : {
                            trigger: 'axis',
                            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis : [
                            {
                                type : 'category',
                                data : data_x,
                                axisTick: {
                                    alignWithLabel: true
                                }
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'',
                                type:'bar',
                                barWidth: '45%',
                                data:data_y
                            }
                        ]
                    };
                    myChart.setOption(option, true);
                }
            });
        }else{
            $('#container').hide();
        }
    }
    getTodayData();
</script>


<div style="display: none" id="searchTable">
    <table class="layui-table" id="DRZJ" ></table>
</div>