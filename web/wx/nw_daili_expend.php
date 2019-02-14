<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>扑克来了代理平台</title>
    <link rel="stylesheet" href="static/mobile/agent/css/common.min.css">
    <link rel="stylesheet" href="static/mobile/agent/css/search.min.css?2">
    <script type="text/javascript" src="static/mobile/agent/lib/jquery.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/sky.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/common.js"></script>
</head>
<body>
<div class="panel panel-index">
    <div class="nav-wrap">
        <div class="nav">
            <a onclick="urlto('wx/route&url=index_all')"></a>
            <h1>扑克来了代理平台</h1>
        </div>
    </div>

    <div class="welcome-wrap">
        <div class="brief">
            <p class="username" style="border-bottom:1px solid #aaa;padding-bottom: 0.2rem;" align="center">
                渠道合伙人伞下数据
            </p>
            <table border="0" style="margin-top: 0.8em;font-size:.22rem;color:#666;" cellpadding="1" cellspacing="1">
                <tr>
                    <td>总消耗(元)：</td>
                    <td><i class="red" id="agent_cost_total">0.00</i></td>
                    <td>当月消耗(元)：</td>
                    <td><i class="red" id="agent_cost_this_month">0.00</i></td>
                </tr>
                <tr><td colspan="4">&nbsp;</td></tr>
                <tr>
                    <td>代理数量：</td>
                    <td><i class="red" id="agent_daili_num">0</i></td>
                    <td>今日新增代理：</td>
                    <td><i class="red" id="agent_new_daili_today">0</i></td>
                </tr>
                <tr><td colspan="4">&nbsp;</td></tr>
                <tr>
                    <td>玩家数量：</td>
                    <td><i class="red" id="agent_player_num">0</i></td>
                    <td>今日新增玩家：</td>
                    <td><i class="red" id="agent_new_player_today">0</i></td>
                </tr>
            </table>
            <!--<p class="txt" style="margin-top:10px;">
                <span class="left">总消耗(元)：<i class="red" id="agent_cost_total">0.00</i></span>
                <span class="right">当月消耗(元)：<i class="red" id="agent_cost_this_month">0.00</i></span>
            </p>
            <p class="txt" style="margin-top:10px;">
                <span class="left">代理数量：<i class="red" id="agent_daili_num">0</i></span>
                <span class="right">今日新增代理：<i class="red" id="agent_new_daili_today">0</i></span>
            </p>

            <p class="txt" style="margin-top:10px;">
                <span class="left">玩家数量：<i class="red" id="agent_player_num">0</i></span>
                <span class="right">今日新增玩家：<i class="red" id="agent_new_player_today">0</i></span>
            </p>-->
        </div>
    </div>

    <div class="search-wrap">
        <div class="search-condition">
	     <form method="get" id="form1">
                <div class="time" style="padding-bottom: 7px;">
                    <div class="start">
                        <i style="display:block;height:0.74rem;line-height:0.74rem">开始时间</i>
                        <span class="date-mask" style="margin-top:20px" id="sdate_mask" style="width: 1.9rem;"></span>
                        <input style="margin-top:20px" type="date" name="sdate" id="sdate" value="" style="width: 1.9rem;">
                    </div>
                    <span class="line" style="padding-top:0.35rem">-</span>
                    <div class="end">
                        <i style="display:block;height:0.74rem;line-height:0.74rem">结束时间</i>
                        <span class="date-mask" style="margin-top:20px" id="edate_mask" style="width: 1.9rem;"></span>
                        <input type="date" style="margin-top:20px" name="edate" id="edate" value="" style="width: 1.9rem;">
                    </div>
                    <a href="javascript:;" class="btn-search" id="search" style="width:1.4rem;top:0.9rem">查询</a>
                    <a onclick="urlto('wx/route&url=da_manageplayer')" class="btn-search" style="top:0.1rem;width:1.4rem">开通代理</a>
                </div>
            </form>
            <div class="item" style="min-height: 6rem;padding-bottom: 0.3rem;margin-top:20px">
                <table class="table" id="agent_cost_table">
                    <tr>
                        <td colspan="2">上月消耗(元)：<span class="red" id="agent_cost_last_month">0</span>&nbsp;&nbsp;当月消耗(元)：<span class="red" id="agent_cost_this_month_2">0</span></td>
                    </tr>

                    <tr>
                        <th>日期</th>
                        <th>消耗数量(元)</th>
                    </tr>


                </table>
                <!--没有数据时展示-->
                <div class="red result-tip" style="text-align: center;padding-top: 0.4rem;font-weight: 600; display: none; " >暂无数据</div>
            </div>

        </div>
    </div>
    <div class="index-wrap">
        <div class="footer-note">
            <p style="text-align:center"><span>扑克来了代理平台</span></p>
        </div>
    </div>

</div>
<!--loading组件-->
<div class="loading-box" id="loading">
    <img src="static/mobile/agent/images/loading.gif" alt="" class="img">
</div>
<!--date组件-->
<script src="static/mobile/agent/js/date.js"></script>

<script>
    // ajax 获取数据
    function getData (sdateVal,edateVal) {
        $('#loading').show();
        $.ajax({
            url:  base_url +'&r=wx/get-agent-cost-by-day'+sign,
            type: 'post',
            dataType: 'json',
            data: {start_time: sdateVal, end_time: edateVal}
        })
            .success(function(data) {
                $('#loading').hide();
                if (data.ret_code === 0) {
                    var result = data;
                    $(".table-td").remove();
                    if (result.data == null  || result.data =='') {
                        $('.result-tip').show();
                        return false;
                    } else {
                        $('.result-tip').hide();
                    }
                    for (var i = 0; i < result.data.length; i++) {
                        // alert(result.data[i]['DAY']+'||'+result.data[i]['NUM']);
                        var $row = '<tr class="table-td"><td>'+ result.data[i]['DAY'] +'</td><td>'+result.data[i]['NUM']+'</td></tr>';
                        $('.table').append($row);
                    }
                }
            })
            .done(function(data) {
                console.log("success");
            });
    }

    // 页面进入后 第一次执行
    var no_sdate = $("#sdate").val();
    var no_edate = $("#edate").val();
    getData(no_sdate,no_edate);

    // 搜索按钮点击事件
    $('#search').click(function () {
        no_sdate = $("#sdate").val();
        no_edate = $("#edate").val();
        getData(no_sdate,no_edate);
    });
</script>
<script type="text/javascript">
    function getStatis()
    {
        $.ajax({
            url:  base_url +'&r=wx/big-daili-data'+sign,
            type: 'post',
            dataType: 'json',
            data: {}
        })
        .success(function(data) {
            var title_cfg = new Array('cost_total', 'cost_this_month', 'daili_num', 'new_daili_today', 'player_num', 'new_player_today', 'cost_last_month', 'cost_this_month_2');
            if (data.ret_code === 0) {
                var result = data.data
                for (var item in title_cfg) {
                    $("#agent_"+title_cfg[item]).html(result[title_cfg[item]]);
                }
            } else {
                for (var item in title_cfg) {
                    $("#agent_"+title_cfg[item]).html(0);
                }
            }
        });
    }

    $(function () {
        getStatis();
    });
</script>

</body>
</html>
