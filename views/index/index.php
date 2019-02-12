<body class="layui-layout-body">

<div id="LAY_app">
<div class="layui-layout layui-layout-admin">
<div class="layui-header">
    <!-- 头部区域 -->
    <ul class="layui-nav layui-layout-left">
        <li class="layui-nav-item layadmin-flexible" lay-unselect>
            <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
            </a>
        </li>
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="#" target="_blank" title="前台">
                <i class="layui-icon layui-icon-website"></i>
            </a>
        </li>
        <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;" layadmin-event="refresh" title="刷新">
                <i class="layui-icon layui-icon-refresh-3"></i>
            </a>
        </li>
        <?php if($uid == 1){ ?>
            <li class="layui-nav-item" lay-unselect>
                <select style="width: 100px" name="channelList" id="channelList">
                    <option value="1">全渠道</option>
                    <?php foreach ($channelList as $key=>$val){ ?>
                        <option value=<?php echo $val['channel_id'];?>> <?php echo $val['channel_name'];?></option>
                    <?php } ?>
                </select>
            </li>
        <?php } ?>
    </ul>
    <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="theme">
                <i class="layui-icon layui-icon-theme"></i>
            </a>
        </li>
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="note">
                <i class="layui-icon layui-icon-note"></i>
            </a>
        </li>
        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="fullscreen">
                <i class="layui-icon layui-icon-screen-full"></i>
            </a>
        </li>
        <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;">
                <cite><?= $user['username']?></cite>
            </a>
            <dl class="layui-nav-child">
                <dd><a lay-href="">基本资料</a></dd>
                <dd><a lay-href="">修改密码</a></dd>
                <hr>
                <dd style="text-align: center;"><a href="/user/login">退出</a></dd>
            </dl>
        </li>

        <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event=""><i class="layui-icon layui-icon-more-vertical"></i></a>
        </li>
        <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
            <a href="javascript:;" layadmin-event=""><i class="layui-icon layui-icon-more-vertical"></i></a>
        </li>
    </ul>
</div>

<!-- 侧边菜单 -->
<div class="layui-side layui-side-menu">
<div class="layui-side-scroll">
<div class="layui-logo" lay-href="/index/welcome">
    <span>一拳娱乐</span>
</div>

<ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">

<?php
    foreach ($title as $v) {
        echo "<li data-name='{$v}' class='layui-nav-item'>
                <a href='javascript:;' lay-tips='{$v}' lay-direction='2'>
                    <i class='layui-icon layui-icon-app'></i>
                    <cite>{$v}</cite>
                </a>
                <dl class='layui-nav-child'>";
        foreach ($data[$v] as $vv) {
            echo "<dd data-name='{$vv['name']}'>
                        <a lay-href='{$vv['url']}'>{$vv['name']}</a>
                   </dd>";
        }
        echo "</dl></li>";
    }
?>

</ul>
</div>
</div>

<!-- 页面标签 -->
<div class="layadmin-pagetabs" id="LAY_app_tabs">
    <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
    <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
    <div class="layui-icon layadmin-tabs-control layui-icon-down">
        <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
            <li class="layui-nav-item" lay-unselect>
                <a href="javascript:;"></a>
                <dl class="layui-nav-child layui-anim-fadein">
                    <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                    <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                    <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                </dl>
            </li>
        </ul>
    </div>
    <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
        <ul class="layui-tab-title" id="LAY_app_tabsheader">
            <li lay-id="/index/welcome" lay-attr="/index/welcome" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
        </ul>
    </div>
</div>


<!-- 主体内容 -->
<div class="layui-body" id="LAY_app_body">
    <div class="layadmin-tabsbody-item layui-show">
        <iframe src="/index/welcome" frameborder="0" class="layadmin-iframe"></iframe>
    </div>
</div>

<!-- 辅助元素，一般用于移动设备下遮罩 -->
<div class="layadmin-body-shade" layadmin-event="shade"></div>
</div>
</div>

<script src="../layuiadmin/layui/layui.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
    layui.config({
        base: '../layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use('index');
    
    $(function () {
       var thisChannelId = '<?php echo $thisChannelId;?>';
       console.log(thisChannelId);
       if(thisChannelId){
           $('#channelList').val(thisChannelId);
       }
    });

    $('#channelList').on('change', function () {
        var channelId = $('#channelList').val();

        $.ajax({
            type: "POST",
            url: 'set-channel',
            data: {channelId:channelId},
            dataType: "json",
            success: function(data){
                if(data.code == 0){
                    window.location.href = window.location.href;
                }else{
                    alert(data.msg);
                }
            }
        });
    })

</script>
</body>

