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
            <a onclick="urlto('wx/route&url=index_all')"></a>
            <h1>我的玩家</h1>
        </div>
    </div>
    
    <?php include 'base_run.php' ?>

    <div class="player-header">
        <div class="box">
            <span class="name">
                <img src="static/mobile/agent/images/index/icon-title.png" alt="" class="img-l img">
                我的玩家
                <img src="static/mobile/agent/images/index/icon-title.png" alt="" class="img-r img">
            </span>
        </div>
    </div>
    <div class="search-wrap">
        <div class="search-condition">
            <form method="get" id="form1">
                <div class="time" style="padding-bottom: 0;">
                    <div class="start">
                        <i>开始时间</i>
                        <span class="date-mask" id="sdate_mask" style="width:2rem;"></span>
                        <input type="date" name="sdate" id="sdate" value="" style="width:2rem;">
                    </div>
                    <span class="line">-</span>
                    <div class="end">
                        <i>结束时间</i>
                        <span class="date-mask" id="edate_mask" style="width:2rem;"></span>
                        <input type="date" name="edate" id="edate" value="" style="width:2rem;">
                    </div>
		<a href="javascript:;" class="btn-search" id="search1" style="bottom:0.3rem;right:0;">查询</a>
                </div>
                <div class="time">
                    <div class="start" style="margin-left:3px;display: block">
                        <i>玩家id</i>
                        <input type="number"
                               style=" border: 1px solid #ccc; border-radius: 0.1rem; height: 0.72rem; line-height: 0.72rem; padding-left: 0.2rem; width: 2.04rem;"
                               name="user_id" id="user_id" min="0">
                    </div>
                    <a href="javascript:;" class="btn-search" id="search" style="bottom:0.3rem;right:auto;left:2.5rem;">查询</a>
                    <a href="javascript:;" class="btn-search" style="bottom:0.3rem;right:auto;left:4rem;width:1.8rem;" id="bindPlayer">手动绑定</a>
                </div>
            </form>
            <div class="item" style="min-height: 6rem;padding-bottom: 0.3rem;">
                <table class="table">
                    <tr>
                        <td colspan="7" style="font-weight: bold;font-size: 13px">所选日期总收益：<span class="red" id = "today_profit">0</span></td>
                    </tr>
                    <tr>
                        <th>玩家ID</th>
                        <th>玩家昵称</th>
                        <th>返利</th>
                        <th>剩余元宝</th>
                        <th>最后登录</th>
                    </tr>
                   
                </table>
                <!--没有数据时展示-->
                <div class="red result-tip" style="text-align: center;padding-top: 0.4rem;font-weight: 600; display: none;">查询结果为零</div>
            </div>
        
            <div class="page-box" id="page_box1">
                <div class="first page disabled">首页</div>
                <div class="previous page disabled">上页</div>
                <input type="text" class="input" id="input_page" value="1">
                <div class="next page">下页</div>
                <div class="last page">末页</div>
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

    <!--alert弹框-->
<div class="alert-mask popup-mask" id="alerttip" style="display: none;z-index: 1001;"></div>
<div class="alert-box popup popup-agency-confirm" id="alertbody" style="display: none;z-index: 1002;">
    <div class="main">
        <div class="alert-tip" style="text-align: center;line-height: 0.5rem;padding:0.2rem;">
            <span class="alerttitle font-weight_b"></span>
        </div>
        <p style="text-align: center;margin-bottom: 10px;"><span class="red player-name"></span></p>
        <div class="dbtn alert-btn">
            <span class="btn">确定</span></div>
    </div>
</div>
<!---->
<!--confirm弹框-->
<div class="alert-mask popup-mask" id="confirm_tip" style="display: none;z-index: 1001;"></div>
<div class="alert-box popup popup-agency-confirm" id="confirm_body" style="display: none;z-index: 1002;">
    <div class="main">
        <h1 class="confirm-title" id="confirm_title"></h1>
        <div class="confirm-info confirm-input" style="text-align: center;line-height: 0.5rem;padding:0.2rem;">
            <input type="number" id="play_ID" placeholder="请输入玩家ID" style="width: 80%;border: 1px solid #ccc;border-radius:5px;padding: 5px;margin: 15px 0;">
        </div>
        <div class="dbtn confirm-btn cancel-btn"><span class="btn">取消</span></div>
        <div class="dbtn confirm-btn ensure-btn"><span class="btn">确定</span></div>
    </div>
