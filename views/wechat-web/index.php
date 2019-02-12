<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>一拳娱乐代理平台</title>
    <script src="../static/lib/layui/layui.js"></script>
    <link rel="stylesheet" href="../wx/static/layui/css/layui.css">

</head>
<style>
    .c{text-align: center;}
    .m{margin:20px 0;}
    .box{width:80%;margin:0 auto;}
    .linktitle{font-weight: 600;margin-top:10px;}
    p{line-height: 2rem;}
    .rightIcon{font-size: 15px;font-weight: 600; color: #454545;float: right;position: relative;top:1px;}
    .icon{width:25px;height: 25px;float: left;text-align: center;line-height: 25px;color:#fff;border-radius: 5px;margin-right: 5px;}
    .remarks{color: red;font-weight: 400;display: inline-block;float:right;}
    .agentModeInfo{color: red;}
    .rm{display: inline-block; margin-right:7px;}
    .lm{display: inline-block; margin-left:7px;}
    a{line-height: 25px;display:inline-block;width:100%;}
    .agentInfo{solid #F4900F;padding:10px;}
    .tableStyle
    {
        width:100%;
        border-collapse:collapse;
    }
    .buttonfont{color: #0C0C0C;width: 100%}
    .buttonvalue{float:left; color: #ff0000}
    .hrefspace{margin: 3% 0;}
    html,body{
        width:100%;
        height:100%
    }
    body{text-align:center;}
    hr {/*透明渐变水平线*/
         width:80%;
         margin:0 auto;
         border: 0;
         height: 1px;
         background: #333;
         background-image: linear-gradient(to right, #ccc, #333, #ccc);
    }
    .buttonUi{
        height: 50px;
        font-size: 16px;
        font-weight: 700;
        background-color: #00CCFF;
        letter-spacing: 5px;
        border-radius: 10px;
    }
</style>
<body>

<form class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">玩家ID</label>
        <div class="layui-input-block">
            <input type="text" name="player_id" required lay-verify="required" placeholder="玩家ID" class="layui-input"/>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="tijiao">设置</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<div style="background-color: #00CCFF;color:#fff;height:50px;width:100%;">
    <h2 style="line-height: 50px;text-align: center;">
        一拳娱乐代理平台
    </h2>
</div>
<iframe align="center" width="100%" height="160" src="agentinfo"  frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
<div style="text-align:center" id="agentModeInfo"><a href="https://oss-cdn.601yx.com/wechat/introduce6"><u><font color="red">代理收益模式说明</font></u></a> </div>

<div style="width:100%;">
    <div style="width: 50%;float:left;">
        <ul style="margin: 0 10px;">
            <a href="withdrawcash" class="hrefspace">
                <div class="buttonfont" style="border-radius: 20px">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon  layui-icon-rmb" style="font-size: 15px; color: #FFFFFF;"></i>提现
                        </span>
                    </button>
                </div>
            </a>
            <a href="myagent" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-user" style="font-size: 15px; color: #FFFFFF;"></i>我的代理(<?php echo $daili_num;?>)
                        </span>
                    </button>
                </div>
            </a>
            <a href="twodimensioncode" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-table" style="font-size: 15px; color: #FFFFFF;"></i>推广二维码
                        </span>
                    </button>
                </div>
            </a>

            <a href="agencyschool" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-survey" style="font-size: 15px; color: #FFFFFF;"></i>代理学堂
                        </span>
                    </button>
                </div>
            </a>

            <a href="yesterdayincome" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-list" style="font-size: 15px; color: #FFFFFF;"></i>上周收入排行榜
                        </span>
                    </button>
                </div>
            </a>
        </ul>
    </div>

    <div style="width: 50%;float:right">
        <ul style="margin: 0 10px;">
            <a href="week-rebate-detail" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-rate" style="font-size: 15px; color: #FFFFFF;"></i>周收益详情
                        </span>
                    </button>
                </div>
            </a>
            <a href="results-query" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-list" style="font-size: 15px; color: #FFFFFF;"></i>业绩查询
                        </span>
                    </button>
                </div>
            </a>
            <a href="myplayer" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-form" style="font-size: 15px; color: #FFFFFF;"></i>我的玩家(<?php echo $player_num;?>)
                        </span>
                    </button>
                </div>
            </a>

            <a href="createagent" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-user" style="font-size: 15px; color: #FFFFFF;"></i>开通代理
                        </span>
                    </button>
                </div>
            </a>

            <a href="baseinfo" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-username" style="font-size: 15px; color: #FFFFFF;"></i>我的信息
                        </span>
                    </button>
                </div>
            </a>
        </ul>
    </div>
</div>

<!--<div style="width:60%;float: left;margin:0 auto;margin-top: 40%;color: #00CCFF;">-->
<div style="margin:0 auto;margin-top: 100%;width:300px;height:100px;color: #00CCFF;">
    <hr>
    <pre>___  一拳娱乐代理后台  ___</pre>
</div>

<script>
    layui.use(['form', 'layer'], function(){
        var form = layui.form
            ,$ = layui.$;

        form.on('submit(tijiao)', function(data){
            console.log(data.elem);
            console.log(data.form);
            console.log(data.field);

            $.post('index', data.field, function(res){
                if (res.code == 200) {
                    layer.open({
                        type: 1,
                        content: '设置成功'
                    });
                }
            })
//            layer.msg(JSON.stringify(data.field));
//            $.post('index', )
            return false;
        });
    });
</script>


</body>
</html>