<style>
    /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
    .listIcon ul{list-style: none}

    .tab2{display:none;}
    .item{
        width:98%;
        height:500px;
        border:1px solid #EEEEEE;
        margin-bottom: 20px;
        /*padding:0 15px;*/
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        overflow:hidden;
        position:relative;
    }
    .itemTitle{
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        height:40px;
        background-color: #eeeeee;
        color:#63666D;
        padding:0 10px;
        line-height: 40px;
    }
    .tabs{ margin-left: 20px;}
    .layui-table,.layui-table-view{margin:0!important;}
    hr{margin:20px 0;}
    /*.load{display: inline-block;float: right;}*/
    .help1,.load{display: inline-block;float: right;position:relative;cursor: pointer;}
    .helpText1{padding:0 5px;display: none;position:absolute;top:10px;right:20px;width:300px;height:200px;overflow: auto;background-color: #000;color:#FFF;border-radius: 5px;z-index: 9;opacity: 0.5;}
    .listIcon li{border:1px solid #A2A2A2;border-radius: 5px;padding: 5px;display:inline;margin:5px;cursor: pointer;list-style: none;float:left;}
    .listIcon{padding:10px;}
    .item .layui-icon{font-size:20px!important;color:#A2A2A2;}
    .tabs2{display:none;}
    .tabIcons{position: absolute;left:10px;bottom:10px;}
    .itemPare{display:flex;justify-content: space-around;width:98%;}
    .itemPare .item{margin:5px;box-sizing: border-box;}
    .help1,.help2,.help3{display: inline-block;float: right;position:relative;}
    .helpText1,.helpText2,.helpText3{padding:0 5px;display: none;position:absolute;top:10px;right:20px;width:300px;height:200px;overflow: auto;background-color: #000;color:#FFF;border-radius: 5px;z-index: 9;opacity: 0.5;}
    .list1 .layui-table-box,.list1 .layui-table-view{margin:20px;}
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">玩家分析</a>
            <a>
                <cite>新增玩家</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">

    <form action="" class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="start_time1" placeholder="开始时间">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="end_time1" placeholder="结束时间">
            </div>
            <div class="layui-btn"  data-type="search" id="search1"><i class="layui-icon">&#xe615;</i></div>
        </div>
    </form>
    <div class="item list1" >
        <div class="itemTitle">
            <span>新增玩家</span>
            <div class="help1">
                <span class="helpIcon layui-icon">&#xe607;</span>
                <div class="helpText1">
                    <div class="helpTextTitle">数据指标说明</div>
                </div>
            </div>
            <div class="load">
                <span class="layui-icon" style="font-weight: bold">&#xe601;</span>
            </div>
        </div>
        <div id="tabcontent1">
            <!--图-->
            <div class="layui-tab tabs">
                <ul class="layui-tab-title " id="tabchar1" >
                    <li class="layui-this">新增激活和账户</li>
                    <li>玩家转换</li>
                </ul>
                <div class="containers" id="tabcharcont1">
                    <div class="containerItem" id="container1" style="width: 100%;height:300px;"></div>
                    <div class="containerItem" id="container2" style="width: 100%;height:300px;"></div>
                </div>
            </div>
            <!--表-->
            <div class="layui-tab tabs tabs2" >
                <ul class="layui-tab-title " id="tabtable1" >
                    <li class="layui-this">新增激活和账户</li>
                    <li>玩家转换</li>

                </ul>
                <div class="list1Icon" >
                    <div class="containers" id="tabtablecont1">
                        <div class="containerItem"  style="">
                            111
                            <table class="layui-table" id="DCU"></table>
                        </div>
                        <div class="containerItem"  style="display:none">
                            222
                            <table class="layui-table" id="WAU"></table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="tabIcons">
            <ul class="listIcon" id="tab1">
                <li><i class="layui-icon">&#xe629;</i></li>
                <li><i class="layui-icon">&#xe62d;</i></li>
            </ul>

        </div>
    </div>
    <div class="itemPare">
        <div class="item" >
            <div class="itemTitle">
                <span>玩家性别</span>
                <div class="help2">
                    <span class="helpIcon layui-icon">&#xe607;</span>
                    <div class="helpText2">
                        <div class="helpTextTitle">数据指标说明</div>
                    </div>
                </div>
                <div class="load">
                    <span class="layui-icon" style="font-weight: bold">&#xe601;</span>
                </div>

            </div>
            <div id="tabcontent2">
                <!--图-->
                <div class=" tabs">

                    <div class="containers" >
                        <div class="containerItem" id="container5" style="width: 100%;height:300px;"></div>

                    </div>
                </div>
                <!--表-->
                <div class="containers" style="display: none">
                    <div class="containerItem"  style="">
                        <table class="layui-table" id="activePlayer"></table>
                    </div>
                </div>
            </div>
            <div class="tabIcons">
                <ul class="listIcon" id="tab2">
                    <li><i class="layui-icon">&#xe629;</i></li>
                    <li><i class="layui-icon">&#xe62d;</i></li>
                </ul>
            </div>
        </div>
        <div class="item" >
            <div class="itemTitle">
                <span>地区分布</span>
                <div class="help3">
                    <span class="helpIcon layui-icon">&#xe607;</span>
                    <div class="helpText3">
                        <div class="helpTextTitle">数据指标说明</div>
                    </div>
                </div>
                <div class="load">
                    <span class="layui-icon" style="font-weight: bold">&#xe601;</span>
                </div>
            </div>
            <div id="tabcontent3">
                <!--图-->
                <div class=" tabs">

                    <div class="containers" >
                        <div class="containerItem" id="container6" style="width: 100%;height:300px;"></div>

                    </div>
                </div>
                <!--表-->
                <div class="containers" style="display: none">
                    <div class="containerItem"  style="">
                        <table class="layui-table" id="activePlayer2"></table>
                    </div>
                </div>
            </div>
            <div class="tabIcons">
                <ul class="listIcon" id="tab3">
                    <li><i class="layui-icon">&#xe629;</i></li>
                    <li><i class="layui-icon">&#xe62d;</i></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>
<script src="//cdn.bootcss.com/echarts/3.3.2/echarts.min.js" charset="utf-8"></script>
<script>
    layui.use(['table','layer','laydate'],function () {
        var $ = layui.$;
        var table = layui.table;
        var laydate = layui.laydate;
        laydate.render({elem: '#start_time1'});
        laydate.render({elem: '#end_time1'});

        $(".help1").mouseover(function () {
            $('.helpText1').css("display","block");
        })
        $(".help1").mouseout(function () {
            $('.helpText1').css("display","none");
        })
        $(".help2").mouseover(function () {
            $('.helpText2').css("display","block");
        })
        $(".help2").mouseout(function () {
            $('.helpText2').css("display","none");
        })
        $(".help3").mouseover(function () {
            $('.helpText3').css("display","block");
        })
        $(".help3").mouseout(function () {
            $('.helpText3').css("display","none");
        })


        function tabs(listIcon,tabContent){
            var liPar = document.getElementById(listIcon);
            var divPar = document.getElementById(tabContent);
            var lis = $(liPar).find('li');
            var divs = $(divPar ).children();
            for (var i=0;i<lis.length;i++){
                $(lis[i]).click(function () {
                    var i = $(this).index();
                    console.log(i)
                    // debugger
                    // $(divs).css('display', 'none');
                    // $(divs[i]).css('display', 'block');
                    $(divs).hide();
                    $(divs[i]).show();
                })
            }
        }

        tabs('tab1','tabcontent1');
        tabs('tabchar1','tabcharcont1');
        tabs('tabtable1','tabtablecont1');
        tabs('tab2','tabcontent2');
        tabs('tab3','tabcontent3');

        var myChart1 = echarts.init(document.getElementById('container1'));
        var myChart2 = echarts.init(document.getElementById('container2'));
        var myChart5 = echarts.init(document.getElementById('container5'));
        var myChart6 = echarts.init(document.getElementById('container6'));
        function mychart2(url,name) {
            $.ajax({
                type: 'get',
                url: url,
                success: function(res) {
                    option = {
                        title: {
                            text: ' '
                        },
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['今天','昨天','七天前']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        // toolbox: {
                        //     feature: {
                        //         saveAsImage: {}
                        //     }
                        // },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: ['00:00','00:20','00:40','01:00','01:20','01:40','02:00',
                                '02:20','02:40','03:00','03:20','03:40','04:00', '04:20',
                                '04:40','05:00','05:20','05:40','06:00', '06:20','06:40',
                                '07:00','07:20','07:40','08:00', '08:20','08:40','09:00',
                                '09:20','09:40','10:00', '11:20','11:40','12:00','12:20',
                                '12:40','13:00', '13:20','13:40','14:00','14:20','14:40',
                                '15:00', '15:20','15:40','16:00','16:20','16:40','17:00',
                                '17:20','17:40','18:00','18:20','18:40','19:00', '19:20',
                                '19:40','20:00','21:20','21:40','22:00', '21:20','21:40',
                                '22:00','22:20','22:40','23:00', '23:20','23:40',]
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [
                            {
                                name:'今天',
                                type:'line',
                                stack: '总量',
                                data:[120, 132, 101, 134, 90, 230, 210]
                            },
                            {
                                name:'昨天',
                                type:'line',
                                stack: '总量',
                                data:[320, 332, 301, 334, 390, 330, 320]
                            },
                            {
                                name:'七天前',
                                type:'line',
                                stack: '总量',
                                data:[820, 932, 901, 934, 1290, 1330, 1320]
                            }
                        ]
                    };
                    name.setOption(option);
                }
            });
        }
        function caky(url,name) {
            $.ajax({
                type: 'get',
                url: url,
                success: function(res) {
                    option = {
                        title : {
                            text: '玩家性别',
                            x:'center'
                        },
                        tooltip : {
                            trigger: 'item',
                            formatter: "{a} <br/>{b} : {c} ({d}%)"
                        },
                        legend: {
                            orient: 'vertical',
                            left: 'left',
                            data: ['男','女']
                        },
                        series : [
                            {
                                name: '访问来源',
                                type: 'pie',
                                radius : '55%',
                                center: ['50%', '60%'],
                                data:[
                                    {value:335, name:'男'},
                                    {value:310, name:'女'},
                                ],
                                itemStyle: {
                                    emphasis: {
                                        shadowBlur: 10,
                                        shadowOffsetX: 0,
                                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                                    }
                                }
                            }
                        ]
                    };

                    name.setOption(option);
                }
            });
        }
        function bar(url,name) {
            $.ajax({
                type: 'get',
                url: url,
                success: function(res) {
                    option = {
                        color: ['#3398DB'],
                        title : {
                            text: '地区分布',
                            x:'center'
                        },
                        tooltip : {
                            trigger: 'axis',
                            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis : [
                            {
                                type : 'category',
                                data : ['北京', '上海', '广州', '深圳', '香港', '澳门', '台湾'],
                                axisTick: {
                                    alignWithLabel: true
                                }
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'直接访问',
                                type:'bar',
                                barWidth: '40%',
                                data:[10, 52, 200, 334, 390, 330, 220]
                            }
                        ]
                    };

                    name.setOption(option);
                }
            });
        }
        mychart2('/test/t201',myChart1);
        caky('/test/t201',myChart5);
        bar('/test/t201',myChart6);

        $("#tabchar1 li:first").click(function () {
            $('#container2').hide();
            $('#container3').hide();
            $('#container4').hide();
            $('#container1').show();
            mychart2('/test/t201',myChart1);
        });
        $("#tabchar1 li:eq(1)").click(function () {
            $('#container3').hide();
            $('#container4').hide();
            $('#container1').hide();
            $('#container2').show();
            mychart2('/test/t201',myChart2);
        });



        table.render({
            elem:"#DCU"
            ,url:"/test/t206"
            ,cols:[[
                {field:"number",title:"日期"}
                ,{field:"ID",title:"玩家转化"}
            ]]
        });
        table.render({
            elem:"#WAU"
            ,url:"/test/t206"
            ,cols:[[
                {field:"number",title:"日期"}
                ,{field:"ID",title:"玩家转化"}
            ]]
        });

        table.render({
            elem:"#activePlayer"
            ,url:"/test/t206"
            ,cols:[[
                {field:"number",title:"日期"}
                ,{field:"ID",title:"玩家转化"}
            ]]
        });
        table.render({
            elem:"#activePlayer2"
            ,url:"/test/t206"
            ,cols:[[
                {field:"number",title:"日期"}
                ,{field:"ID",title:"玩家转化"}
            ]]
        });
    })
</script>