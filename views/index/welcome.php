<body>

<div class="layui-fluid">
<div class="layui-row layui-col-space15">

<div class="layui-col-sm6 layui-col-md3">
    <div class="layui-card">
        <div class="layui-card-header">
            充值
            <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
        </div>
        <div class="layui-card-body layuiadmin-card-list">
            <p class="layuiadmin-big-font"><?= $data['today_recharge']?></p>
            <p>
                各方式
                <span class="layuiadmin-span-color"><span class="layui-bg-orange">活动</span><?= $data['today_activity_recharge']?>&nbsp;&nbsp;<span class="layui-bg-orange">vip</span><?= $data['today_vip_recharge']?>&nbsp;&nbsp;<span class="layui-bg-orange">系统</span><?= $data['today_system_recharge']?><i class="layui-inline layui-icon layui-icon-rmb"></i></span>
            </p>
        </div>
    </div>
</div>
<div class="layui-col-sm6 layui-col-md3">
    <div class="layui-card">
        <div class="layui-card-header">
            一级代理分成
            <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
        </div>
        <div class="layui-card-body layuiadmin-card-list">
            <p class="layuiadmin-big-font"><?= $data['today_player_pay']?></p>
            <p>
                汇总
                <span class="layuiadmin-span-color"><?= $data['total_player_pay']?><i class="layui-inline layui-icon layui-icon-rmb"></i></span>
            </p>
        </div>
    </div>
</div>
<div class="layui-col-sm6 layui-col-md3">
    <div class="layui-card">
        <div class="layui-card-header">
            元宝消耗
            <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
        </div>
        <div class="layui-card-body layuiadmin-card-list">

            <p class="layuiadmin-big-font"><?= $data['today_consume']?></p>
            <p>
                总消耗
                <span class="layuiadmin-span-color"><?= $data['all_consume']?><i class="layui-inline layui-icon layui-icon-rmb"></i></span>
            </p>
        </div>
    </div>
</div>
<div class="layui-col-sm6 layui-col-md3">
    <div class="layui-card">
        <div class="layui-card-header">
            今日代理TX
            <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
        </div>
        <div class="layui-card-body layuiadmin-card-list">
            <p class="layuiadmin-big-font"><?= $data['today_agent_pay']?></p>
            <p class="saveIN">
                库收入
                <span class="layuiadmin-span-color"><?= $data['robot']?><i class="layui-inline layui-icon layui-icon-rmb"></i></span>
            </p>
            <p class="saveINHide layui-hide">
                待添加
                <span class="layuiadmin-span-color">0<i class="layui-inline layui-icon layui-icon-rmb"></i></span>
            </p>
        </div>
    </div>
</div>

<div class="layui-col-sm6 layui-col-md3">
    <div class="layui-card">
        <div class="layui-card-header">
            总充值
            <span class="layui-badge layui-bg-green layuiadmin-badge">年</span>
        </div>
        <div class="layui-card-body layuiadmin-card-list">
            <p class="layuiadmin-big-font"><?= $data['total_recharge']?></p>
            <p>
                各方式
                <span class="layuiadmin-span-color"><span class="layui-bg-orange">活动</span><?= $data['total_activity_recharge']?>&nbsp;&nbsp;<span class="layui-bg-orange">vip</span><?= $data['total_vip_recharge']?>&nbsp;&nbsp;<span class="layui-bg-orange">系统</span><?= $data['total_system_recharge']?><i class="layui-inline layui-icon layui-icon-rmb"></i></span>
            </p>
        </div>
    </div>
</div>
<div class="layui-col-sm6 layui-col-md3">
    <div class="layui-card">
        <div class="layui-card-header">
            代理可TX
            <span class="layui-badge layui-bg-cyan layuiadmin-badge">总</span>
        </div>
        <div class="layui-card-body layuiadmin-card-list">
            <p class="layuiadmin-big-font"><?= $data['agent_pay']?></p>
            <p>
                代理总FL
                <span class="layuiadmin-span-color"><?= $data['all_agent_pay']?><i class="layui-inline layui-icon layui-icon-rmb"></i></span>
            </p>
        </div>
    </div>
</div>
<div class="layui-col-sm6 layui-col-md3">
    <div class="layui-card">
        <div class="layui-card-header">
            元宝淤积
            <span class="layui-badge layui-bg-cyan layuiadmin-badge">总</span>
        </div>
        <div class="layui-card-body layuiadmin-card-list">

            <p class="layuiadmin-big-font deposit"></p>
            <p class="robot">
                robot
                <span class="layuiadmin-span-color robotValue"></span>
            </p>
            <p class="robotHide layui-hide">
                待添加
                <span class="layuiadmin-span-color">0</span>
            </p>
        </div>
    </div>
