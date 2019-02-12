<style>
    .BGO{background-color: #EEEEEE;padding:1px;}
    /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营统计</a>
            <a>
                <cite>金币日志</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">

    <div action="" class="layui-form BGO">
        <!--<div class="layui-form-item">-->
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="玩家id" id="player_id" />
            </div>

            <!--<div class="layui-input-inline">-->
                <!--<input type="text" class="layui-input" placeholder="订单id" id="order_id" />-->
            <!--</div>-->

            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="日期" id="date">
            </div>
            <button class="layui-btn"  id="search" data-type="search"><i class="layui-icon">&#xe615;</i></button>
        <!--</div>-->
    </div>
    <table class="layui-table " id="recordDetails" lay-filter="sort"></table>
</div>
<script>
    //日期查询
    layui.use(['table','layer','laydate'],function () {
        var laydate = layui.laydate;

        var myDate = new Date();
        var month = myDate.getMonth()+1;
        var today = myDate.getFullYear()+"-"+month+"-"+myDate.getDate();

        laydate.render({
            elem:'#date'
            ,value: today
        });

        var table = layui.table;

        //自动加载
        table.render({
            elem:"#recordDetails"
            ,url:"/operation-stat/gold-log"
            ,method:"post"
            ,page:true
            ,where: {
                date: today
            }
            ,cols:[[
                {field:"ID",title:'序号'}
                ,{field:"ORDER_ID",title:'操作类型'}
                ,{field:"SOURCE_TYPE",title:'业务类型(0为游戏内赢输消耗)'}
                ,{field:"PLAYER_ID",title:'玩家id'}
                ,{field:"MONEY_TYPE",title:'货币类型'}//(1表示金条)
                ,{field:"COUNT",title:'本次操作货币数量'}
                ,{field:"PRE_COUNT",title:'操作之前的货币数量'}
                ,{field:"OPERATION_TYPE",title:'操作类型'}//(1加2减)
                ,{field:"CREATE_TIME",title:'记录时间'}
                ,{field:"REMARK",title:'备注'}
            ]]
            ,done:function () {
                //判断货币类型。1显示金条。0 显示元宝
                var coinType = $('#recordDetails').next().find('tr').find('td:eq(4) div');
                for (var i=0;i<coinType.length;i++){
                    if ($(coinType[i]).html() == 1){
                        $(coinType[i]).html('元宝')
                    }
                }
            //    判断操作类型，1显示加2显示减
                var operType = $('#recordDetails').next().find('tr').find('td:eq(7) div');
                for (var i=0;i<operType.length;i++){
                    console.log(operType[i])
                    if ($(operType[i]).html() == 1){
                        $(operType[i]).html('加')
                    }else if($(operType[i]).html() == 2){
                        $(operType[i]).html('减')
                    }
                }

            }
        });

        //排序
        table.on('sort(sort)', function(obj){
            table.reload('recordDetails', {
                url:'/operation-stat/gold-log',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

        //查询
        var $ = layui.$, active = {
            search: function(){
                var playerId = $('#player_id').val();
                var orderId = $('#order_id').val();
                var date = $('#date').val();

                //执行重载
                table.reload('recordDetails', {
                    url:'/operation-stat/gold-log'
                    ,method:"post"
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        playerId: playerId,
                        date: date,
                        orderId:orderId
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