</div>
<!---->
<script src="static/mobile/agent/js/date.js"></script>
<script type="text/javascript" src="js/turning.js"></script>
<script type="text/javascript" src="static/mobile/agent/js/dialog.js"></script>

<script>
  //ajax  获取数据
    var sdate = $("#sdate");
    var edate = $("#edate");
    
    /*function getData () {
        $('#loading').show();
        var sdateVal = sdate.val();
        var edateVal = '';
       if(edate.val() != ''){
        var edateVal = edate.val() + ' 23:59:59';
       }
        var userid = $("#user_id").val();
        $.ajax({
            url:  base_url +'&r=wxdaili/member-list'+sign,
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
                   
                    for(var i=0;i<result.data.length;i++){
                        var $row = $('<tr class="table-td"><td>'+result.data[i]['PLAYER_INDEX']+'</td><td>'+result.data[i]['NAME']+'</td><td>'+result.data[i]['PROFIT']+'</td><td>'+result.data[i]['GOLD']+'</td><td>'+result.data[i]['REGISTIME']+'</td></tr>');
                        $('.table').append($row);
                    }
                }
            })
            .done(function(data) {
                console.log("success");
            })
    }*/

    /*数据分页*/
    var trunpage1 = new TurnPage('page_box1');
    function getData(){
      $('#loading').show();

      var sdateVal = sdate.val();

      var edateVal = '';
      if(edate.val() != ''){
        var edateVal = edate.val() + ' 23:59:59';
      }
      var userid = $("#user_id").val();
      user_url = base_url +'&r=wxdaili/member-list&page_size=10'+sign,
      trunpage1.init(user_url,{start_time: sdateVal, end_time: edateVal,user_id:userid},function(data){ return userlist(data)});
    }

    function userlist(data){
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
        for(var i=0;i<result.data.length;i++){
            var $row = $('<tr class="table-td"><td>'+result.data[i]['PLAYER_INDEX']+'</td><td>'+result.data[i]['NAME']+'</td><td>'+result.data[i]['PROFIT']+'</td><td>'+result.data[i]['GOLD']+'</td><td>'+result.data[i]['REGISTIME']+'</td></tr>');
            $('.table').append($row);
        }
      }
      return  data.data.page_count;
    }


    getData();
    // 搜索按钮点击事件
    $('#search').click(function () {
        getData();
    });
    $('#search1').click(function () {
        getData();
    });

     $(function () {
      //    alert弹框出现
      function showalert($msg, name) {
        $(".alerttitle").html($msg);
        $(".player-name").html(name);
        $('#alerttip').fadeIn();
        $('#alertbody').fadeIn();
      }
      //    关闭alert弹框
      function closealert() {
        $('#alerttip').fadeOut();
        $('#alertbody').fadeOut();
      }
      //    confirm弹框出现
      function showconfirm(info) {
        $('#play_ID').val('');
        $('#confirm_title').html(info);
        $('#confirm_tip').fadeIn();
        $('#confirm_body').fadeIn();
      }
      //    confirm弹框关闭
      function closeconfirm() {
        $('#confirm_tip').fadeOut();
        $('#confirm_body').fadeOut();
      }
      // confirm 取消按钮
      $('.cancel-btn').click(function () {
        closeconfirm();
      });
      // alert 确定按钮
      $('.alert-btn').click(function () {
        closealert();
      });
      // 点击绑定玩家按钮
      $('#bindPlayer').click(function () {
        showconfirm('绑定玩家');
      });
      // confirm 确定按钮
      $('.ensure-btn').click(function () {
        var playId = $('#play_ID').val();
        if (playId == '') {
          closeconfirm();
          showalert('温馨提示', '请输入玩家ID');
          return;
        }
        myloading(true,'confirm_body');
        ajaxPlayerID(base_url +'&r=wxdaili/bind-member'+sign,playId);
      });
      // ajax提交玩家id
      function ajaxPlayerID (url, id) {
        $.ajax({
          url: url,
          type: 'post',
          dataType: 'json',
          data: {playID: id}
        })
        .success(function(data) {
          // 请求成功
          myloading(false);
          // id输入正确
          if (data.ret_code == 0) {
            closeconfirm();
            showalert('恭喜您!', '绑定玩家'+id+'成功');
          }else if(data.ret_code === 10005){
            closeconfirm();
           // $('#confirm_title').html('输入玩家ID有误，请重新输入!');
            showalert('玩家ID有误');
          }else{
            closeconfirm();
            showalert('对不起！',data.ret_msg);
          }
        })
        .error(function () {
          myloading(false);
        })
      }
    })

</script>
</body>
</html>
