<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>上周收入排行榜</title>
    <link rel="stylesheet" href="../static/lib/layui/css/layui.css">
    <script src="../static/lib/layui/layui.js"></script>
    <!--<link rel="stylesheet" href="static/bootstrap-4.1.1-dist/css/bootstrap.css">-->
    <!--<script src="static/bootstrap-4.1.1-dist/js/bootstrap.js"></script>-->
    <!--<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
    <!--<script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>-->
    <!--<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
</head>
<style>
    .layui-table-cell{padding:0!important;}
</style>
<body>
<div style="background-color: #00CCFF;color:#fff;height:50px;width:100%;">
    <a href="index" style="color:#fff;"><i class="layui-icon layui-icon-return" style="float:left;position: relative;left:15px;top:17px;"></i></a>
    <h2 style="line-height: 50px;text-align: center;">上周收入排行榜</h2>
</div>
<div class="layui-container" style="margin-top:10px;" >
    <p style="text-align: center;font-weight: 550">上周收入排行榜</p>
    <hr>
    <table class="layui-table" id="yesterdayIncome"></table>
    <hr>
    <div><span id="isRank"></span><span>     </span><span id="myIncome"></span></div>
</div>
</body>
<script>
    layui.use(['table'],function () {
        var table = layui.table;
        table.render({
            elem:"#yesterdayIncome"
            ,url:"/wechat/yesterday-income-list"
            ,page:true
            ,size: 'lg'
            ,cols:[[
                {field:"rank",title:'上周排名', align: 'center', width: '30%'}
                ,{field:"name",title:'玩家昵称', align: 'center', width: '30%'}
                ,{field:"num",title:'上周收入(元)', align: 'center', width: '40%'}
            ]]
            ,done:function (res, curr, count) {
                console.log(res);
                console.log(res.code);
                var data = res.data;
                $.ajax({
                    url:"/wechat/my-yesterday-income"
                    ,success:function (res_) {
                        res  = eval('('+res_+')');
                        if (res.code == 0) {
                            var rank = 0;
                            for (var i = 0; i < data.length; i++) {
                                if (res.data >= data[i].num) {
                                    rank = 1;
                                    $('#isRank').html('我的排行:'+i);
                                    $('#myIncome').html('上周收入:'+res.data);
                                }
                            }
                            if (rank == 0) {
                                $('#isRank').html('我的排行:未上榜');
                                $('#myIncome').html('上周收入:'+res.data);
                            }
                        }
                    }
                });

            }

        });
    })
</script>
</html>