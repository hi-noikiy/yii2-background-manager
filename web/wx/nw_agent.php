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
    <script type="text/javascript" src="static/mobile/agent/js/common.js"></script>
</head>
<body>
<div class="panel panel-index">
    <div class="nav-wrap">
        <div class="nav">
            <a href="javascript:;" onclick="history.go(-1)"></a>
            <h1>我的代理</h1>
        </div>
    </div>

     <?php include 'base_head.php' ?>
     
    <div class="search-wrap">
        <div class="search-condition">
            <form method="get" id="form1">
                <div class="time" style="padding-bottom: 0;">
                    <div class="start">
                        <i>开始时间</i>
                        <span class="date-mask" id="sdate_mask" style="width: 1.9rem;"></span>
                        <input type="date" name="sdate" id="sdate" value="" style="width: 1.9rem;">
                    </div>
                    <span class="line">-</span>
                    <div class="end">
                        <i>结束时间</i>
                        <span class="date-mask" id="edate_mask" style="width: 1.9rem;"></span>
                        <input type="date" name="edate" id="edate" value="" style="width: 1.9rem;">
                    </div>
                    <div class="fast start" style="margin-left:3px;display: block">
                        <i>快速查询</i>
                        <div class="ipt-container">
                            <select id="type" onchange="quickSearch(this.options[this.options.selectedIndex].value)" style="display: block;height: .78rem;line-height: .78rem;padding-left: .2rem;border: 1px solid #ccc;-webkit-border-radius: .1rem;border-radius: .1rem;box-shadow: none;width: 1.7rem;">
                                <option value="0">请选择</option>
                                <option value="3">3天</option>
                                <option value="7">1周</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="time">
                    <div class="start" style="margin-left:3px;display: block">
                        <i>代理id</i>
                        <input type="number"
                               style=" border: 1px solid #ccc; border-radius: 0.1rem; height: 0.72rem; line-height: 0.72rem; padding-left: 0.2rem; width: 1.9rem;"
                               name="user_id" id="user_id" value="">
                    </div>
                    <a href="javascript:;" class="btn-search" id="search" style="top:32px">查询</a>
                </div>
            </form>
            <div class="item" style="min-height: 6rem;padding-bottom: 0.3rem;">
                <table class="table">
                    <tr>
                        <td colspan="7" style="font-weight: bold;font-size: 13px">所选日期总收益：<span class="red" id="today_profit">0</span></td>

                    <tr>
                        <th>代理ID</th>
                        <th>代理昵称</th>
                        <th>一级返利(10%)</th>
                        <th>二级返利(5%)</th>
                        <?php
                        if ($user_info['daili_level'] == 1) {
                          ?><th>伞下消耗(元宝)</th><?php
                        }
                        ?>
                    </tr>
                   
                   
                </table>
                <!--没有数据时展示-->
                <div class="red result-tip" style="text-align: center;padding-top: 0.4rem;font-weight: 600; display: none; " >查询结果为零</div>
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
<script src="static/mobile/agent/js/date.js"></script>

<script>
    var daili_level = '<?php echo $user_info['daili_level'];?>';
    daili_level = parseInt(daili_level);
  //ajax  获取数据
    function getData (sdateVal,edateVal,userid) {

        $('#loading').show();
        $.ajax({
            url:  base_url +'&r=wxdaili/member-daili-list'+sign,
            type: 'post',
            dataType: 'json',
            data: {start_time: sdateVal, end_time: edateVal,user_id:userid}
        })
        .success(function(data) {
            $('#loading').hide();
            if (data.ret_code === 0) {
                    //$("#kz_num").html(data.data.today_room);
                    $("#today_profit").html(data.data.today_profit);
                    var result = data.data
                    if (result.data == null  || result.data =='') {
                        $('.result-tip').show();
                        return false;
                    } else {
                        $('.result-tip').hide();
                    }
                    $(".table-td").remove();
                    console.log(data.data);
                    for(var i=0;i<result.data.length;i++){
                        if (daili_level == 1) {
                            var $row = $('<tr class="table-td"><td class="red">'+ result.data[i]['PLAYER_INDEX'] +'</td><td>'+result.data[i]['NAME']+'</td><td>'+result.data[i]['PROFIT']+'</td><td>'+result.data[i]['SON_PROFIT']+'</td><td>'+result.data[i]['cost']+'</td></tr>');
                        } else {
                            var $row = $('<tr class="table-td"><td class="red">'+ result.data[i]['PLAYER_INDEX'] +'</td><td>'+result.data[i]['NAME']+'</td><td>'+result.data[i]['PROFIT']+'</td><td>'+result.data[i]['SON_PROFIT']+'</td></tr>');
                        }

                        $('.table').append($row);
                    }
                }
            })
            .done(function(data) {
                console.log("success");
            })
    }

    // 页面进入后 第一次执行
    var no_sdate = $("#sdate").val();
    var no_edate = $("#edate").val() + ' 23:59:59';
    var userid = $("#user_id").val();
    getData(no_sdate,no_edate,userid);

    //快速查询
    function quickSearch(day){

        if(day == 0){
            return ;
        }
        var s_sdate = getBeforeDate(day);
        $("#sdate_mask").html(s_sdate);
        var date = new Date();
        var s_edate = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()+' 23:59:59';
        userid = $("#user_id").val();
        getData(s_sdate,s_edate,userid);

    }

    // 搜索按钮点击事件
    $('#search').click(function () {
         no_sdate = $("#sdate").val();
         no_edate = $("#edate").val() + ' 23:59:59';
         userid = $("#user_id").val();
        getData(no_sdate,no_edate,userid);
    });
</script>

</body>
</html>
