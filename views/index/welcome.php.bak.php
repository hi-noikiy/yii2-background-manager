<body>
<div class="x-body layui-anim layui-anim-up">
	<fieldset class="layui-elem-field">
		<legend>数据统计</legend>
        <form action="/index/welcome" method="post">
<!--            <select name="partnerId" id="partnerId" style="width: 90%">-->
<!--                <option value="">选择渠道合伙人</option>-->
<!--                --><?php //foreach ($data['partnerList'] as $key=>$val){ ?>
<!--                    <option value=--><?php //echo $val['player_id']; ?><!-- --><?php //if($data['hasChose'] == $val['player_id']){echo "selected";} ?><!-- >--><?php //echo $val['name'];?><!--</option>-->
<!--                --><?php //} ?>
<!--            </select>-->
<!--            <input type="submit" class="layui-btn" name="search">-->
        </form>
		<div class="layui-field-box">
			<div class="layui-col-md12">
				<div class="layui-card">
					<div class="layui-card-body">
						<div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
							<ul class="layui-row layui-col-space12 layui-this ">
                                <li class="layui-col-md3">
                                    <div class="x-admin-backlog-body">
                                        <div>今日: 充值额度</div>
                                        <div><span>RMB:<p style="display: inline-block"><cite><?php echo $data['todayData']['recharge'];?></cite></p>元（活动充值：<?php echo $data['todayData']['activity_recharge'];?>元）</span></div>
                                    </div>
                                    <div class="x-admin-backlog-body">
                                        <div>今日: VIP</div>
                                        <div><span>RMB:<p style="display: inline-block"><cite><?php echo $data['todayData']['vip_recharge'];?></cite></p>元</span></div>
                                    </div>
                                    <div class="x-admin-backlog-body">
                                        <div>今日: 系统增发</div>
                                        <div><span>RMB:<p style="display: inline-block"><cite><?php echo $data['todayData']['system_recharge'];?></cite></p>元</span></div>
                                    </div>
                                </li>

                                <li class="layui-col-md3">
                                    <div class="x-admin-backlog-body">
                                        <div>今日: 元宝消耗</div>
                                        <div><span><p style="display: inline-block"><cite><?php echo $data['todayData']['consume'];?></cite></p>元宝</span></div>
                                    </div>
                                    <div class="x-admin-backlog-body">
                                        <div>今日: 一级代理分成</div>
                                        <div><span>RMB:<p style="display: inline-block"><cite><?php echo $data['todayData']['player_pay'];?></cite></p>元</span></div>
                                    </div>
                                    <div class="x-admin-backlog-body">
                                        <div>今日: 元宝淤积</div>
                                        <div><span><p style="display: inline-block"><cite><?php echo $data['todayData']['deposit'];?></cite></p>元宝（robot：<?php echo $data['todayData']['robot'];?>元宝）</span></div>
                                    </div>
                                </li>

                                <li class="layui-col-md3">
                                    <div class="x-admin-backlog-body">
                                        <div>今日: T返利</div>
                                        <div><span>RMB:<p style="display: inline-block"><cite><?php echo $data['todayData']['agent_ti'];?></cite></p>元</span></div>
                                    </div>
                                    <div class="x-admin-backlog-body">
                                        <div>可TX金额</div>
                                        <div><span>RMB:<p style="display: inline-block"><cite><?php echo $data['todayData']['daili_ti'];?></cite></p>元</span></div>
                                    </div>
                                    <div class="x-admin-backlog-body">
                                        <div>历史TX</div>
                                        <div><span>RMB:<p style="display: inline-block"><cite><?php echo $data['todayData']['all_daili_ti'];?></cite></p>元</span></div>
                                    </div>
								</li>

                                <li class="layui-col-md3">
                                    <div class="x-admin-backlog-body">
                                        <div>今日: 库收入</div>
                                        <div><span><p style="display: inline-block"><cite><?php echo $data['todayData']['income_gold'];?></cite></p>元宝</span></div>
                                    </div>
                                    <div class="x-admin-backlog-body">
                                        <div>监控：输入+输出=异常</div>
                                        <div><span><p style="display: inline-block"><cite><?php echo $data['todayData']['input'];?></cite></p>元宝</span><span><p style="display: inline-block"><cite><?php echo $data['todayData']['output'];?></cite></p>元宝</span><span><p style="display: inline-block"><cite><?php echo $data['todayData']['result'];?></cite></p>元宝</span></div>
                                    </div>
                                </li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</fieldset>

	<div class="layui-tab">
		<ul class="layui-tab-title tabs">
			<li class="layui-this">实时在线人数</li>
			<li id="li2">新增用户数</li>
			<li id="li3">活跃用户</li>
			<li id="li4">充值额度</li>
		</ul>
		<div id="charts" style="width:100%;height:400px;">
			<div id="main1" class="layui-col-sm4" style="width:25%;height:400px;"></div>
			<div id="main2" class="layui-col-sm8" style="width:65%;height:400px;"></div>
			<div id="main" style="width:90%;height:400px;"></div>
		</div>
	</div>
</div>
<script src="https://cdn.bootcss.com/echarts/3.3.2/echarts.min.js" charset="utf-8"></script>
<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('main'));
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
                //console.log(data[0]);
                var option = {
                    title: {
                        text: '在线人数统计图',
                        subtext:'非规律性时间内的变化'
                    },
                    tooltip: {
                        trigger: 'axis',
                        // verticalAlign:right
                    },
                    legend: {
                        data:['今日在线人数','昨日在线人数','前日在线人数','四日在线人数','五日在线人数','六日在线人数','七日在线人数'],
                        selected : {
                            '前日在线人数': false,
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
                        data: data[1].time
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

</script>

</body>
</html>