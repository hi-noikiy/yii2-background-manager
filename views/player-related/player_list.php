<style>
    .BGO{background-color: #EEEEEE;padding:1px;}
    /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">玩家相关</a>
            <a>
                <cite>玩家列表</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">

    <!--<form action="" class="layui-form">-->
    <div class="layui-form-item BGO">
        <!--<label for="" class="layui-form-label">玩家ID</label>-->
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="玩家ID" id="playerID">
        </div>
        <div class="layui-btn" style="margin-left:-10px;" data-type="search" id="search"><i class="layui-icon  ">&#xe615;</i></div>
    </div>
    <!--</form>-->
    <table class="layui-table" id="playerList" lay-filter="sort"></table>
</div>
<!--<script type="text/html" id="barpartnerList">-->
<!--<button class="layui-btn layui-btn-xs">消耗详情</button>-->
<!--<button class="layui-btn layui-btn-xs">下级详情</button>-->
<!--</script>-->
<script>
    layui.use('table',function () {
        var table =  layui.table;
        //自动加载
        table.render({
            elem:"#playerList"
            ,url:"/player-related/player-list"
            ,method:'post'
            ,page:true
            ,cols:[[
                {field:"number",title:"序号",sort:true}
                ,{field:"userID",title:"创建人ID"}
                ,{field:"nickName",title:"创建人昵称"}
                ,{field:"creatData",title:"创建时间"}
                ,{field:"content",title:"内容"}
            ]]
        });
        var active = {
            search:function () {
                var playerID = $('#playerID').val();
                table.reload('playerList',{
                    url:'/test/t201/'
                    ,page:{
                        curr:1
                    }
                    ,where:{
                        playerID:playerID
                    }
                })
            }
        };
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

//排序
        table.on('sort(sort)', function(obj){
            table.reload('playerList', {
                url:'/test/t205',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
    })
</script>
</body>