</div>
<div class="layui-col-sm6 layui-col-md3">
    <div class="layui-card">
        <div class="layui-card-header">
            元宝监控
            <span class="layui-badge layui-bg-green layuiadmin-badge">年</span>
        </div>
        <div class="layui-card-body layuiadmin-card-list">

            <p class="layuiadmin-big-font"><?= $data['monitor']?></p>
            <p>
                输入/输出
                <span class="layuiadmin-span-color"><?= $data['input']?>/<?= $data['output']?><i class="layui-inline layui-icon layui-icon-rmb"></i></span>
            </p>
        </div>
    </div>
</div>


<div class="layui-col-sm8">
    <div class="layui-card">
        <div class="layui-card-header">
            基础数据
        </div>
        <div class="layui-card-body">
            <div class="layui-row">
                <div class="layui-col-sm12">
                    <div class="layui-tab">
                        <ul class="layui-tab-title tabs">
                            <li class="layui-this">实时在线人数</li>
                            <li id="li2">新增用户数</li>
                            <li id="li3">活跃用户</li>
                            <li id="li4">充值额度</li>
                        </ul>
                        <div id="charts" style="width:100%;height:400px;">

                            <div id="main2" class="layui-col-sm8" style="width:100%;height:400px;"></div>
                            <div id="main" style="width:100%;height:400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="layui-col-sm4">
    <div class="layui-card">
        <div class="layui-card-header">
            实时在线
        </div>
        <div class="layui-card-body">
            <div class="layui-row">
                <div class="layui-col-sm12">
                    <div id="main1" class="layui-col-sm4" style="width:100%;height:400px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>

<script src="../../layuiadmin/layui/layui.js"></script>
<script>
    layui.config({
        base: '../../layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index']);
</script>

<script src="https://cdn.bootcss.com/echarts/3.3.2/echarts.min.js" charset="utf-8"></script>
<script type="text/javascript">
var myChart = echarts.init(document.getElementById('main'));
console.log(myChart);
layui.use('table', function(){
    var $ = layui.$;

    $(function () {
        var channelId = '<?php echo $data['channel_id']; ?>';
        console.log(channelId);
	var deposit = '<?php echo $data['deposit']; ?>';
	var robot_deposit = '<?php echo $data['deposit_root']; ?>';
        if(channelId != 1){
            var people = parseInt(deposit)-parseInt(robot_deposit);
            $('.saveIN').addClass('layui-hide');
            $('.robot').addClass('layui-hide');
            $('.saveINHide').removeClass('layui-hide');
            $('.robotHide').removeClass('layui-hide');
            $('.deposit').html(people);
        }else{
	    $('.robotValue').html(robot_deposit);
	    $('.deposit').html(deposit);    
	}
    });

//新增用户
    function mychart2() {
        $.ajax({
            type: 'get',
            url: "/index/new-people",
            success: function(res) {
                var data = eval("(" + res + ")");
                console.log(data);
                option = {
                    color: ['#3398DB'],
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        }
                    },
                    xAxis: {
                        type: 'category',
                        data: data.data.time
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [{
                        name:'新增：',
                        data: data.data.num,
                        type: 'bar'
                    }]
                };
                myChart.setOption(option);
            }
        });
    }
//活跃用户
    function mychart3() {
        $.ajax({
            type: 'get',
            url: "/index/active-people",
            success: function(res) {
                var data = eval("(" + res + ")");
                console.log(data);
                option = {
                    color: ['#3398DB'],
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        }
                    },
                    xAxis: {
                        type: 'category',
                        data: data.data.time
                    },
                    yAxis: {
                        type: 'value'
                    },
                    itemStyle:{
                        normal:{color:'#7CB5EC'}
                    },
                    series: [{
                        name:'活跃：',
                        data: data.data.num,
                        type: 'bar'
                    }]
                };
                myChart.setOption(option);
            }
        });
    }
