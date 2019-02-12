<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">GM工具</a>
        <a>
            <cite>解散房间</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <div class="layui-form"  style="margin-top:50px;">

        <div class="layui-form-item" >
            <label for="" class="layui-form-label" style="width: 30%;">解散选项</label>
            <div class="layui-input-inline" >
                <select name="chess" class="gameId" id="" style="width: 30%;">
                    <?php foreach ($games as $k=>$val){ ?>
                        <option value=<?php echo $k;?> ><?php echo $val;?></option>
                    <?php } ?>
                </select>
            </div>
            <!-- 无用 干掉-->
            <div class="layui-input-inline" style="display: none">
                <select name="type1" id="roomType" style="width: 30%;">
                    <option value="1">类型-元宝自建-场</option>
                    <option value="2">类型-元宝匹配-场</option>
                </select>
            </div>
            <!-- 不知道是啥先隐藏 -->
            <div class="layui-input-inline" style="display: none">
                <select name="type2" id="" style="width: 30%;">
                    <option value=""></option>
                    <option value="">不知道是啥选项</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 30%;">房间号</label>
            <div class="layui-input-inline" style="width: 30%;">
                <input class="layui-input roomNum" name="roomNum">
            </div>
            <button class="layui-btn" onclick="checkDetail()">查询详情</button>
        </div>
        <div class="layui-form-item roomInfo" >
            <label for="" style="width: 30%;" lay-submit="" class="layui-form-label">房间信息</label>
            <label class="tableInfo" style="margin: auto; color: #00A1CB"></label>
        </div>
        <div class="layui-form-item layui-col-xs4 layui-col-xs-offset4" style="margin-top: 30px;">
            <div class="layui-btn" lay-submit="dissloveRoom(1)"  lay-filter="confim">普通解散（日常使用）</div>
            <div class="layui-btn layui-btn-danger" lay-submit="" lay-filter="force">强制解散（谨慎使用）</div>
        </div>
    </div>
</div>
<script>
    layui.use('form',function () {
        var form = layui.form;
        var $ = layui.$;
        //解散房间
        form.on('submit(confim)',function (data) {
            var tableId = $(".roomNum").val();
            var gameId = $(".gameId").val();
            $.ajax({
                type: 'post'
                , data: {
                    'tableId': tableId,
                    'gameId': gameId
                }
                , url: '/dissolve-room/dissolve-room'
                , success: function (data) {
                    data = eval("("+data+")");
                    console.log(data);
                    if(data.code == 0){
                        alert('解散成功！');
                    }else{
                        alert(data.msg);
                    }
                }
                ,error:function () {
                    alert('解散失败,请稍后重试！');
                }
            });
        });

        form.on('submit(force)',function (data) {
            var tableId = $(".roomNum").val();
            var gameId = $(".gameId").val();
            $.ajax({
                type: 'post'
                , data: {
                    'tableId': tableId,
                    'gameId': gameId
                }
                , url: '/dissolve-room/force-dissolve-room'
                , success: function (data) {
                    data = eval("("+data+")");
                    console.log(data);
                    if(data.code == 0){
                        alert('解散成功！');
                    }else{
                        alert(data.msg);
                    }
                }
                ,error:function () {
                    alert('解散失败,请稍后重试！');
                }
            });
        });
    });

    //查询房间详情
    function checkDetail() {
        var tableId = $(".roomNum").val();
        var gameId = $(".gameId").val();
//        alert(tableId);alert(gameId);return;
        $.ajax({
            type: 'post'
            , data: {
                'tableId': tableId,
                'gameId': gameId,
            }
            , url: '/dissolve-room/search-room'
            , success: function (data) {
                data = eval("("+data+")");
                console.log(data);
                if(data.code == 0){
                    info = data.data;
                    console.log(info);
                    $(".tableInfo").html(info.info);
                }else{
                    alert(data.msg);
                }

            }
            ,error:function () {
                alert('查询失败,请稍后重试！');
            }
        });
    }
</script>
</body>
