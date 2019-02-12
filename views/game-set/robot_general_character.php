
<style>
    .layui-table .layui-table-cell{
        height:auto;
        overflow:visible;
        text-overflow:inherit;
        white-space:normal;
    }
    .c{text-align: center!important;}
    .box{display: flex;justify-content: space-between;}
    /*.tableDiv{float: left;}*/
    .characterTable td{height:39px;box-sizing: border-box;text-align: center;}
    .characterTable td{width:85px!important;}
    .character{font-size:18px;font-weight: bold;width:100px!important;}
    .tableBtn{line-height: 413px;}
    h2{line-height: 60px;}
    .title{text-align: center;color:#666666;}
</style>
<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="/game-set/mengxin">游戏系统设置</a>
        <a>
            <cite>机器人性格</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body" >
    <div class="titleFormStyle">
        <!--<div class=" layui-col-xs2">-->
            <form action="" class="layui-form " style="float:left;">
                <div class="layui-input-inline">
                    <label class="layui-form-label">机器人开关</label>
                    <div class="layui-input-block" style="padding:6px 0;">
                        <input id="switch1" type="checkbox" lay-skin="switch" lay-text="ON|OFF" lay-filter="switch" <?php if($switch == 'true') {echo 'checked';}?>>
                    </div>
                </div>
                <div class="layui-input-inline">
                    <label class="layui-form-label">炸金花开关</label>
                    <div class="layui-input-block" style="padding:6px 0;">
                        <input id="switch2" type="checkbox" lay-skin="switch" lay-text="ON|OFF" lay-filter="switch" <?php if($switch_1 == 'true') {echo 'checked';}?>>
                    </div>
                </div>
                <div class="layui-input-inline">
                    <label class="layui-form-label">牛牛开关</label>
                    <div class="layui-input-block" style="padding:6px 0;">
                        <input id="switch3" type="checkbox" lay-skin="switch" lay-text="ON|OFF" lay-filter="switch" <?php if($switch_2 == 'true') {echo 'checked';}?>>
                    </div>
                </div>
                <div class="layui-input-inline">
                    <label class="layui-form-label">斗地主开关</label>
                    <div class="layui-input-block" style="padding:6px 0;">
                        <input id="switch4" type="checkbox" lay-skin="switch" lay-text="ON|OFF" lay-filter="switch" <?php if($switch_3 == 'true') {echo 'checked';}?>>
                    </div>
                </div>
            </form>
        <!--</div>-->
    </div>
    <div class="titleFormStyle">
        <!--<div class=" layui-col-xs2">-->
        <form action="" class="layui-form " style="float:left;">
            <div class="layui-form-item" >
                <label for="" class="layui-form-label">机器人进场底注匹配</label>
                <div class="layui-input-inline">
                    <select id="match_game_id" lay-filter="matchGameId">
                        <option value="">请选择游戏</option>
                        <option value="524816">炸金花</option>
                        <option value="524818">牛牛</option>
                        <option value="524822">斗地主</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="match_value" id="match_value" placeholder="1/5/10">
                </div>
                <div class="layui-input-inline">
                    <div class="layui-btn" id="saveMatch">保存</div>
                </div>
            </div>
        </form>
        <!--</div>-->
    </div>
    <div class="titleFormStyle">
        <!--<div class=" layui-col-xs2">-->
        <form action="" class="layui-form " style="float:left;">
            <div class="layui-input-inline">
                <label class="layui-form-label">斗地主机器人进场时间</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="enterTime" id="enterTime">
                </div>
                <div class="layui-btn"  id="saveSetting">保存</div>
            </div>
        </form>
        <!--</div>-->
    </div>
    <br>
    <hr>
    <br>
    <div class="layui-row titleFormStyle">
        <div id="btn" class="lf">
            <a class="layui-btn" href="/game-set/general-robot-index">机器人列表</a>
            <button class="layui-btn" data-method="addCharacter">新增性格</button>
        </div>
    </div>

    <div class="title">
        <h2>机器人性格列表</h2>
    </div>

    <!--<div class="box" id="robot1">
        <div class="tableDiv" >
        </div>
        <div class="tableBtn">
            <button class="layui-btn" data-method="reviseBtn">修改</button>
            <button class="layui-btn" data-method="delBtn">删除</button>
        </div>
    </div>-->

</div>
<script TYPE="text/html" id="barDemo">
    <div id="layer">
        <button class="layui-btn layui-btn-xs" lay-event="reviseBtn">修改</button>
        <button class="layui-btn layui-btn-xs" lay-event="delBtn">删除</button>
    </div>
</script>
<script>
    layui.use(['table','form','layer'],function () {
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var layer =layui.layer;

        var charactData = [];
        var tr2 = $('.characterTable tr:eq(1) td');
        var tr5 = $('.characterTable tr:eq(4) td');
        var tr8 = $('.characterTable tr:eq(7) td');
        for (var i=0;i<tr2.length;i++){
            charactData.push(tr2[i].innerHTML);
        }
        for (var i=0;i<tr5.length;i++){
            charactData.push(tr5[i].innerHTML);
        }
        for (var i=0;i<tr8.length;i++){
            charactData.push(tr8[i].innerHTML);
        }

        form.on('switch(switch)',function (data) {
            console.log(data.elem.checked);
            $.ajax({
                url:'/game-set/general-robot-switch',
                type:'POST',
                data:{
                    switch:data.elem.checked,
                    key:data.elem.id
                },
                dataType:"json",
                success:function(res){
                    console.log("1");
                },
                error:function (res) {
                    console.log("2");
                }
            })
        });

        //-------------------------------机器人进场匹配底注-------------------------------------------
        form.on('select(matchGameId)',function (data) {
            getGameMatch();

        })

        //获取对应游戏的底注设置
        function getGameMatch() {
            $.ajax({
                url:'/game-set/game-match-base',
                type:'GET',
                data:{
                    gid:$('#match_game_id').val()
                },
                success:function (res) {
                    res = eval('('+res+')');
                    console.log(res.data);
                    if (res.code == 0) {
                        $('#match_value').val(res.data);
                    }
                }
            });
        }
        //设置
        $('#saveMatch').on('click',function () {
            if (!$('#match_game_id').val()) {
                return layer.msg('请选择游戏',{time:1000});
            }
            if (!$('#match_value').val()) {
                return layer.msg('请填写底注',{time:1000});
            }
            saveMatch();
        });
        function saveMatch() {
            $.ajax({
                url:'/game-set/general-robot-match-base',
                type:'POST',
                data:{
                    gid:$('#match_game_id').val(),
                    match_base:$('#match_value').val()
                },
                success:function (res) {
                    res = eval('('+res+')');
                    if (res.code == 0) {
                        return layer.msg('设置成功',{time:1000});
                    } else {
                        return layer.msg('请选择游戏',{time:1000});
                    }
                }
            });
        }
        //-----------------------------------------------------------------------------------------

        table.on('sort(table1)', function(obj){
            table.reload('robotSettingTable', {
                url:'/test/t205',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

        function robotList () {
            $.ajax({
                url:'/game-set/general-robot-character-list',
                type:'GET',
                data:{
                    page:1,
                    limit:10
                },
                success:function (res) {
                    if (res.code == 0) {
                        var data = res.data;
                        var html = '';
                        for (var i = 0;i < data.length;i++) {
                            data[i].seePoker = JSON.parse(data[i].seePoker);
                            data[i].disPoker = JSON.parse(data[i].disPoker);
                            data[i].followBet = JSON.parse(data[i].followBet);
                            data[i].addBet = JSON.parse(data[i].addBet);
                            data[i].pkPoker = JSON.parse(data[i].pkPoker);
                            data[i].qiangzhuang = JSON.parse(data[i].qiangzhuang);
                            data[i].yafen = JSON.parse(data[i].yafen);
                            data[i].timeInterval = data[i].timeInterval?data[i].timeInterval:'';
                            data[i].setoutTime = data[i].leaveTableProp?data[i].setoutTime:'';
                            data[i].leaveTableTime = data[i].leaveTableTime?data[i].leaveTableTime:'';
                            data[i].leaveTableProp = data[i].leaveTableProp?data[i].leaveTableProp:'';
                            data[i].sendTime = data[i].sendTime?data[i].sendTime:'';
                            data[i].emojiProp = data[i].emojiProp?data[i].emojiProp:'';
                            data[i].textProp = data[i].textProp?data[i].textProp:'';
                            data[i].waitTime = data[i].waitTime?data[i].waitTime:'';
                            data[i].canWaitProp = data[i].canWaitProp?data[i].canWaitProp:'';
                            data[i].downLine = data[i].downLine?data[i].downLine:'';
                            data[i].upWinProp = data[i].upWinProp?data[i].upWinProp:'';
                            data[i].upLine = data[i].upLine?data[i].upLine:'';
                            data[i].downWinProp = data[i].downWinProp?data[i].downWinProp:'';
                            data[i].leaveTableMaxGameNum = data[i].leaveTableMaxGameNum?data[i].leaveTableMaxGameNum:'';
                            data[i].openPokerTime = data[i].openPokerTime?data[i].openPokerTime:'';
                            data[i].ddzLevel = data[i].ddzLevel?data[i].ddzLevel:'';
                            html += '               <div class="box x-body" id="'+data[i].id+'">\n' +
                                '<div class="tableDiv" > <table class="layui-table characterTable" >\n' +
                                '                <tr style="background-color: #F2F2F2">\n' +
                                '                    <td rowspan="8" class="character" >'+data[i].commont+'</td>\n' +
                                '                    <td>操作间隔</td>\n' +
                                '                    <td>准备等待</td>\n' +
                                '                    <td>随机时间</td>\n' +
                                '                    <td>离桌率</td>\n' +
                                '                    <td>时间间隔</td>\n' +
                                '                    <td>表情触发</td>\n' +
                                '                    <td>短信触发</td>\n' +
                                '                    <td>忍耐触发点</td>\n' +
                                '                    <td>短信概率</td>\n' +
                                '                    <td>负警戒线</td>\n' +
                                '                    <td>胜率提升至</td>\n' +
                                '                    <td>正警戒线</td>\n' +
                                '                    <td>失败率提升至</td>\n' +
                                '                    <td>最高游戏次数</td>\n' +
                                '                    <td>开牌时间</td>\n' +
                                '                    <td>斗地主机器人等级</td>\n' +
                                '                </tr>\n' +
                                '                <tr >\n' +
                                '                    <td class="new_data">'+data[i].timeInterval+'</td>\n' +
                                '                    <td class="new_data">'+data[i].setoutTime+'</td>\n' +
                                '                    <td class="new_data">'+data[i].leaveTableTime+'</td>\n' +
                                '                    <td class="new_data">'+data[i].leaveTableProp+'</td>\n' +
                                '                    <td class="new_data">'+data[i].sendTime+'</td>\n' +
                                '                    <td class="new_data">'+data[i].emojiProp+'</td>\n' +
                                '                    <td class="new_data">'+data[i].textProp+'</td>\n' +
                                '                    <td class="new_data">'+data[i].waitTime+'</td>\n' +
                                '                    <td class="new_data">'+data[i].canWaitProp+'</td>\n' +
                                '                    <td class="new_data">'+data[i].downLine+'</td>\n' +
                                '                    <td class="new_data">'+data[i].upWinProp+'</td>\n' +
                                '                    <td class="new_data">'+data[i].upLine+'</td>\n' +
                                '                    <td class="new_data">'+data[i].downWinProp+'</td>\n' +
                                '                    <td class="new_data">'+data[i].leaveTableMaxGameNum+'</td>\n' +
                                '                    <td class="new_data">'+data[i].openPokerTime+'</td>\n' +
                                '                    <td class="new_data">'+data[i].ddzLevel+'</td>\n' +
                                '                </tr>\n' +
                                '                <tr style="background-color: #F2F2F2">\n' +
                                '                    <td colspan="4">看牌率</td>\n' +
                                '                    <td colspan="4">棋牌率</td>\n' +
                                '                    <td colspan="4">跟注</td>\n' +
                                '                    <td colspan="4">加注</td>\n' +
                                '                </tr>\n' +
                                '                <tr style="background-color: #F2F2F2">\n' +
                                '                    <td>1</td>\n' +
                                '                    <td>2~3</td>\n' +
                                '                    <td>4~5</td>\n' +
                                '                    <td>6~9</td>\n' +
                                '                    <td>1</td>\n' +
                                '                    <td>2~3</td>\n' +
                                '                    <td>4~5</td>\n' +
                                '                    <td>6~9</td>\n' +
                                '                    <td>1</td>\n' +
                                '                    <td>2~3</td>\n' +
                                '                    <td>4~5</td>\n' +
                                '                    <td>6~9</td>\n' +
                                '                    <td>1</td>\n' +
                                '                    <td>2~3</td>\n' +
                                '                    <td>4~5</td>\n' +
                                '                    <td>6~9</td>\n' +
                                '                </tr>\n' +
                                '                <tr >\n' +
                                '                    <td class="new_data">'+data[i].seePoker["1"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].seePoker["2-3"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].seePoker["4-5"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].seePoker["6-9"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].disPoker["1"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].disPoker["2-3"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].disPoker["4-5"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].disPoker["6-9"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].followBet["1"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].followBet["2-3"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].followBet["4-5"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].followBet["6-9"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].addBet["1"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].addBet["2-3"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].addBet["4-5"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].addBet["6-9"]+'</td>\n' +
                                '                </tr>\n' +
                                '                <tr style="background-color: #F2F2F2">\n' +
                                '                    <td colspan="4">比牌</td>\n' +
                                '                    <td colspan="4">抢庄</td>\n' +
                                '                    <td colspan="4">叫分</td>\n' +
                                '                </tr>\n' +
                                '                <tr class="numTd" style="background-color: #F2F2F2">\n' +
                                '                    <td>1</td>\n' +
                                '                    <td>2~3</td>\n' +
                                '                    <td>4~5</td>\n' +
                                '                    <td>6~9</td>\n' +
                                '                    <td>1</td>\n' +
                                '                    <td>2~3</td>\n' +
                                '                    <td>4~5</td>\n' +
                                '                    <td>6~9</td>\n' +
                                '                    <td>1</td>\n' +
                                '                    <td>2~3</td>\n' +
                                '                    <td>4~5</td>\n' +
                                '                    <td>6~9</td>\n' +
                                '                </tr>\n' +
                                '                <tr >\n' +
                                '                    <td class="new_data">'+data[i].pkPoker["1"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].pkPoker["2-3"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].pkPoker["4-5"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].pkPoker["6-9"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].qiangzhuang["1"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].qiangzhuang["2-3"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].qiangzhuang["4-5"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].qiangzhuang["6-9"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].yafen["1"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].yafen["2-3"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].yafen["4-5"]+'</td>\n' +
                                '                    <td class="new_data">'+data[i].yafen["6-9"]+'</td>\n' +
                                '                </tr>\n' +
                                '            </table></div><div class="tableBtn">\n'+
                                '<button class="layui-btn" data-method="reviseBtn">修改</button>\n'+
                                '<button class="layui-btn" data-method="delBtn">删除</button>\n'+
                                '</div></div>\n';
                        }
                        if (!html) {
                            html = '无数据';
                        }
                        $('body').append(html);
                        $('.tableBtn .layui-btn').on('click', function(){
                            var othis = $(this), method = othis.data('method');
                            active[method] ? active[method].call(this, othis) : '';
                        });
                    } else {

                    }
                },
                error:function () {

                }
            });
        }

        robotList();
        var active = {
            addCharacter:function () {
                layer.open({
                    type:1
                    ,title:false
                    ,closeBtn:1
                    ,area:['90%','90%']
                    ,id:'LAY_layuipro'
                    //,btn:['确认','取消']
                    ,content:$('#addCharacter')
                    ,success:function () {
                    }
                    ,yes:function () {

                    }
                })
            },
            reviseBtn:function () {
                var id = $(this).parents(".box").attr('id');
                layer.open({
                    type:1
                    ,title:false
                    ,closeBtn:1
                    ,area:['90%','90%']
                    ,id:'LAY_layuipro'
                    //,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#addCharacter')
                    ,success:function (layero,index) {
                        var charactData = $('#'+id+' .new_data');
                        var inputs = $('#addCharacter td input');
                        var j=0;
                        for (var key in inputs){ // inputs[i].val=data[i];
                            if(j<inputs.length){
                                //console.log(charactData[key].innerHTML);
                                inputs[j].value=charactData[key].innerHTML;
                                j++;
                            }
                        }
                        $('#commont_c').val($('#'+id+' .character')[0].innerHTML);
                        $('#robotSubmitBtn').attr('cha_id',id);
                    }
/*
                    ,yes:function (index) {
                        var edit_data = {
                            id:id,
                            commont:$('#commont_c').val(),
                            timeInterval:$('#timeInterval_c').val(),
                            setoutTime:$('#setoutTime_c').val(),
                            leaveTableTime:$('#leaveTableTime_c').val(),
                            leaveTableProp:$('#leaveTableProp_c').val(),
                            leaveTableMaxGameNum:('#leaveTableMaxGameNum_c').val(),
                            sendTime:$('#sendTime_c').val(),
                            emojiProp:$('#emojiProp_c').val(),
                            textProp:$('#textProp_c').val(),
                            waitTime:$('#waitTime_c').val(),
                            canWaitProp:$('#canWaitProp_c').val(),
                            downLine:$('#downLine_c').val(),
                            upWinProp:$('#upWinProp_c').val(),
                            upLine:$('#upLine_c').val(),
                            downWinProp:$('#downWinProp_c').val(),
                            seePoker:{'1':$('#seePoker1_c').val(),'2-3':$('#seePoker2-3_c').val(),'4-5':$('#seePoker4-5_c').val(),'6-9':$('#seePoker6-9_c')},
                            openPoker:{'1':$('#openPoker1_c').val(),'2-3':$('#openPoker2-3_c').val(),'4-5':$('#openPoker4-5_c').val(),'6-9':$('#openPoker6-9_c')},
                            disPoker:{'1':$('#disPoker1_c').val(),'2-3':$('#disPoker2-3_c').val(),'4-5':$('#disPoker4-5_c').val(),'6-9':$('#disPoker6-9_c')},
                            followBet:{'1':$('#followBet1_c').val(),'2-3':$('#followBet2-3_c').val(),'4-5':$('#followBet4-5_c').val(),'6-9':$('#followBet6-9_c')},
                            addBet:{'1':$('#addBet1_c').val(),'2-3':$('#addBet2-3_c').val(),'4-5':$('#addBet4-5_c').val(),'6-9':$('#addBet6-9_c')},
                            pkPoker:{'1':$('#pkPoker1_c').val(),'2-3':$('#pkPoker2-3_c').val(),'4-5':$('#pkPoker4-5_c').val(),'6-9':$('#pkPoker6-9_c')},
                            qiangzhuang:{'1':$('#qiangzhuang1_c').val(),'2-3':$('#qiangzhuang2-3_c').val(),'4-5':$('#qiangzhuang4-5_c').val(),'6-9':$('#qiangzhuang6-9_c')},
                            yafen:{'1':$('#yafen1_c').val(),'2-3':$('#yafen2-3_c').val(),'4-5':$('#yafen4-5_c').val(),'6-9':$('#yafen6-9_c')},
                        }
                        $.ajax({
                            url:'game-set/general-robot-create',
                            type:'POST',
                            data:edit_data,
                            success:function(res){
                                if (res.code == 0) {
                                    layer.close(index);
                                    robotList();
                                } else {
                                    layer.msg('失败');
                                    return;
                                }

                            }
                        })
                    }
*/
                })
            },
            delBtn:function () {
                var id = $(this).parents(".box").attr('id');
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
                        //将删除的跑马灯编号显示在提示窗口
                    }
                    ,yes:function (index,layero) {

                        $.ajax({
                            url:'/game-set/general-robot-character-del',
                            type:'POST',
                            data:{
                                'id': id
                            },
                            success:function (data) {
                                location.replace(window.location.href);
                            },
                            error:function () {
                                console.log("失败");
                            }
                        });
                        layer.close(index);
                    }
                })
            }
        }

        //性格设置方法
        function setCharacter (id) {
            var add_data = {
                commont:$('#commont_c').val(),
                timeInterval:$('#timeInterval_c').val(),
                setoutTime:$('#setoutTime_c').val(),
                leaveTableTime:$('#leaveTableTime_c').val(),
                leaveTableProp:$('#leaveTableProp_c').val(),
                leaveTableMaxGameNum:$('#leaveTableMaxGameNum_c').val(),
                openPokerTime:$('#openPokerTime_c').val(),
                ddzLevel:$('#ddzLevel_c').val(),
                sendTime:$('#sendTime_c').val(),
                emojiProp:$('#emojiProp_c').val(),
                textProp:$('#textProp_c').val(),
                waitTime:$('#waitTime_c').val(),
                canWaitProp:$('#canWaitProp_c').val(),
                downLine:$('#downLine_c').val(),
                upWinProp:$('#upWinProp_c').val(),
                upLine:$('#upLine_c').val(),
                downWinProp:$('#downWinProp_c').val(),
                seePoker:JSON.stringify({'1':$('#seePoker1_c').val(),'2-3':$('#seePoker2-3_c').val(),'4-5':$('#seePoker4-5_c').val(),'6-9':$('#seePoker6-9_c').val()}),
                disPoker:JSON.stringify({'1':$('#disPoker1_c').val(),'2-3':$('#disPoker2-3_c').val(),'4-5':$('#disPoker4-5_c').val(),'6-9':$('#disPoker6-9_c').val()}),
                followBet:JSON.stringify({'1':$('#followBet1_c').val(),'2-3':$('#followBet2-3_c').val(),'4-5':$('#followBet4-5_c').val(),'6-9':$('#followBet6-9_c').val()}),
                addBet:JSON.stringify({'1':$('#addBet1_c').val(),'2-3':$('#addBet2-3_c').val(),'4-5':$('#addBet4-5_c').val(),'6-9':$('#addBet6-9_c').val()}),
                pkPoker:JSON.stringify({'1':$('#pkPoker1_c').val(),'2-3':$('#pkPoker2-3_c').val(),'4-5':$('#pkPoker4-5_c').val(),'6-9':$('#pkPoker6-9_c').val()}),
                qiangzhuang:JSON.stringify({'1':$('#qiangzhuang1_c').val(),'2-3':$('#qiangzhuang2-3_c').val(),'4-5':$('#qiangzhuang4-5_c').val(),'6-9':$('#qiangzhuang6-9_c').val()}),
                yafen:JSON.stringify({'1':$('#yafen1_c').val(),'2-3':$('#yafen2-3_c').val(),'4-5':$('#yafen4-5_c').val(),'6-9':$('#yafen6-9_c').val()}),
            };

            if (add_data.commont.length == 0) {
                return layer.msg('名称必填',{time:1000});

            }

            if (add_data.ddzLevel != 1 && add_data.ddzLevel != 2) {
                return layer.msg('斗地主机器人等级请填写1或2',{time:1000});
            }
            if (id) {
                add_data.id = id;
            }
            $.ajax({
                url:'/game-set/general-robot-character-create',
                data:add_data,
                type:'POST',
                success:function(res){
                    if (res.code == 0) {
                        location.replace(window.location.href);
                    } else {
                        layer.msg('失败');
                        return;
                    }
                }
            });
        }

        //新建机器人按钮绑定事件
        $('#robotSubmitBtn').on('click',function () {
            var id = $(this).attr('cha_id');
            setCharacter(id);
        });

        $('#btn .layui-btn').on('click', function(){
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });


        //获取机器人开关的值
        /*$.ajax({
            url:'',
            data:{},
            dataType:"JSON",
            success:function (res) {
                $('em:first').html('ON');
                $('em:first').parent().addClass('layui-form-onswitch')
            },
            error:function (res) {
                $('em:first').html('ON');
                $('em:first').parent().addClass('layui-form-onswitch')
            }
        })*/

        //斗地主机器人进场时间设置
        $('#saveSetting').on('click',function () {
            $.ajax({
                url:'/game-set/ddz-enter',
                type:'POST',
                data:{
                    enter_time:$('#enterTime').val()
                },
                success:function (res) {
                    res = eval('('+res+')');
                    if (res.code == 0) {
                        return layer.msg('修改成功',{time:1000});
                    } else if (res.code == -202) {
                        return layer.msg('参数错误',{time:1000});
                    }
                },
                error:function (res) {
                    return layer.msg('出现错误',{time:1000});
                }
            });
        });
    })
</script>
</body>
<!--新增性格-->
<div class="x-body " id="addCharacter" style="display: none;">
    <form action="" class="layui-form"  id="robotForm" >
        <div class="layui-form-item">
            <label for="" class="layui-form-label">性格</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="commont_c" id="commont_c">
            </div>
        </div>
        <div class="layui-input-inline">
            <table class="layui-table" >
                <thead>
                <tr>
                    <th>操作间隔</th>
                    <th>准备等待</th>
                    <th>随机时间</th>
                    <th>离桌率</th>
                    <th>时间间隔</th>
                    <th>表情触发</th>
                    <th>短信触发</th>
                </tr>
                <tr>
                    <td><input type="text" class="layui-input" name="timeInterval_c" id="timeInterval_c"></td>
                    <td><input type="text" class="layui-input" name="setoutTime_c" id="setoutTime_c"></td>
                    <td><input type="text" class="layui-input" name="leaveTableTime_c" id="leaveTableTime_c"></td>
                    <td><input type="text" class="layui-input" name="leaveTableProp_c" id="leaveTableProp_c"></td>
                    <td><input type="text" class="layui-input" name="sendTime_c" id="sendTime_c"></td>
                    <td><input type="text" class="layui-input" name="emojiProp_c" id="emojiProp_c"></td>
                    <td><input type="text" class="layui-input" name="textProp_c" id="textProp_c"></td>
                </tr>
                </thead>
            </table>
            <table class="layui-table" >
                <thead>
                <tr>
                    <th>忍耐触发</th>
                    <th>短信概率</th>
                    <th >负警戒线</th>
                    <th>胜率提升至</th>
                    <th>正警戒线</th>
                    <th>失败率提升至</th>
                    <th>最高游戏次数</th>
                    <th>开牌时间</th>
                    <th>斗地主机器人等级</th>
                </tr>
                <tr>
                    <td><input type="text" class="layui-input" name="waitTime_c" id="waitTime_c"></td>
                    <td><input type="text" class="layui-input" name="canWaitProp_c" id="canWaitProp_c"></td>
                    <td><input type="text" class="layui-input" name="downLine_c" id="downLine_c"></td>
                    <td><input type="text" class="layui-input" name="upWinProp_c" id="upWinProp_c"></td>
                    <td><input type="text" class="layui-input" name="upLine_c" id="upLine_c"></td>
                    <td><input type="text" class="layui-input" name="downWinProp_c" id="downWinProp_c"></td>
                    <td><input type="text" class="layui-input" name="leaveTableMaxGameNum_c" id="leaveTableMaxGameNum_c"></td>
                    <td><input type="text" class="layui-input" name="openPokerTime_c" id="openPokerTime_c"></td>
                    <td><input type="text" class="layui-input" name="ddzLevel_c" id="ddzLevel_c"></td>
                </tr>
                </thead>
            </table>
            <table class="layui-table" >
                <thead>
                <tr>
                    <th class="c" colspan="4">看牌率</th>
                    <th class="c" colspan="4">弃牌率</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                </tr>
                <tr>
                    <td><input type="text" class="layui-input" name="seePoker1_c" id="seePoker1_c"></td>
                    <td><input type="text" class="layui-input" name="seePoker2-3_c" id="seePoker2-3_c"></td>
                    <td><input type="text" class="layui-input" name="seePoker4-5_c" id="seePoker4-5_c"></td>
                    <td><input type="text" class="layui-input" name="seePoker6-9_c" id="seePoker6-9_c"></td>
                    <td><input type="text" class="layui-input" name="disPoker1_c" id="disPoker1_c"></td>
                    <td><input type="text" class="layui-input" name="disPoker2-3_c" id="disPoker2-3_c"></td>
                    <td><input type="text" class="layui-input" name="disPoker4-5_c" id="disPoker4-5_c"></td>
                    <td><input type="text" class="layui-input" name="disPoker6-9_c" id="disPoker6-9_c"></td>
                </tr>
                </thead>
            </table>
            <table class="layui-table" >
                <thead>
                <tr>
                    <th class="c" colspan="4">跟注</th>
                    <th class="c" colspan="4">加注</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                </tr>
                <tr>
                    <td><input type="text" class="layui-input" name="followBet1_c" id="followBet1_c"></td>
                    <td><input type="text" class="layui-input" name="followBet2-3_c" id="followBet2-3_c"></td>
                    <td><input type="text" class="layui-input" name="followBet4-5_c" id="followBet4-5_c"></td>
                    <td><input type="text" class="layui-input" name="followBet6-9_c" id="followBet6-9_c"></td>
                    <td><input type="text" class="layui-input" name="addBet1_c" id="addBet1_c"></td>
                    <td><input type="text" class="layui-input" name="addBet2-3_c" id="addBet2-3_c"></td>
                    <td><input type="text" class="layui-input" name="addBet4-5_c" id="addBet4-5_c"></td>
                    <td><input type="text" class="layui-input" name="addBet6-9_c" id="addBet6-9_c"></td>

                </tr>
                </thead>
            </table>
            <table class="layui-table" >
                <thead>
                <tr>
                    <th class="c" colspan="4">比牌</th>
                    <th class="c" colspan="4">抢庄</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                </tr>
                <tr>
                    <td><input type="text" class="layui-input" name="pkPoker1_c" id="pkPoker1_c"></td>
                    <td><input type="text" class="layui-input" name="pkPoker2-3_c" id="pkPoker2-3_c"></td>
                    <td><input type="text" class="layui-input" name="pkPoker4-5_c" id="pkPoker4-5_c"></td>
                    <td><input type="text" class="layui-input" name="pkPoker6-9_c" id="pkPoker6-9_c"></td>
                    <td><input type="text" class="layui-input" name="qiangzhuang1_c" id="qiangzhuang1_c"></td>
                    <td><input type="text" class="layui-input" name="qiangzhuang2-3_c" id="qiangzhuang2-3_c"></td>
                    <td><input type="text" class="layui-input" name="qiangzhuang4-5_c" id="qiangzhuang4-5_c"></td>
                    <td><input type="text" class="layui-input" name="qiangzhuang6-9_c" id="qiangzhuang6-9_c"></td>
                </tr>
                </thead>
            </table>
            <table class="layui-table" style="width: 50%">
                <thead>
                <tr> <th class="c" colspan="4">叫分</th></tr>
                <tr>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                </tr>
                <td><input type="text" class="layui-input" name="yafen1_c" id="yafen1_c"></td>
                <td><input type="text" class="layui-input" name="yafen2-3_c" id="yafen2-3_c"></td>
                <td><input type="text" class="layui-input" name="yafen4-5_c" id="yafen4-5_c"></td>
                <td><input type="text" class="layui-input" name="yafen6-9_c" id="yafen6-9_c"></td>
                </thead>
            </table>
        </div>
        <div class="layui-form-item" style="width: 150px;margin: 0 auto;">
            <input type="submit" class="layui-btn" value="保存" id="robotSubmitBtn" onclick="">
            <button class="layui-btn">重置</button>
        </div>
    </form>
</div>
<!--修改-->
<!--<div class="x-body " id="reviseCharacter" style="display: none;">
    <form action="" class="layui-form"  id="robotForm1" >
        <div class="layui-form-item">
            <label for="" class="layui-form-label">性格</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="character" id="commont">
            </div>
        </div>
        <div class="layui-input-inline">
            <table class="layui-table" >
                <thead>
                <tr>
                    <th>操作间隔</th>
                    <th>准备等待</th>
                    <th>随机时间</th>
                    <th>离桌率</th>
                    <th>时间间隔</th>
                    <th>表情触发</th>
                    <th>短信触发</th>
                </tr>
                <tr>
                    <td><input type="text" class="layui-input" name="timeInterval" id="timeInterval"></td>
                    <td><input type="text" class="layui-input" name="setoutTime" id="setoutTime"></td>
                    <td><input type="text" class="layui-input" name="leaveTableTime" id="leaveTableTime"></td>
                    <td><input type="text" class="layui-input" name="leaveTableProp" id="leaveTableProp"></td>
                    <td><input type="text" class="layui-input" name="sendTime" id="sendTime"></td>
                    <td><input type="text" class="layui-input" name="emojiProp" id="emojiProp"></td>
                    <td><input type="text" class="layui-input" name="textProp" id="textProp"></td>
                </tr>
                </thead>
            </table>
            <table class="layui-table" >
                <thead>
                <tr>
                    <th>忍耐触发</th>
                    <th>短信概率</th>
                    <th >负警戒线</th>
                    <th>胜率提升至</th>
                    <th>正警戒线</th>
                    <th>胜率降低至</th>
                    <th>最高游戏次数</th>
                </tr>
                <tr>
                    <td><input type="text" class="layui-input" name="waitTime" id="waitTime"></td>
                    <td><input type="text" class="layui-input" name="canWaitProp" id="canWaitProp"></td>
                    <td><input type="text" class="layui-input" name="downLine" id="downLine"></td>
                    <td><input type="text" class="layui-input" name="upWinProp" id="upWinProp"></td>
                    <td><input type="text" class="layui-input" name="upLine" id="upLine"></td>
                    <td><input type="text" class="layui-input" name="downWinProp" id="downWinProp"></td>
                    <td><input type="text" class="layui-input" name="leaveTableMaxGameNum" id="leaveTableMaxGameNum"></td>
                </tr>
                </thead>
            </table>
            <table class="layui-table" >
                <thead>
                <tr>
                    <th class="c" colspan="4">看牌率</th>
                    <th class="c" colspan="4">弃牌率</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                </tr>
                <tr>
                    <td><input type="text" class="layui-input" name="seePoker1" id="seePoker1"></td>
                    <td><input type="text" class="layui-input" name="seePoker2-3" id="seePoker2-3"></td>
                    <td><input type="text" class="layui-input" name="seePoker4-5" id="seePoker4-5"></td>
                    <td><input type="text" class="layui-input" name="seePoker6-9" id="seePoker6-9"></td>
                    <td><input type="text" class="layui-input" name="disPoker1" id="disPoker1"></td>
                    <td><input type="text" class="layui-input" name="disPoker2-3" id="disPoker2-3"></td>
                    <td><input type="text" class="layui-input" name="disPoker4-5" id="disPoker4-5"></td>
                    <td><input type="text" class="layui-input" name="disPoker6-9" id="disPoker6-9"></td>
                </tr>
                </thead>
            </table>
            <table class="layui-table" >
                <thead>
                <tr>
                    <th class="c" colspan="4">跟注</th>
                    <th class="c" colspan="4">加注</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                </tr>
                <tr>
                    <td><input type="text" class="layui-input" name="followBet1" id="followBet1"></td>
                    <td><input type="text" class="layui-input" name="followBet2-3" id="followBet2-3"></td>
                    <td><input type="text" class="layui-input" name="followBet4-5" id="followBet4-5"></td>
                    <td><input type="text" class="layui-input" name="followBet6-9" id="followBet6-9"></td>
                    <td><input type="text" class="layui-input" name="addBet1" id="addBet1"></td>
                    <td><input type="text" class="layui-input" name="addBet2-3" id="addBet2-3"></td>
                    <td><input type="text" class="layui-input" name="addBet4-5" id="addBet4-5"></td>
                    <td><input type="text" class="layui-input" name="addBet6-9" id="addBet6-9"></td>
                </tr>
                </thead>
            </table>
            <table class="layui-table" >
                <thead>
                <tr>
                    <th class="c" colspan="4">比牌</th>
                    <th class="c" colspan="4">抢庄</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                </tr>
                <tr>
                    <td><input type="text" class="layui-input" name="pkPoker1" id="pkPoker1"></td>
                    <td><input type="text" class="layui-input" name="pkPoker2-3" id="pkPoker2-3"></td>
                    <td><input type="text" class="layui-input" name="pkPoker4-5" id="pkPoker4-5"></td>
                    <td><input type="text" class="layui-input" name="pkPoker6-9" id="pkPoker6-9"></td>
                    <td><input type="text" class="layui-input" name="qiangzhuang1" id="qiangzhuang1"></td>
                    <td><input type="text" class="layui-input" name="qiangzhuang2-3" id="qiangzhuang2-3"></td>
                    <td><input type="text" class="layui-input" name="qiangzhuang4-5" id="qiangzhuang4-5"></td>
                    <td><input type="text" class="layui-input" name="qiangzhuang6-9" id="qiangzhuang6-9"></td>
                </tr>
                </thead>
            </table>
            <table class="layui-table" style="width: 50%">
                <thead>
                <tr> <th class="c" colspan="4">叫分</th></tr>
                <tr>
                    <th>1</th>
                    <th>2-3</th>
                    <th>4-5</th>
                    <th>6-9</th>
                </tr>
                <td><input type="text" class="layui-input" name="yafen1" id="yafen1"></td>
                <td><input type="text" class="layui-input" name="yafen2-3" id="yafen2-3"></td>
                <td><input type="text" class="layui-input" name="yafen4-5" id="yafen4-5"></td>
                <td><input type="text" class="layui-input" name="yafen6-9" id="yafen6-9"></td>
                </thead>
            </table>
        </div>
        <div class="layui-form-item" style="width: 150px;margin: 0 auto;">
            <input type="submit" class="layui-btn" value="修改">
            <button class="layui-btn">重置</button>
        </div>

    </form>
</div>
-->
</div>
<!--删除-->
<div class="x-body" id="del"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除机器人性格吗？</h2>
</div>
