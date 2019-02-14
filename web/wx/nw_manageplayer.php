<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>百万棋牌室代理平台</title>
    <link rel="stylesheet" href="static/mobile/agent/css/common.min.css">
    <link rel="stylesheet" href="static/mobile/agent/css/search.min.css">
    <script type="text/javascript" src="static/mobile/agent/js/jquery.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/sky.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/common.js"></script>
</head>
<body>
<div class="g-loading" id="g_loading">
    <div class="loading-bd">
        <div class='loader'><div class='loader-inner line-scale'><div></div><div></div><div></div><div></div><div></div></div></div>
    </div>
</div>
<div class="panel panel-index">
    <div class="nav-wrap">
        <div class="nav">
            <a onclick="urlto('wx/route&url=index_all')"></a>
            <h1>开通代理</h1>
        </div>
    </div>
   
    <!-- 跑马灯 -->
    <?php include 'base_run.php' ?>
    <!-- 头部信息 -->
    <?php include 'base_head.php' ?>

    <div class="search-wrap">
        <div class="tab-container">
            <div class="tab-nav" style="border:none;">
                <a href="javascript:;" class="on first">开通下级代理</a>
                <a onclick="urlto('wx/route&url=nw_managedaili')" class="last">下级代理管理</a>
            </div>
        </div>
        <div class="search-condition">
            <div class="item" style="min-height: 6rem;padding:0.3rem 0;">
                <table class="table table-bd">
                    <tr>
<td colspan="8" style="font-weight: bold;font-size: 13px;text-align: left;padding: .24rem;">可开通代理个数：<span id="open_total" class="red"></span> (已开通：<span id="open_used" class="red"></span>)， 增加下级代理数量请添加客服微信：<span class="red">BWDLKF01</span></td>
                    <tr>
                        <th>我的玩家ID</th>
                        <th>昵称</th>
                        <th>贡献收益</th>
                        <th>登录时间</th>
                        <th>操作</th>
                    </tr>
                </table>
                <!--没有数据时展示-->
                <div class="red result-tip" style="text-align: center;padding-top: 0.4rem;font-weight: 600;display: none">查询结果为零</div>
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
        <h1 class="alert-title">玩家</h1>
        <div class="alert-tip" id="alerttitle" style="text-align: center;line-height: 0.5rem;padding:0.2rem;">
            请填写正确的数据
        </div>
        <!--<p class="red" style="text-align: center;margin-bottom: 10px;">已成为您的下级代理</p>-->
        <div class="dbtn alert-btn"><span class="btn" >确定</span></div>
    </div>
</div>
<!---->
<!--confirm弹框-->
<div class="alert-mask popup-mask" id="confirm_tip" style="display: none;z-index: 1001;"></div>
<div class="alert-box popup popup-agency-confirm" id="confirm_body" style="display: none;z-index: 1002;">
    <div class="main">
        <h1 class="confirm-title">玩家</h1>
        <div class="confirm-info" style="text-align: center;line-height: 0.5rem;padding:0.2rem;"></div>
        <div class="dbtn confirm-btn cancel-btn"><span class="btn">取消</span></div>
        <div class="dbtn confirm-btn ensure-btn"><span class="btn">确定</span></div>
    </div>
</div>
<!---->
<script>
    $(function () {
      $("#g_loading").fadeOut();
      //    alert弹框出现
      function showalert($msg) {
        $("#alerttitle").html($msg);
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
        $('.confirm-info').html(info);
        $('#confirm_tip').fadeIn();
        $('#confirm_body').fadeIn();
      }
      //    confirm弹框出现
      function closeconfirm() {
        $('#confirm_tip').fadeOut();
        $('#confirm_body').fadeOut();
      }
      $('.cancel-btn').click(function () {
        closeconfirm();
      });
      $('.ensure-btn').click(function () {
            ajaxOpen();
      });
      $('.alert-btn').click(function () {
        closealert();
      });
      //ajax  获取数据代理
      var openBox = $('.table-bd');
      var dataArray = [];
      var attrId;
      var thatArray;
      function getData () {
        $.ajax({
          url: base_url +'&r=wxdaili/manage-player-list'+sign,
          type: 'post',
          dataType: 'json'
        })
          .success(function(data) {

            var open_num  = data.data.used+'/'+data.data.total;
            $("#open_total").html(data.data.over);
	         $("#open_used").html(data.data.used);
            if (data.ret_code === 0) {

              $(".table-td").remove();
              var result = data.data
              if ( result.data == null ) {
                $('.result-tip').show();
                return false;
              } else {
                $('.result-tip').hide();
              }
              dataArray.push(result.data);
        
              for(var i=0;i<result.data.length;i++){
                var $row = $('<tr class="table-td"><td width="25%">'+result.data[i]['PLAYER_INDEX']+'</td><td width="22%">'+result.data[i]['NAME']+'</td><td width="20%">'+result.data[i]['PROFIT']+'</td><td width="23%">'+result.data[i]['LASTTIME']+'</td><td class="open red" width="10%" data-id='+result.data[i]["PLAYER_INDEX"]+'>开通</td></tr>');
                $('.table-bd').append($row);
              }
            }
          })
      }
      getData()
      // 开通按钮事件
      openBox.delegate('.open','click',function () {
        attrId = $(this).attr('data-id');
        console.log(dataArray);
        dataArray.forEach(function (ele, idx) {
          ele.forEach(function (e, i) {
            if (e.PLAYER_INDEX == attrId) {
              thatArray = e;
            }
          })
        });
        showconfirm('ID：<span class="red">'+ thatArray["PLAYER_INDEX"]+'</span></br>昵称：<span class="red">'+ thatArray['NAME']+'</span>')
      });

      function ajaxOpen () {
        $.ajax({
          url: base_url +'&r=wxdaili/open-daili'+sign,
          type: 'post',
          dataType: 'json',
          data: {user_id:thatArray["PLAYER_INDEX"]}
        })
        .success(function(data) {
            if (data.ret_code === 0) {
              closeconfirm();
              showalert('ID：<span class="red">'+ thatArray["PLAYER_INDEX"] +'</span></br>昵称：<span class="red">'+ thatArray['NAME'] +'</span><p class="red" style="text-align: center;margin-bottom: 10px;">已成为您的下级代理</p>');
              getData();
              
            }else{
              closeconfirm();
              showalert('<span class="red">'+data.ret_msg+'</span>');
            }
        })
      }
    })
</script>
</body>
</html>
