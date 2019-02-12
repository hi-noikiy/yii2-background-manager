
<style>
    .setBtn{
        float:right;
        position: relative;
        top:30px;
        /*left:30px;*/
    }
</style>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">游戏系统设置</a>
        <a>
            <cite>机器人统计</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>

</div>
<div class="x-body">
    <!--<div class="refresh refreshThis " style="float:right;cursor:pointer;position: relative;"> <i class="layui-icon layui-icon-refresh" ></i></div>-->
    <div class="layui-row">
        <div class="layui-col-xs10">
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                        <ul class="layui-row layui-col-space10 layui-this ">
                            <li class="layui-col-md3">
                                <div class="x-admin-backlog-body">
                                    <p><span>机器人初始元宝</span><cite id="init_gold"></cite></p>
                                    <p><span>机器人携带元宝</span><cite id="take_gold"></cite></p>
                                </div>

                            </li>
                            <li class="layui-col-md3">
                                <div class="x-admin-backlog-body">
                                    <p><span>机器人奖池额度</span><cite id="gold_pool"></cite></p>
                                    <p><span>机器人奖池增减</span><cite id="today_gold_pool"></cite></p>
                                </div>
                            </li>
                            <li class="layui-col-md3">
                                <div class="x-admin-backlog-body">
                                    <p><span>回收元宝额度</span><cite id="recovery_pool"></cite></p>
                                    <p><span>今日回收元宝额度</span><cite id="today_recovery_pool"></cite></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
<div class="layui-row titleFormStyle">
    <div class="layui-btn " data-method="set" id="set">设置</div>
</div>
    <table class="layui-table" id="robotStaticTable"  lay-filter="sort"></table>
</div>

