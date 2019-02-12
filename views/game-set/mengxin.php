<!--萌新设置-->
<style>
    /*.x-body{background-color:#EEEEEE;}*/
    label{width:98px!important;}
    .rf{float: right;}


</style>
<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="/game-set/mengxin">游戏系统设置</a>
        <a>
            <cite>萌新设置</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>

<div class="x-body">
    <form action="/game-set/mengxin" class="layui-form InputStyle titleFormStyle" method="post">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">萌新触发概率：</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="probability" value="<?= $redis_data['probability']?>">
            </div>
            <span class="per">%</span>

            <label for="" class="layui-form-label">萌新赢局数：</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="jushu" value="<?= $redis_data['jushu']?>">
            </div>
            <label for="" class="layui-form-label">时长(小时)：</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="max_time" value="<?= $redis_data['max_time']?>">
            </div>
            <div class="layui-input-inline SwitchStyle" >
                <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" <?php if($redis_data['status'] == 0) {echo 'checked' ;}?>>
            </div>
            <div class="layui-input-inline">
                <button class="layui-btn" lay-submit lay-filter="formDemo">修改</button>
            </div>
        </div>
    </form>

    <hr/>
    <br/>
    <br/>


    <div class="layui-col-xs12 layui-col-md4">
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="开始日期" id="startTime">
        </div>
        <!--<div class="layui-input-inline">-->
            <!--<i class="layui-icon dateIcon">&#xe637</i>-->
        <!--</div>-->
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="结束日期" id="endTime">
        </div>
        <!--<div class="layui-input-inline">-->
            <!--<i class="layui-icon dateIcon">&#xe637</i>-->
        <!--</div>-->
        <div class="layui-btn" data-type="search" id="search"><i class="layui-icon  ">&#xe615;</i></div>
    </div>


            <!--<div class="layui-input-inline">-->
                <!--<input type="text" class="layui-input" placeholder="开始日期" id="startTime">-->
            <!--</div>-->
            <!--<div class="layui-input-inline">-->
                <!--<input type="text" class="layui-input" placeholder="结束日期" id="endTime">-->
            <!--</div>-->
            <!--<button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>-->



    <table class="layui-table" id="newSetting" lay-filter="sort" >
        <caption><h2>萌新统计</h2></caption>
    </table>


    <table class="layui-table" id="warning" style="margin-top: 50px;" lay-filter="clearBtn">
        <caption><h2>异常警告</h2></caption>
    </table>

    <table class="layui-table" id="op" style="margin-top: 50px;">
        <caption><h2>操作记录</h2></caption>
    </table>
</div>

<script type="text/html" id="clearStatus">
    <button  class="layui-btn layui-btn-xs" lay-event="revise">清除萌新状态</button>
</script>

<script>
    layui.use(['table','laydate','form'],function () {
        var $ = layui.$;
        //日期
        var laydate = layui.laydate;
        laydate.render({
            elem:'#startTime'
            ,value: '<?= $date['start_time']?>'
        });
        laydate.render({
            elem:'#endTime'
            ,value: '<?= $date['end_time']?>'
        });

        //table渲染
        var table = layui.table;
        table.render({
            elem:'#newSetting'
            ,url:'/game-set/stat-mengxin'
            ,page:true
            ,height:498
            // ,width:1600
            ,cols:[[
                {field:'stat_date',title:'日期'}
                ,{field:'user_all',title:'新用户人数',sort:true}
                ,{field:'play_all',title:'总场次',sort:true}
                ,{field:'play_accord',title:'满足5局赢用户',sort:true}
                ,{field:'win_count',title:'赢的场次',sort:true}
                ,{field:'lose_count',title:'输的场次',sort:true}
                ,{field:'win_sum',title:'赢元宝',sort:true}
                ,{field:'lose_sum',title:'输元宝',sort:true}
            ]]
        });

        table.render({
            elem:'#warning'
            ,url:'/game-set/mengxin-alert'
            ,page:true
            ,width:1600
            ,cols:[[
                {field:'id',title:'ID'}
                ,{field:'gid',title:'游戏ID'}
                ,{field:'player_id',title:'玩家ID'}
                ,{field:'stat_date',title:'统计时间'}
                ,{field:'trigger_count',title:'触发次数'}
                ,{field:'',title:'操作',toolbar:"#clearStatus"}
            ]]
        });
        table.render({
            elem:'#op'
            ,url:'/game-set/op-mengxin'
            ,page:true
            ,width:1600
            ,cols:[[
                {field:'username',title:'操作人'}
                ,{field:'op_time',title:'操作时间'}
                ,{field:'op_content',title:'操作内容'}
            ]]
        });

        //排序
        table.on('sort(sort)',function (obj) {
            table.reload('newSetting',{
                url:'/game-set/stat-mengxin'
                ,initSort:obj
                ,where:{
                    field:obj.field
                    ,order:obj.type
                }
            })
        });

        //清除萌新状态按钮监听
        table.on('tool(clearBtn)',function (obj) {
            var data = obj.data;
//            console.log(data);
            if (obj.event==='revise') {
                var gameID = obj.data.gid;
                var playerID = obj.data.player_id;
                console.log(gameID,playerID);
                $.ajax({
                    url:'/game-set/clear-mengxin',
                    type:"POST",
                    data:{
                        'gameID':gameID,
                        'playerID':playerID
                    }
                    ,dataType:'json'
                    ,success:function (data) {
                        if (data.code == 0) {
                            layer.msg('清除成功！',{time:1000});
                        } else {
                            layer.msg('清除失败', {time:1000});
                        }
                    }
                    ,error:function (data) {
                        layer.msg('清除失败！',{time:1000});
                    }
                })
            }
        })

        //查询
        var active = {
            search:function () {
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();
                table.reload('newSetting',{
                    url:'/game-set/stat-mengxin'
                    ,page:{
                        curr:1
                    }
                    ,where:{
                        startTime:startTime
                        ,endTime:endTime
                    }
                })
            }
        };
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //从后台获取操作记录并显示
        $.ajax({
            type:"GET"
            ,url:'/game-set/op-mengxin'
            ,data:{}
            ,dataType:'JSON'
            ,success:function (val) {
                console.log(val);
                // var data = JSON.parse(val);
                // console.log(data.number);
                console.log(val.data[0].number);
                $('#operationRecord').html(val.data[0].number);
            }
        })

    })
</script>
</body>