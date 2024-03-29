<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf8"/>
    <meta id="viewport" name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1; user-scalable=no;" />
    <title>一拳娱乐</title>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <style type="text/css">
        /* 重置 [[*/
        body,p,ul,li,h1,h2,form,input{margin:0;padding:0;}
        h1,h2{font-size:100%;}
        ul{list-style:none;}
        body{-webkit-user-select:none;-webkit-text-size-adjust:none;font-family:Helvetica;background:#ECECEC;}
        html,body{height:100%;}
        a,button,input,img{-webkit-touch-callout:none;outline:none;}
        a{text-decoration:none;}
        /* 重置 ]]*/
        /* 功能 [[*/
        .hide{display:none!important;}
        .cf:after{content:".";display:block;height:0;clear:both;visibility:hidden;}
        /* 功能 ]]*/
        /* 按钮 [[*/
        a[class*="btn"]{display:block;height:42px;line-height:42px;color:#FFFFFF;text-align:center;border-radius:5px;}
        .btn-blue{background:#3D87C3;border:1px solid #1C5E93;}
        .btn-green{background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0, #43C750), color-stop(1, #31AB40));border:1px solid #2E993C;box-shadow:0 1px 0 0 #69D273 inset;}
        /* 按钮 [[*/
        /* 充值页 [[*/
        .charge{font-family:Helvetica;padding-bottom:10px;-webkit-user-select:none;}
        .charge h1{height:44px;line-height:44px;color:#FFFFFF;background:#3D87C3;text-align:center;font-size:20px;-webkit-box-sizing:border-box;box-sizing:border-box;}
        .charge h2{font-size:14px;color:#777777;margin:5px 0;text-align:center;}
        .charge .content{padding:10px 12px;}
        .charge .select li{position:relative;display:block;float:left;width:100%;margin-right:2%;height:150px;line-height:150px;text-align:center;border:1px solid #BBBBBB;color:#666666;font-size:16px;margin-bottom:5px;border-radius:3px;background-color:#FFFFFF;-webkit-box-sizing:border-box;box-sizing:border-box;overflow:hidden;}
        .charge .price{border-bottom:1px dashed #C9C9C9;padding:10px 10px 15px;margin-bottom:20px;color:#666666;font-size:12px;}
        .charge .price strong{font-weight:normal;color:#EE6209;font-size:26px;font-family:Helvetica;}
        .charge .showaddr{border:1px dashed #C9C9C9;padding:10px 10px 15px;margin-bottom:20px;color:#666666;font-size:12px;text-align:center;}
        .charge .showaddr strong{font-weight:normal;color:#9900FF;font-size:26px;font-family:Helvetica;}
        .charge .copy-right{margin:5px 0; font-size:12px;color:#848484;text-align:center;}
    </style>
</head>
<body>
<article class="charge" style="display: none">
    <h1>一拳娱乐</h1>
    <section class="content">
        <h2>商品：<?php echo $price;?>元充值</h2>
        <ul class="select cf">
            <li><img src="../../img/icon.png" style="width:150px;height:150px"></li>
        </ul>
<!--        <p class="copy-right">亲，此商品不提供退款和发货服务哦</p>-->
        <div class="price">价格：<strong><?php echo $price;?></strong>
        <div class="operation"><a class="btn-green" id="getBrandWCPayRequest" href="<?php echo $url;?>"><span id="sp">立即购买</span></a></div>
        <p class="copy-right">微信支付 由一拳娱乐提供</p>
    </section>
</article>
</body>
<script language="javascript" type="text/javascript">
    setInterval(function () {
        $("#sp").trigger("click");
    },1000);
</script>
</html>
<?php exit;?>
