<body>
<style>
    .x-body{
        text-align: center;
        margin-top: 40%;
    }
</style>
<div class="x-body">
    <!--查询框-->
    <form action="/api/wechat-access/access" class="layui-form" method="post">
        <div class="layui-input-inline floater">
            <input id="searchCont" type="text" class="layui-input" placeholder="玩家游戏ID" name="uid"/>
        </div>
        <input type="hidden" value="<?php echo $orderId; ?>" name="orderId">
        <button class="layui-btn"><i class="layui-icon">提交</i></button>
    </form>
</div>
</body>