//充值额度
    function mychart4() {
        $.ajax({
            type: 'get',
            url: "/index/recharge-rental",
            success: function(res) {
                var data = eval("(" + res + ")");
                console.log(data);
                option = {
                    color: ['#3398DB'],
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        }
                    },
                    xAxis: {
                        type: 'category',
                        data: data.data.time
                    },
                    yAxis: {
                        type: 'value'
                    },
                    itemStyle:{
                        normal:{color:'#A5AAD9'}
                    },
                    series: [{
                        name:'充值：',
                        data: data.data.num,
                        type: 'bar'
                    }]
                };
                myChart.setOption(option);
            }
        });
    }

    $(".tabs li:eq(1)").click(function () {
        $('#main1').hide();
        $('#main2').hide();
        $('#main').show();
        mychart2();});
    $(".tabs li:eq(2)").click(function () {
        $('#main1').hide();
        $('#main2').hide();
        $('#main').show();
        mychart3();});
    $(".tabs li:eq(3)").click(function () {
        $('#main1').hide();
        $('#main2').hide();
        $('#main').show();
        mychart4();});

    var myChart0 = echarts.init(document.getElementById('main1'));
    var myChart1 = echarts.init(document.getElementById('main2'));
    function mychart0() {
        setInterval(function () {
            $.ajax({
                type: 'GET',
                url: '/index/real-online',
                success: function(res) {
                    res = eval('('+res+')');
                    var data = res.data;
                    console.log(data);

                    var num_length = data.length;
                    option = {
                        tooltip : {
                            formatter: "{a} <br/>{c} {b}"
                        },
                        toolbox: {
                            show: true,
                            feature: {
                                restore: {show: true},
                                saveAsImage: {show: true}
                            }
                        },
                        series : [
                            {
                                name: '速度',
                                type: 'gauge',
                                z: 3,
                                min: 0,

                                max: 220,
                                splitNumber: 11,
                                radius: '50%',
                                axisLine: {            // 坐标轴线
                                    lineStyle: {       // 属性lineStyle控制线条样式
                                        width: 10
                                    }
                                },
                                axisTick: {            // 坐标轴小标记
                                    length: 15,        // 属性length控制线长
                                    lineStyle: {       // 属性lineStyle控制线条样式
                                        color: 'auto'
                                    }
                                },
                                splitLine: {           // 分隔线
                                    length: 20,         // 属性length控制线长
                                    lineStyle: {       // 属性lineStyle（详见lineStyle）控制线条样式
                                        color: 'auto'
                                    }
                                },
                                title : {
                                    textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                                        fontWeight: 'bolder',
                                        fontSize: 20,
                                        fontStyle: 'italic'
                                    }
                                },
                                detail : {
                                    textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                                        fontWeight: 'bolder'
                                    }
                                },
                                data:[{value: data.data, name: ' '}]
                            },

                        ]
                    };
                    myChart0.setOption(option);
                }
            });
        },5000)
    }
    function mychart1() {
        $.ajax({
            url:'/index/today-player',
            type: 'get',
            //url: url,
            success: function(res) {
                res = eval('('+res+')');
                var data = res.data;
		var time = res.data[1].time;
		if(time.length == 0){
		   time = res.data[0].time;
		}
                console.log(data[0]);
                var option = {
                    tooltip: {
                        trigger: 'axis'
                        // verticalAlign:right
                    },
                    legend: {
                        data:['今日在线人数','昨日在线人数','前日在线人数','四日在线人数','五日在线人数','六日在线人数','七日在线人数'],
                        selected : {
                            //'前日在线人数': false,
                            '四日在线人数':false,
                            '五日在线人数':false,
                            '六日在线人数':false,
			    '七日在线人数':false
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        feature: {
                            saveAsImage: {}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: time
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [
                        {
                            name:'今日在线人数',
                            type:'line',
                            stack: '总量1',
                            data:data[0].num
                        },
                        {
                            name:'昨日在线人数',
                            type:'line',
                            stack: '总量2',
                            data:data[1].num
                        },
                        {
                            name:'前日在线人数',
                            type:'line',
                            stack: '总量3',
                            data:data[2].num
                        },
                        {
                            name:'四日在线人数',
                            type:'line',
                            stack: '总量4',
                            data:data[3].num
                        },
                        {
                            name:'五日在线人数',
                            type:'line',
                            stack: '总量5',
                            data:data[4].num
                        },
                        {
                            name:'六日在线人数',
                            type:'line',
                            stack: '总量6',
                            data:data[5].num
                        },
                        {
                            name:'七日在线人数',
                            type:'line',
                            stack: '总量7',
                            data:data[6].num
                        }
                    ]
                };
                myChart1.setOption(option);
            }
        });
    }
    mychart0();
    mychart1();
    $(".tabs li:first").click(function () {
        $('#main').hide();
        $('#main1').show();
        $('#main2').show();
        mychart0();
        mychart1();
    })
});
</script>
</body>