<script>
    layui.use(['table','form'],function () {
        var $ = layui.$;
        var form = layui.form;
        $(".refresh").on("click",function(){
            window.location.href = window.location.href;
        });
        var table = layui.table;

        //页面展示今日信息
        $.ajax({
            url:'/game-set/general-robot-stat-info',
            type:'GET',
            success:function (res) {
                if (res.code == 0) {
                    var data = res.data;
                    $('#init_gold').html(data.init_gold);
                    $('#take_gold').html(data.take_gold);
                    $('#gold_pool').html(data.gold_pool);
                    $('#recovery_pool').html(data.recovery_pool);
                    $('#today_gold_pool').html(data.today_gold_pool);
                    $('#today_recovery_pool').html(data.today_recovery_pool);
                }
            }
        })

        table.render({
            elem:"#robotStaticTable"
            ,url:'/game-set/general-robot-day-stat'
            ,page:true
            ,cols:[[
                {field:"date",title:"日期"}
                ,{field:"robot_num",title:"机器人数量",sort:true}
                ,{field:"character",title:"性格统计"}
                ,{field:"player_num",title:"陪玩家数",sort:true}
                ,{field:"init_gold",title:"初始奖池额度"}
                ,{field:"curr_gold",title:"结算额度",sort:true}
                ,{field:"cost_gold",title:"元宝消耗",sort:true}
                ,{field:"borrow_count",title:"借贷次数",sort:true}
                ,{field:"borrow_gold",title:"借贷额度",sort:true}
                ,{field:"game_count",title:"游戏总场次",sort:true}
                ,{field:"win_count",title:"赢场次",sort:true}
                ,{field:"lose_count",title:"输场次",sort:true}
                ,{field:"win_percent",title:"输赢比例",sort:true,templet:function (d) {
                        return d.win_percent*100+'%'
                    }}
            ]]
            ,done: function(res, curr, count){
            }
        });

        table.on('sort(sort)', function(obj){
            table.reload('robotStaticTable', {
                url:'/game-set/general-robot-day-stat',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
        var active = {
            set:function () {
                layer.open({
                    type: 1
                    ,title: "机器人设置" //不显示标题栏
                    ,closeBtn: 1
                    ,area: ['60%','70%']
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    // ,btn: ['创建','取消']
                    ,btnAlign: 'c'
                    ,moveType: 1 //拖拽模式，0或者1
                    ,content:$('#setPage')
                    ,success:function () {
                        //获取奖池设置
                        $.ajax({
                            url:'general-robot-gold-pool',
                            type:'GET',
                            success:function (res) {
                                if (res.code == 0) {
                                    var data = res.data;
                                    $('#now_gold_pool').val(data.now_gold_pool);
                                    $('#up_limit').val(data.up_limit);
                                    $('#down_limit').val(data.down_limit);
                                    //渲染下拉框
                                    $.ajax({
                                        url:'general-robot-character-list',
                                        type:'GET',
                                        success:function (res) {
                                            var character = res.data;
                                            $("[name='robotCharacter']").html('');

                                            for (var i=0;i<character.length;i++) {
                                                if (data.character_id == character[i].id) {
                                                    $("[name='robotCharacter']").append('<option value="'+character[i].id+'" selected>'+character[i].commont+'</option>') ;

                                                } else {
                                                    $("[name='robotCharacter']").append('<option value="'+character[i].id+'">'+character[i].commont+'</option>') ;
                                                }
                                            }
                                            form.render('select');
                                        }
                                    })
                                } else {
                                    console.log(res);
                                }
                            }
                        })
                        //获取奖池操作记录
                        getGoldPoolLog();
                    }
                })
            }
        };
        $('#set').on('click', function(){
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });

//设置页面的table渲染数据
        function getGoldPoolLog(){
            table.render({
                elem:"#setTable"
                ,url:'/game-set/log-general-robot-gold-pool'
                ,width:800
                ,page:true
                ,cols:[[
                    {field:"create_time",title:"时间",align:"center"}
                    ,{field:"uid",title:"操作人",align:"center"}
                    ,{field:"gold_pool",title:"添加奖池金额",align:"center"}
                    ,{field:"recovery_pool",title:"入库金额",align:"center"}
                ]]
            });
        }


        $('#setPool').on('click',function () {
            $.ajax({
                url:'/game-set/general-robot-gold-pool-set',
                type:'POST',
                data:{
                    'add_gold_pool':$('#add_gold_pool').val(),
                    'character_id':$('#character_id').val(),
                    'up_limit':$('#up_limit').val(),
                    'down_limit':$('#down_limit').val(),
                },
                success:function (res) {
                    if (res.code == 0) {
                        $('#add_gold_pool').val('');
                        return layer.msg('成功',{time:1000});
                    } else {
                        return layer.msg('失败',{time:1000});
                    }
                }
            })
        });

    })
</script>
</body>
<style>
    #setPage .layui-form-label{width:120px!important;}
    #setPage form{margin-top:20px;}
    .setPageBtnR{float: left;position:relative;left:50%;}
    .setP{height:50px;}
    .TD{padding:10px 50px;}
    .TD table{position:absolute;margin:auto;left:0;bottom:0;left:0;right:0;}
    .hsBtn{margin-left:10px;}
</style>
<div id="setPage" style="display: none">
    <form action="" class="layui-form">
        <div class="layui-row">
            <!--<div class="layui-col-xs6">-->
            <div class="layui-form-item" >
                <label for="" class="layui-form-label">当前奖池额度</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" readonly id="now_gold_pool">
                </div>
                <!--<div class="layui-input-inline hsBtn">
                    <div class="layui-btn ">回收入库</div>
                </div>-->
            </div>
            <!--</div>-->
            <!--<div class="layui-col-xs6 layui-col-xs-">-->
            <!---->
            <!--</div>-->
        </div>
        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">奖池额度</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" placeholder="输入添加入奖池的额度" id="add_gold_pool">
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">指定性格</label>
                    <div class="layui-input-inline">
                        <select name="robotCharacter" lay-filter="robotCharacter" id="character_id">
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">警戒上限</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="up_limit">
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">警戒下限</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="down_limit">
                    </div>
                </div>
            </div>
        </div>
        <div class="setP">
            <div class="setPageBtnR">
                <div class="layui-btn" id="setPool">修改</div>
            </div>
        </div>

    </form>
    <div class="TD">
        <table id="setTable"></table>
    </div>

</div>

