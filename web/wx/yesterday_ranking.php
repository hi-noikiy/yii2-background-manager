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
            <h1>排行榜</h1>
        </div>
    </div>
    <?php include 'base_run.php' ?>
    <div class="search-wrap">
        <div class="search-condition">
            <div class="item" style="min-height: 6rem;padding-bottom: 0.3rem;">
                <table>
                    <tr>
                        <td colspan="3" style="font-weight: bold;font-size: 13px;text-align:center">昨日收入排行榜：</td>
                    </tr>
                    <tr>
                        <th>昨日排名</th>
                        <th>玩家昵称</th>
                        <th>昨日收入(元)</th>
                    </tr>
                    <?php echo $data ?>
                </table>

        </div>
        <div style="width:100%;height:50px">
            <div style='float:left;width: 45%;height: 100%;line-height: 50px;text-align: center;'>
                我的排行：<?php echo $my['rank']?>
            </div>
            <div style='float:left;width: 45%;height: 100%;line-height: 50px;text-align: center;'>
                昨日收入：<?php echo $my['num']?>
            </div>
        </div>
        <div style="width:100%;height:25px;line-height: 25px;text-align: center;">
            昨日排行榜于每日早8点更新
        </div>
    </div>
    <div class="index-wrap">
        <div class="footer-note">
            <p style="text-align:center"><span>百万棋牌室代理平台</span></p>
        </div>
    </div>
</div>

<!---->
<script src="static/mobile/agent/js/date.js"></script>
<script type="text/javascript" src="js/turning.js"></script>
<script type="text/javascript" src="static/mobile/agent/js/dialog.js"></script>
<style type="text/css">
    table {
        width: 100%;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
    }
    tr{
        border-top:1px solid #ccc;
        height: 40px; 
    }
    th{
        height: 40px;
    }


</body>
</html>
