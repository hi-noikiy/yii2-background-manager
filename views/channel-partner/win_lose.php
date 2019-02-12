<style>
    .BGO{background-color: #EEEEEE;padding:1px;}
    .x-nav{margin-bottom:10px!important;padding:0!important;}
</style>
<body>
<div class="x-body">
    <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营统计</a>
            <a>
                <cite>玩家输赢统计</cite>
            </a>
        </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
    </div>
    <div action="" class="layui-form BGO" >
        <div class="layui-input-inline">
            <select name=""  id="gameName">
                <option value="">游戏名称</option>
                <?php foreach ($games as $key=>$val){ ?>
                    <option value=<?php echo $key; ?> ><?php echo $val; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="玩家ID" id="ID">
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="合伙人ID" id="partnerID">
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="查询日期" id="date">
        </div>
        <button class="layui-btn" data-method="search" id="search" data-type="search" id="search"><i class="layui-icon">&#xe615;</i></button>
    </div>
    <table class="layui-table " id="umberllaWinOrLose" lay-filter="sort"></table>
</div>
<script>
    layui.use(['table','layer','laydate'],function () {
        var table = layui.table;
        var laydate = layui.laydate;

        var date = new Date();
        var month = date.getMonth() + 1;
        var strDate = date.getDate() - 1;
        var today = date.getFullYear() + "-" + month + "-" + strDate;
        laydate.render({
            elem:'#date'
            ,value: today
        });
        table.render({
            elem:"#umberllaWinOrLose"
            ,url:"/channel-partner/win-lose"
            ,method:"post"
            ,page:true
            ,where:{
                search_time: today
            }
            ,cols:[[
                {field:"id",title:'序号',sort:true}
                ,{field:"stat_date",title:'日期'}
                ,{field:"game_id",title:'游戏ID'}
                ,{field:"player_id",title:'玩家ID'}
                ,{field:"player_name",title:'玩家昵称'}
                ,{field:"current_gold",title:'当前元宝'}
                ,{field:"counter",title:'游戏局数'}
                ,{field:"win_count",title:'赢局数',sort:true}
                ,{field:"win",title:'赢元宝数',sort:true}
                ,{field:"lose_count",title:'输局数',sort:true}
                ,{field:"lose",title:'输元宝数',sort:true}
                ,{field:"grossYield",title:'毛收益',sort:true}
                ,{field:"rate_win_lose",title:'胜率',sort:true}
                ,{field:"parent_id",title:'上级ID'}
                ,{field:"parent_name",title:'上级昵称'}
                ,{field:"top_id",title:'顶级ID'}
                ,{field:"top_name",title:'顶级昵称'}
            ]]
        });

        //排序
        table.on('sort(sort)', function(obj){
            table.reload('umberllaWinOrLose', {
                url:'/channel-partner/win-lose'
                ,method:"post"
                ,initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
        //查询
        var $ = layui.$, active = {
            search: function(){
                var userID = $('#ID').val();
                var partnerID = $("#partnerID").val();
                var gameName = $('#gameName').val();
                var date = $('#date').val();

                //执行重载
                table.reload('umberllaWinOrLose', {
                    url:'/channel-partner/win-lose'
                    ,method:"post"
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        player_id: userID,
                        gid: gameName,
                        search_time: date,
                        partnerID:partnerID
                    }
                });
            }
        };
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    })
</script>
</body>

