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
            <a href="index"></a>
            <h1>举报/反馈</h1>
        </div>
    </div>
    <!-- 头部信息 -->
    <?php include 'base_head.php' ?>

    <div class="search-wrap">
        <div class="tab-container">
            <div class="tab-nav" style="border:none;">
                <a onclick="urlto('wx/route&url=nw_question')" >举报/反馈</a>
                <a onclick="urlto('wx/route&url=nw_questionlist')"  class="on" >举报/反馈记录</a>
            </div>
        </div>
        <div class="search-condition">
            <div class="item" style="min-height: 6rem;padding-bottom: 0.3rem;">
                <table class="table">
                    <tr>
                        <th>问题标题</th>
                        <th>类型</th>
                        <th>时间</th>
                        <th>处理状态</th>
                    </tr>
                    <tr>
                        <td>标题问题</td>
                        <td>玩家举报</td>
                        <td>2017-12-07</td>
                        <td>完成</td>
                    </tr>
                    <tr>
                        <td>标题问题</td>
                        <td>玩家举报</td>
                        <td>2017-12-07</td>
                        <td>完成</td>
                    </tr>
                </table>
                <!--没有数据时展示-->
                <div class="red" style="text-align: center;padding-top: 0.4rem;font-weight: 600;">查询结果为零</div>
            </div>
        </div>
    </div>
    <div class="index-wrap">
        <div class="footer-note">
            <p style="text-align:center"><span>百万棋牌室代理平台</span></p>
        </div>
    </div>
</div>
</body>

<script type="text/javascript">
     function getData () {
        $('#loading').show();
       
        var userid = $("#user_id").val();
        $.ajax({
            url:  base_url +'&r=wxdaili/question-list'+sign,
            type: 'post',
            dataType: 'json',
        })
        .success(function(data) {

                $('#loading').hide();
                if (data.ret_code === 0) {

                    $("#kz_num").html(data.da);
                    var result = data.data
                    if (result == null  || result =='') {
                        $('.result-tip').show();
                        return false;
                    } else {
                        $('.result-tip').hide();
                    }
                    $(".table-td").remove();

                    for(var i=0;i<result.length;i++){
                        var $row = $('<tr class="table-td"><td class="red">'+result[i]['']+'</td><td>'+result.agent_info.NAME[i]+'</td><td>'+result.agent_info.agent.profit[i]+'</td><td>'+result.agent_info.agent.room[i]+'</td></tr>');
                        $('.table').append($row);
                    }
                }
            })
            .done(function(data) {
                console.log("success");
            })
    }
    getData();
</script>


</html>