<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>百万棋牌室代理平台</title>
    <link rel="stylesheet" href="static/mobile/agent/css/common.min.css">
    <link rel="stylesheet" href="static/mobile/agent/css/search.min.css?2">
    <script type="text/javascript" src="static/mobile/agent/lib/jquery.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/sky.min.js"></script>
</head>
<body>
<div class="panel panel-index">
    <div class="nav-wrap">
        <div class="nav">
            <a onclick="urlto('wx/route&url=index_all')"></a>
            <h1>提现查询</h1>
        </div>
    </div>
    
    <!-- 头部信息 -->
    <?php include 'base_head.php' ?>

    <div class="search-wrap">
        <div class="tab-container">
            <div class="tab-nav" style="border:none;">
                <a  onclick="urlto('wx/route&url=nw_draw')" class="first">提现</a>
                <a  onclick="urlto('wx/route&url=nw_drawsearch')"  class="on last" >提现查询</a>
            </div>
        </div>
        <div class="search-condition">
          
                <div class="time">
                    <div class="start">
                        <i>开始时间</i>
                        <span class="date-mask" id="sdate_mask"></span>
                        <input type="date" name="sdate" id="sdate" value="" >
                    </div>
                    <span class="line">-</span>
                    <div class="end">
                        <i>结束时间</i>
                        <span class="date-mask" id="edate_mask"></span>
                        <input type="date" name="edate" id="edate" value="">
                    </div>
                     <div onclick="getData()" class="btn-search" style="top:30px">搜索</div>
                </div>
           
                <div class="item" style="min-height: 6rem;padding-bottom: 0.3rem;">
                    <table class="table">
                    <tr>
                        <td colspan="8" style="font-weight: bold;font-size: 13px">提现次数:<span class="red" id="tx_num"></span> 提现总额:<span class="red" id="tx_money"></span></td>
                    </tr>
                    <tr>
                        <th>提现金额</th>
                        <th>提现状态</th>
                        <th>提现时间</th>
                    </tr>
                </table>
                <!--没有数据时展示-->
                    <div class="red result-tip" style="text-align: center;padding-top: 0.4rem;font-weight: 600;">查询结果为零</div>
                </div>
        </div>
    </div>
    <div class="index-wrap">
        <div class="footer-note">
            <p style="text-align:center"><span>百万棋牌室代理平台</span></p>
        </div>
    </div>
</div>

<!--loading组件-->
    <div class="loading-box" id="loading">
        <img src="static/mobile/agent/images/loading.gif" alt="" class="img">
    </div>
    <!---->
<script type="text/javascript" src="static/mobile/agent/js/common.js"></script>
<script src="static/mobile/agent/js/date.js"></script>
<script>
    //ajax  获取数据
    var sdate = $("#sdate");
    var edate = $("#edate");
    $('#loading').show();
    function getData () {
        $('#loading').show();
        var sdateVal = sdate.val();
        var edateVal = edate.val()+' 23:59:59';
        $.ajax({
            url:  base_url +'&r=wxdaili/get-take-money-order'+sign,
            type: 'post',
            dataType: 'json',
            data: {start_time: sdateVal, end_time: edateVal}
        })
            .success(function(data) {
                if (data.ret_code === 0) {
                    var result = data.data;
                    if (result.data.length < 1 || result.data == null  || result.data =='') {
                        $('#loading').hide();
                        $(".result-tip").show();
                        $("#tx_num").html(result.num);
                        $("#tx_money").html(result.money);
                        return false;
                    } else {
                        $(".result-tip").hide();
                    }

                    $(".table-td").remove();
                    $("#tx_num").html(result.num);
                    $("#tx_money").html(result.money);
                    for(var i=0;i<result.data.length;i++){
                        result.data[i]['CREATE_TIME'] = result.data[i]['CREATE_TIME'].slice(0,10);
                        result.data[i]['ID'] = "<?php echo $user_info['user_id']; ?>";
                        result.data[i]['NICK_NAME'] =  "<?php echo $user_info['play_name'] ?>";
                        var $row = $('<tr class="table-td"><td>'+result.data[i]['PAY_MONEY']+'</td><td>'+result.data[i]['STATUS_INFO']+'</td><td>'+result.data[i]['CREATE_TIME']+'</td></tr>');
                        $('.table').append($row);
                    }
                }
                $('#loading').hide();
            })
           
    }
    getData()

</script>
</body>
</html>
