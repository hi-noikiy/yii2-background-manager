<style>
   .list1 h2{
        /*display: inline;*/
        /*float:left;*/
        /*position: absolute;*/
        /*left:45%;*/
        /*text-align: center;*/
    }

    .lf{
        float:left;
    }
</style>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">游戏系统设置</a>
        <a>
            <cite>白菜设置</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>

</div>
<div class="x-body">
    <div class="layui-row titleFormStyle">
        <div><div class="lf layui-btn" data-method="add" id="add"><i class="layui-icon">&#xe61f;</i>新增白菜</div></div>
    </div>
    <div class="list1">
        <table id="memberList" class="layui-table" lay-filter="table1">
            <caption><h2>白菜列表</h2></caption>
        </table>
    </div>
    <div class="list2">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="date1" name="startDate" placeholder="开始日期">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="date2" name="endDate" placeholder="结束日期">
            </div>
            <button class="layui-btn search " data-type="search1"><i class="layui-icon">&#xe615;</i></button>
        <table id="cabbagePrice" class="layui-table" lay-filter="sort2">
            <caption><h2>白菜货币每日汇总</h2></caption>
        </table>
    </div>
    <div class="list3">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="date" name="start" placeholder="日期">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="playerId" name="playerId" placeholder="用户id">
            </div>
            <button class="layui-btn search" data-type="search2"><i class="layui-icon">&#xe615;</i></button>
        <table id="gobang" class="layui-table" lay-filter="sort3">
            <caption><h2>白菜五子棋每日对局详情</h2></caption>
        </table>
    </div>

</div>
<script type="text/html" id="barMemberList">
    <div id="layerBtn">
        <button lay-event="revise" class="layui-btn layui-btn-sm">修改</button>
        <button lay-event="del" class="layui-btn layui-btn-sm layui-btn-danger" >删除</button>
    </div>
