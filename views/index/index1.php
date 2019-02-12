<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="/index/index">一拳娱乐</a></div>
    <div class="left_open">
        <i title="展开左侧栏" class="iconfont">&#xe699;</i>
    </div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;"><?= $user['username']?></a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="x_admin_show('个人信息','http://www.baidu.com')">个人信息</a></dd>
                <dd><a onclick="x_admin
                 _show('切换帐号','http://www.baidu.com')">切换帐号</a></dd>
                <dd><a href="/user/login">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index"><a href="/"></a></li>
    </ul>
</div>
<!-- 顶部结束 -->

<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
<div id="side-nav">


<!-- 上半部分-->
<ul id="nav">
<?php
    foreach ($title as $v) {
        echo '<li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe723;</i>
                    <cite>' . $v . '</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">';

        foreach ($data[$v] as $vv) {
            echo '<li>
                <a _href="' . $vv['url'] . '">
                    <i class="iconfont">&#xe6a7;</i>
                    <cite>' . $vv['name'] . '</cite>
                </a>
            </li >';
        }
        echo "</ul></li>";
    }
?>
</ul>
</div>

</div>
<!-- 左侧菜单结束 -->

<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>今日概况</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='/index/welcome' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
    </div>
</div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">Copyright ©2018 All Rights Reserved</div>
</div>
<!-- 底部结束 -->
