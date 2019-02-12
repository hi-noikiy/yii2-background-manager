<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>一拳娱乐代理后台</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/static/css/font.css">
    <link rel="stylesheet" href="/static/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/static/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/js/xadmin.js"></script>
    <!--<link rel="stylesheet" href="../../assets/css/all.css">-->

    <style>
        .InputStyle .layui-input-inline{
            width:90px;
        }
        .dateIcon{
            float: right;
            position: absolute;
            right:18px;
            top:-7px;
        }
        .SwitchStyle{padding-top:5px;}
        .refresh{
            /*border:1px solid #F7F7F7;*/
            background-color: #F7F7F7;
            -webkit-border-radius: 20px;
            -moz-border-radius: 20px;
            border-radius: 20px;
            padding:10px;
            width:20px;
            height:20px;
            float:right;
            cursor:pointer;
            position: absolute;
            z-index: 9;
            top:-20px;
            right:-20px;
            color: #5FB878;
            /*color: #FFF;*/
            font-weight: 900;
        }
        .refresh:hover{
            top:0px;
            right:0px;
            /*background-color: #ffffff;*/
            font-size:20px;
            border-radius: 0;
            border-bottom-left-radius: 30px;
        }
        .refresh .layui-icon{position: absolute;top: 4px;right:5px;}
        .titleFormStyle{
            background-color: #eeeeee;padding:10px;height:60px;box-sizing: border-box;
        }
        .titleFormStyle .layui-form-item{margin-bottom: 0;}
        .per{
            float: left;
            position: relative;
            left:-30px;
            margin-top: 10px;
        }
        .rf{float:right;}
        .lf{float: left;}
        a:hover{cursor: pointer;}
        .delIcon{color:red;}
        .delIcon:hover{color:red;}
        .reviseRecordIcon{color:#000;font-weight: 700}

    </style>

</head>

<!--<div class="refresh refreshThis  " style="width:15px;height:15px;float:right;cursor:pointer;position: relative;z-index: 9;top:20px;color: #5FB878;font-size: 30px;font-weight: 900"><i class="layui-icon " style="position: absolute;top:10px" >ဂ</i></div>-->

<?= $content ?>

<script>

    // $(".refresh").on("click",function(){
    //     window.location.href = window.location.href;
    // });

</script>
</html>