</script>
<script>
    layui.use(['table','layer','laydate'],function () {
        //日期查询
        var $ = layui.$;
        var laydate = layui.laydate;
        var myDate = new Date();
        //获取当前年
        var year=myDate.getFullYear();
        //获取当前月
        var month=myDate.getMonth()+1;
        //获取当前日
        var date=myDate.getDate();
        var now=year+"-"+month+"-"+date;
        laydate.render({elem:'#date1',value:now});
        laydate.render({elem:'#date2',value:now});
        laydate.render({elem:'#date'});

        //表格数据渲染
        var table = layui.table;

        //白菜列表自动加载
        table.render({
            elem:'#memberList'
            ,url:'/system/cabbage'
            ,method:"post"
            ,page:true
            ,cols:[[
                {field:'BID',title:'序号'}
                ,{field:'PLAYER_INDEX',title:'白菜ID',sort:true}
                ,{field:'NAME',title:'白菜姓名'}
                ,{field:'TEL',title:'联系方式'}
                ,{field:'SWITCH',title:'盛付通开关'}
                ,{field:'CREATE_TIME',title:'创建时间'}
                ,{field:'',title:'操作',toolbar:'#barMemberList'}
            ]]

        });

        //白菜设置列表的监听事件
        table.on('tool(table1)',function (obj) {
            var data =obj.data;
            console.log(data);
            //修改操作
            if (obj.event==='revise'){
                var originalPublic;
                data.public==="是"? originalPublic=1:originalPublic=0;
                var id = data.BID;
                var originalPlayerId = data.PLAYER_INDEX;
                var originalTel = data.TEL;
                var originalName = data.NAME;
                var originalSwitch = data.SWITCH;

                layer.open({
                    type: 1
                    ,title: false //不显示标题栏
                    ,closeBtn: 1
                    ,area: ['40%','40%']
                    ,shade: 0.8
                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    ,btn: ['确认输入','取消']
                    ,btnAlign: 'c'
                    ,moveType: 1 //拖拽模式，0或者1
                    ,content:$('#revisecabbagelist')
                    ,success:function (layero,index) {
                        $('#userID').val(originalPlayerId);
                        $('#cabbageName').val(originalName);
                        $('#tel').val(originalTel);
                        $('#switch').val(originalSwitch);
                    }
                    ,yes:function (index,layero) {
                        var userID = $('#userID').val();
                        var cabbageName = $('#cabbageName').val();
                        var tel = $('#tel').val();
                        var switchCont = $('#switch').val();
                        $.ajax({
                            url:'/system/cabbage-edit',
                            type:"POST",
                            data:{
                                'id':id,
                                'userID':userID,
                                'cabbageName':cabbageName,
                                'tel':tel,
                                'switchCont':switchCont
                            }
                            ,success:function (data) {
                                layer.close(index);
                                layer.confirm('修改成功！',{time:1000});
                                table.render({
                                    url:'/system/cabbage'
                                });
                            }
                            ,error:function () {
                                console.log("失败");
                                layer.msg('修改失败！',{time:1000});
                            }
                        });
                        // layer.close(index);
                    }
                })
           //删除操作
            }else if(obj.event==='del'){
                var BID = obj.data.BID;
                var playerId = obj.data.PLAYER_INDEX;
                console.log(BID);
                console.log(playerId);
                layer.open({
                    type:1
                    ,title:false
                    ,closeBtn:1
                    ,area:['30%','30%']
                    ,id:'LAY_layuipro'
                    ,btn:['确认','取消']
                    ,content:$('#del')
                    ,success:function (layero,index) {
                        $('#num').html(playerId);
                    }
                    ,yes:function (index,layero) {
                        $.ajax({
                            url:'/system/del',
                            type:'POST',
                            data:{
                                'BID': BID
                            },
                            success:function (data) {
                                console.log("成功");
                                //删除成功后重载表格
                                table.reload('memberList', {
                                    url:'/system/cabbage'
                                });
                                layer.close(index);
                                layer.msg('删除成功！',{time:1000});
                            },
                            error:function () {
                                console.log("失败");
                                layer.msg('删除失败！',{time:1000});

                            }
                        });

                    }
                })
            }
        });

        //白菜货币每日汇总自动加载
        table.render({
            elem:'#cabbagePrice'
            ,url:'/system/detail'
            ,method:"post"
            ,page:true
            ,cols:[[
                {field:'sid',title:'序号'}
                ,{field:'sdate',title:'日期',sort:true}
                ,{field:'gold_recharge',title:'充值元宝'}
                ,{field:'gold_out',title:'输出元宝'}
                ,{field:'return_rmb',title:'兑回（108：100）元'}
                ,{field:'gold_in',title:'赢入元宝'}
                ,{field:'exchange_rmb',title:'兑出（110：100）元'}
                ,{field:'gold_left',title:'剩余元宝'}
            ]]
        });

        //五子棋对局详情自动加载
        table.render({
            elem:'#gobang'
            ,url:'/system/gobang-detail/'
            ,method:'post'
            ,page:true
            ,cols:[[
                {field:'ID',title:'序号',width :70}
                ,{field:'START_TIME',title:'开始时间',width : 200,sort:true}
                ,{field:'END_TIME',title:'结束时间',sort:true,width : 200}
                ,{field:'HOME_PLAYER',title:'房主ID'}
                ,{field:'HOME_GOLD_TYPE',title:'房主增减'}
                ,{field:'HOME_GOLD_START',title:'对局前'}
                ,{field:'HOME_GOLD_END',title:'对局后'}
                ,{field:'PLAYER',title:'玩家ID'}
                ,{field:'PLAYER_GOLD_TYPE',title:'玩家增减'}
                ,{field:'PLAYER_GOLD_START',title:'对局前'}
                ,{field:'PLAYER_GOLD_END',title:'对局后'}
                ,{field:'GOLD_NUM',title:'元宝扣费'}
            ]]
        });

        //查询
        var active = {
            search1:function () {
                var date1 = $("#date1").val();
                var date2 = $("#date2").val();
                table.reload('cabbagePrice',{
                    url:'/system/detail/'
                    ,method:'post'
                    ,page:{
                        curr:1
                    }
                    ,where:{
                        date1:date1,
                        date2:date2
                    }
                })
            }
            ,search2:function () {
                var date = $('#date').val();
                var playerId = $('#playerId').val();
                table.reload('gobang',{
                    url:'/system/gobang-detail/'
                    ,method:'post'
                    ,page:{
                        curr:1
                    }
                    ,where:{
                        date:date,
                        playerId:playerId
                    }
                })
            }
            //新增白菜功能
            ,add:function () {
                layer.open({
                    type: 1
                    ,title: false //不显示标题栏
                    ,closeBtn: 1
                    ,area: ['40%','40%']
                    ,shade: 0.8
                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    ,btn: ['确认输入','取消']
                    ,btnAlign: 'c'
                    ,moveType: 1 //拖拽模式，0或者1
                    ,content:$('#addContent')
                    ,yes:function (index,layero) {
                        var userID = $('#addUserID').val();
                        var cabbageName = $('#addCabbageName').val();
                        var tel = $('#addTel').val();
                        var switchCont = $('#addSwitch').val();
                        $.ajax({
                            url:'/system/create',
                            type:"POST",
                            data:{
                                'userID':userID,
                                'cabbageName':cabbageName,
                                'tel':tel,
                                'switchCont':switchCont
                            }
                            ,success:function (data) {
                                data = eval("("+data+")");
                                if(data.code == 200){
                                    layer.close(index);
                                    layer.confirm('添加成功！',{time:1000});
                                    table.reload('memberList', {
                                        url:'/system/cabbage'
                                    });
                                }else{
                                    alert(data.msg);
                                }
                            }
                            ,error:function () {
                                alert("添加失败!");
                            }
                        });
                        // layer.close(index);
                    }
                })
            }
        };
        $('.search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        $('#layerBtn .layui-btn').on('click', function(){
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });

        //添加白菜
        $('#add').on('click', function(){
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });

        //排序
        table.on('sort(sort1)', function(obj){
            table.reload('memberList', {
                url:'/system/cabbage',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
        table.on('sort(sort2)', function(obj){
            table.reload('cabbagePrice', {
                url:'/system/detail',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
        table.on('sort(sort3)', function(obj){
            table.reload('gobang', {
                url:'/system/gobang-detail/',
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



<!--白菜设置列表的修改弹出层-->
<style>
    .center{
        position:absolute;
        /*width:300px;*/
        /*height: 100px;*/
        left: 20%;
    }
</style>
<body>
<div class="x-body" id="revisecabbagelist" style="display:none;">
    <div class="center">
        <form action="" class="layui-form">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">用户ID</label>
                <div class="layui-input-inline">
                    <input id="userID" type="text" class="layui-input" readonly>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">白菜姓名</label>
                <div class="layui-input-inline">
                    <input  id="cabbageName" type="text" class="layui-input" name="cabbageName">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">联系方式</label>
                <div class="layui-input-inline">
                    <input  id="tel" type="text" class="layui-input" name="phone">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">盛付通开关</label>
                <div class="layui-input-inline">
                    <select name="switch" id="switch">
                        <option value="1">开启</option>
                        <option value="0">关闭</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="x-body" id="addContent" style="display:none;">
    <div class="center">
        <form action="" class="layui-form">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">用户ID</label>
                <div class="layui-input-inline">
                    <input id="addUserID" type="text" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">白菜姓名</label>
                <div class="layui-input-inline">
                    <input  id="addCabbageName" type="text" class="layui-input" name="addCabbageName">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">联系方式</label>
                <div class="layui-input-inline">
                    <input  id="addTel" type="text" class="layui-input" name="addTel">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">盛付通开关</label>
                <div class="layui-input-inline">
                    <select name="addSwitch" id="addSwitch">
                        <option value="1">开启</option>
                        <option value="0">关闭</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>
</body>


<!--白菜设置列表的修改弹出层-->
<div class="x-body" id="del"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除ID为<span id="num"></span>的会员吗</h2>
</div>
