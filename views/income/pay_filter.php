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
    .help1,.load{display: inline-block;float: right;position:relative;cursor: pointer;}
    .helpText1{padding:0 5px;display: none;position:absolute;top:10px;right:20px;width:300px;height:200px;overflow: auto;background-color: #000;color:#FFF;border-radius: 5px;z-index: 9;opacity: 0.5;}
    .listIcon li{border:1px solid #A2A2A2;border-radius: 5px;padding: 5px;display:inline;margin:5px;cursor: pointer;list-style: none;float:left;}
    .listIcon{padding:10px;}
    .item .layui-icon{font-size:20px!important;color:#A2A2A2;}
    .tabs2{display:none;}
    .tabIcons{position: absolute;left:10px;bottom:10px;}
    .itemPare{display:flex;justify-content: space-around;width:98%;}
    .itemPare .item{margin:5px;box-sizing: border-box;}
    .help1,.help2,.help3,.help4,.help5,.help6{display: inline-block;float: right;position:relative;}
    .helpText1,.helpText2,.helpText3,.helpText4,.helpText5,.helpText6{padding:0 5px;display: none;position:absolute;top:10px;right:20px;width:300px;height:200px;overflow: auto;background-color: #000;color:#FFF;border-radius: 5px;z-index: 9;opacity: 0.5;}
    .list1 .layui-table-box,.list1 .layui-table-view{margin:20px;}
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">收入分析</a>
            <a>
                <cite>付费渗透</cite>
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
            <span>付费率</span>
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
                    <li class="layui-this">日付费率</li>
                    <li>周付费率</li>
                    <li>月付费率</li>
                </ul>
                <div class="containers" id="tabcharcont1">
                    <div class="containerItem" id="list1-container1" style="width: 100%;height:300px;"></div>
                    <div class="containerItem" id="list1-container2" style="width: 100%;height:300px;"></div>
                    <div class="containerItem" id="list1-container3" style="width: 100%;height:300px;"></div>
                </div>
            </div>
            <!--表-->
            <div class="layui-tab tabs tabs2" >
                <ul class="layui-tab-title " id="tabtable1" >
                    <li class="layui-this">日付费率</li>
                    <li>周付费率</li>
                    <li>月付费率</li>
                </ul>
                <div class="list1Icon" >
                    <div class="containers" id="tabtablecont1">
                        <div class="containerItem"  style="">
                            <table class="layui-table" id="list1-table1"></table>
                        </div>
                        <div class="containerItem"  style="display:none">
                            <table class="layui-table" id="list1-table2"></table>
                        </div>
                        <div class="containerItem"  style="display:none">
                            <table class="layui-table" id="list1-table3"></table>
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
    <div class="item list2" >
        <div class="itemTitle">
            <span>ARPU</span>
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
            <div class="layui-tab tabs">
                <ul class="layui-tab-title " id="tabchar2" >
                    <li class="layui-this">ARPU（日）</li>
                    <li>ARPU（月）</li>
                    <li>ARPPU（日）</li>
                    <li>ARPPU（月）</li>
                </ul>
                <div class="containers" id="tabcharcont2">
                    <div class="containerItem" id="list2-container1" style="width: 100%;height:300px;"></div>
                    <div class="containerItem" id="list2-container2" style="width: 100%;height:300px;"></div>
                    <div class="containerItem" id="list2-container3" style="width: 100%;height:300px;"></div>
                    <div class="containerItem" id="list2-container4" style="width: 100%;height:300px;"></div>
                </div>
            </div>
            <!--表-->
            <div class="layui-tab tabs tabs2" >
                <ul class="layui-tab-title " id="tabtable2" >
                    <li class="layui-this">ARPU（日）</li>
                    <li>ARPU（月）</li>
                    <li>ARPPU（日）</li>
                    <li>ARPPU（月）</li>
                </ul>
                <div class="list1Icon" >
                    <div class="containers" id="tabtablecont2">
                        <div class="containerItem" >
                            <table class="layui-table" id="list2-table1"></table>
                        </div>
                        <div class="containerItem"  style="display: none">
                            <table class="layui-table" id="list2-table2"></table>
                        </div>
                        <div class="containerItem"  style="display: none">
                            <table class="layui-table" id="list2-table3"></table>
                        </div>
                        <div class="containerItem"  style="display: none">
                            <table class="layui-table" id="list2-table4"></table>
                        </div>
                    </div>
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
    <div class="itemPare">
        <div class="item list3" >
            <div class="itemTitle">
                <span>ARPU</span>
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
                <div class="layui-tab tabs">
                    <ul class="layui-tab-title " id="tabchar3" >
                        <li class="layui-this">日均付费率</li>
                        <li>日均ARPU</li>
                        <li>日均ARPPU</li>
                    </ul>
                    <div class="containers" id="tabcharcont3">
                        <div class="containerItem" id="list3-container1" style="width: 100%;height:300px;"></div>
                        <div class="containerItem" id="list3-container2" style="width: 100%;height:300px;"></div>
                        <div class="containerItem" id="list3-container3" style="width: 100%;height:300px;"></div>
                    </div>
                </div>
                <!--表-->
                <div class="layui-tab tabs tabs2" >
                    <ul class="layui-tab-title " id="tabtable3" >
                        <li class="layui-this">日均付费率</li>
                        <li>日均ARPU</li>
                        <li>日均ARPPU</li>
                    </ul>
                    <div class="list1Icon" >
                        <div class="containers" id="tabtablecont3">
                            <div class="containerItem" >
                                <table class="layui-table" id="list3-table1"></table>
                            </div>
                            <div class="containerItem"  style="display: none">
                                <table class="layui-table" id="list3-table2"></table>
                            </div>
                            <div class="containerItem"  style="display: none">
                                <table class="layui-table" id="list3-table3"></table>
                            </div>
                        </div>
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
        <div class="item list4" >
            <div class="itemTitle">
                <span>ARPU</span>
                <div class="help4">
                    <span class="helpIcon layui-icon">&#xe607;</span>
                    <div class="helpText4">
                        <div class="helpTextTitle">数据指标说明</div>
                    </div>
                </div>
                <div class="load">
                    <span class="layui-icon" style="font-weight: bold">&#xe601;</span>
                </div>
            </div>
            <div id="tabcontent4">
                <!--图-->
                <div class="layui-tab tabs">
                    <ul class="layui-tab-title " id="tabchar4" >
                        <li class="layui-this">日均付费率</li>
                        <li>日均ARPU</li>
                        <li>日均ARPPU</li>
                    </ul>
                    <div class="containers" id="tabcharcont4">
                        <div class="containerItem" id="list4-container1" style="width: 100%;height:300px;"></div>
                        <div class="containerItem" id="list4-container2" style="width: 100%;height:300px;"></div>
                        <div class="containerItem" id="list4-container3" style="width: 100%;height:300px;"></div>
                    </div>
                </div>
                <!--表-->
                <div class="layui-tab tabs tabs2" >
                    <ul class="layui-tab-title " id="tabtable4" >
                        <li class="layui-this">日均付费率</li>
                        <li>日均ARPU</li>
                        <li>日均ARPPU</li>
                    </ul>
                    <div class="list1Icon" >
                        <div class="containers" id="tabtablecont4">
                            <div class="containerItem" >
                                <table class="layui-table" id="list4-table1"></table>
                            </div>
                            <div class="containerItem" style="display: none">
                                <table class="layui-table" id="list4-table2"></table>
                            </div>
                            <div class="containerItem" style="display: none">
                                <table class="layui-table" id="list4-table3"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tabIcons">
                <ul class="listIcon" id="tab4">
                    <li><i class="layui-icon">&#xe629;</i></li>
                    <li><i class="layui-icon">&#xe62d;</i></li>
                </ul>

            </div>
        </div>
    </div>
    <div class="itemPare">
        <div class="item list5" >
            <div class="itemTitle">
                <span>ARPU</span>
                <div class="help5">
                    <span class="helpIcon layui-icon">&#xe607;</span>
                    <div class="helpText5">
                        <div class="helpTextTitle">数据指标说明</div>
                    </div>
                </div>
                <div class="load">
                    <span class="layui-icon" style="font-weight: bold">&#xe601;</span>
                </div>
            </div>
            <div id="tabcontent5">
                <!--图-->
                <div class="layui-tab tabs">
                    <ul class="layui-tab-title " id="tabchar5" >
                        <li class="layui-this">日均付费率</li>
                        <li>日均ARPU</li>
                        <li>日均ARPPU</li>
                    </ul>
                    <div class="containers" id="tabcharcont5">
                        <div class="containerItem" id="list5-container1" style="width: 100%;height:300px;"></div>
                        <div class="containerItem" id="list5-container2" style="width: 100%;height:300px;"></div>
                        <div class="containerItem" id="list5-container3" style="width: 100%;height:300px;"></div>
                    </div>
                </div>
                <!--表-->
                <div class="layui-tab tabs tabs2" >
                    <ul class="layui-tab-title " id="tabtable5" >
                        <li class="layui-this">日均付费率</li>
                        <li>日均ARPU</li>
                        <li>日均ARPPU</li>
                    </ul>
                    <div class="list1Icon" >
                        <div class="containers" id="tabtablecont5">
                            <div class="containerItem" >
                                <table class="layui-table" id="list5-table1"></table>
                            </div>
                            <div class="containerItem" style="display: none">
                                <table class="layui-table" id="list5-table2"></table>
                            </div>
                            <div class="containerItem" style="display: none">
                                <table class="layui-table" id="list5-table3"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tabIcons">
                <ul class="listIcon" id="tab5">
                    <li><i class="layui-icon">&#xe629;</i></li>
                    <li><i class="layui-icon">&#xe62d;</i></li>
                </ul>

            </div>
        </div>
        <div class="item list6" >
            <div class="itemTitle">
                <span>ARPU</span>
                <div class="help6">
                    <span class="helpIcon layui-icon">&#xe607;</span>
                    <div class="helpText6">
                        <div class="helpTextTitle">数据指标说明</div>
                    </div>
                </div>
                <div class="load">
                    <span class="layui-icon" style="font-weight: bold">&#xe601;</span>
                </div>
            </div>
            <div id="tabcontent6">
                <!--图-->
                <div class="layui-tab tabs">
                    <ul class="layui-tab-title " id="tabchar6" >
                        <li class="layui-this">日均付费率</li>
                        <li>日均ARPU</li>
                        <li>日均ARPPU</li>
                    </ul>
                    <div class="containers" id="tabcharcont6">
                        <div class="containerItem" id="list6-container1" style="width: 100%;height:300px;"></div>
                        <div class="containerItem" id="list6-container2" style="width: 100%;height:300px;"></div>
                        <div class="containerItem" id="list6-container3" style="width: 100%;height:300px;"></div>
                    </div>
                </div>
                <!--表-->
                <div class="layui-tab tabs tabs2" >
                    <ul class="layui-tab-title " id="tabtable6" >
                        <li class="layui-this">日均付费率</li>
                        <li>日均ARPU</li>
                        <li>日均ARPPU</li>
                    </ul>
                    <div class="list1Icon" >
                        <div class="containers" id="tabtablecont6">
                            <div class="containerItem" >
                                <table class="layui-table" id="list6-table1"></table>
                            </div>
                            <div class="containerItem" style="display: none">
                                <table class="layui-table" id="list6-table2"></table>
                            </div>
                            <div class="containerItem" style="display: none">
                                <table class="layui-table" id="list6-table3"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tabIcons">
                <ul class="listIcon" id="tab6">
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
        //鼠标悬浮帮助icon
        function help(help,helpText){
            var help1 = document.getElementsByClassName(help)[0];
            var helpText1 = document.getElementsByClassName(helpText)[0];
            $(help1).mouseover(function () {
                $(helpText1).css("display","block");
            })
            $(help1).mouseout(function () {
                $(helpText1).css("display","none");
            })
        }
        //切换图表和图形，切换图表的tab
        function tabs(listIcon,tabContent){
            var liPar = document.getElementById(listIcon);
            var divPar = document.getElementById(tabContent);
            var lis = $(liPar).find('li');
            var divs = $(divPar ).children();
            for (var i=0;i<lis.length;i++){
                $(lis[i]).click(function () {
                    var i = $(this).index();
                    console.log(i)
                    $(divs).hide();
                    $(divs[i]).show();
                })
            }
        }

        function mychart2(url,name) {
            $.ajax({
                type: 'get',
                url: url,
                success: function(res) {
                    option = {
                        title: {
                            text: ''
                        },
                        tooltip : {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross',
                                label: {
                                    backgroundColor: '#6a7985'
                                }
                            }
                        },
                        legend: {
                            data:['','','','','']
                        },
                        toolbox: {
                            feature: {
                                saveAsImage: {}
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
                                boundaryGap : false,
                                data : ['周一','周二','周三','周四','周五','周六','周日']
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'邮件营销',
                                type:'line',
                                stack: '总量',
                                areaStyle: {normal: {}},
                                data:[120, 132, 101, 134, 90, 230, 210]
                            },
                            // {
                            //     name:'联盟广告',
                            //     type:'line',
                            //     stack: '总量',
                            //     areaStyle: {normal: {}},
                            //     data:[220, 182, 191, 234, 290, 330, 310]
                            // },
                            // {
                            //     name:'视频广告',
                            //     type:'line',
                            //     stack: '总量',
                            //     areaStyle: {normal: {}},
                            //     data:[150, 232, 201, 154, 190, 330, 410]
                            // },
                            // {
                            //     name:'直接访问',
                            //     type:'line',
                            //     stack: '总量',
                            //     areaStyle: {normal: {}},
                            //     data:[320, 332, 301, 334, 390, 330, 320]
                            // },
                            // {
                            //     name:'搜索引擎',
                            //     type:'line',
                            //     stack: '总量',
                            //     label: {
                            //         normal: {
                            //             show: true,
                            //             position: 'top'
                            //         }
                            //     },
                            //     areaStyle: {normal: {}},
                            //     data:[820, 932, 901, 934, 1290, 1330, 1320]
                            // }
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
                            text: '',
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

        help('help1','helpText1');
        help('help2','helpText2');
        help('help3','helpText3');
        help('help4','helpText4');
        help('help5','helpText5');
        help('help6','helpText6');


        tabs('tab1','tabcontent1');
        tabs('tabtable1','tabtablecont1');
        tabs('tab2','tabcontent2');
        tabs('tabtable2','tabtablecont2');
        tabs('tab3','tabcontent3');
        tabs('tabtable3','tabtablecont3');
        tabs('tab4','tabcontent4');
        tabs('tabtable4','tabtablecont4');
        tabs('tab5','tabcontent5');
        tabs('tabtable5','tabtablecont5');
        tabs('tab6','tabcontent6');
        tabs('tabtable6','tabtablecont6');


        var list1Chart1 = echarts.init(document.getElementById('list1-container1'));
        var list1Chart2 = echarts.init(document.getElementById('list1-container2'));
        var list1Chart3 = echarts.init(document.getElementById('list1-container3'));

        var list2Chart1 = echarts.init(document.getElementById('list2-container1'));
        var list2Chart2 = echarts.init(document.getElementById('list2-container2'));
        var list2Chart3 = echarts.init(document.getElementById('list2-container3'));
        var list2Chart4 = echarts.init(document.getElementById('list2-container4'));

        var list3Chart1 = echarts.init(document.getElementById('list3-container1'));
        var list3Chart2 = echarts.init(document.getElementById('list3-container2'));
        var list3Chart3 = echarts.init(document.getElementById('list3-container3'));

        var list4Chart1 = echarts.init(document.getElementById('list4-container1'));
        var list4Chart2 = echarts.init(document.getElementById('list4-container2'));
        var list4Chart3 = echarts.init(document.getElementById('list4-container3'));

        var list5Chart1 = echarts.init(document.getElementById('list5-container1'));
        var list5Chart2 = echarts.init(document.getElementById('list5-container2'));
        var list5Chart3 = echarts.init(document.getElementById('list5-container3'));

        var list6Chart1 = echarts.init(document.getElementById('list6-container1'));
        var list6Chart2 = echarts.init(document.getElementById('list6-container2'));
        var list6Chart3 = echarts.init(document.getElementById('list6-container3'));

        mychart2('/test/t201',list1Chart1);
        mychart2('/test/t201',list2Chart1);
        bar('/test/t201',list3Chart1);
        bar('/test/t201',list4Chart1);
        caky('/test/t201',list5Chart1);
        bar('/test/t201',list6Chart1);

        //图表的li切换功能，参数：图表对应的tab的ID，需要渲染的图表的名称，cavas的ID
        function charTabLine1(tabchar1,char1,char2,char3,list3Container1,list3Container2,list3Container3){
            var tabchar = document.getElementById(tabchar1);
            var lis1 =$(tabchar).find('li:first');
            var lis2 =$(tabchar).find('li:eq(1)');
            var lis3 =$(tabchar).find('li:eq(2)');
            var listContainer1 = document.getElementById(list3Container1);
            var listContainer2 = document.getElementById(list3Container2);
            var listContainer3 = document.getElementById(list3Container3);
            lis1.click(function () {
                $(listContainer2).hide();
                $(listContainer3).hide();
                $(listContainer1).show();
                mychart2('/test/t201',char1);
            });
            lis2.click(function () {
                $(listContainer3).hide();
                $(listContainer1).hide();
                $(listContainer2).show();
                mychart2('/test/t201',char2);
            });
            lis3.click(function () {
                $(listContainer2).hide();
                $(listContainer1).hide();
                $(listContainer3).show();
                mychart2('/test/t201',char3);
            });

        }
        function charTabLine(tabchar1,char1,char2,char3,char4,list3Container1,list3Container2,list3Container3,list3Container4){
            var tabchar = document.getElementById(tabchar1);
            var lis1 =$(tabchar).find('li:first');
            var lis2 =$(tabchar).find('li:eq(1)');
            var lis3 =$(tabchar).find('li:eq(2)');
            var lis4 =$(tabchar).find('li:eq(3)');
            var listContainer1 = document.getElementById(list3Container1);
            var listContainer2 = document.getElementById(list3Container2);
            var listContainer3 = document.getElementById(list3Container3);
            var listContainer4 = document.getElementById(list3Container4);
            lis1.click(function () {
                $(listContainer4).hide();
                $(listContainer2).hide();
                $(listContainer3).hide();
                $(listContainer1).show();
                mychart2('/test/t201',char1);
            });
            lis2.click(function () {
                $(listContainer4).hide();
                $(listContainer3).hide();
                $(listContainer1).hide();
                $(listContainer2).show();
                // $(listContainer3).css('visibility','hidden');
                // $(listContainer1).css('visibility','hidden');
                // $(listContainer2).css('visibility','visible');
                mychart2('/test/t201',char2);
            });
            lis3.click(function () {
                $(listContainer4).hide();
                $(listContainer2).hide();
                $(listContainer1).hide();
                $(listContainer3).show();
                mychart2('/test/t201',char3);
            });
            lis4.click(function () {
                $(listContainer3).hide();
                $(listContainer2).hide();
                $(listContainer1).hide();
                $(listContainer4).show();
                mychart2('/test/t201',char4);
            });
        }
        function charTabBar(tabchar1,char1,char2,char3,list3Container1,list3Container2,list3Container3){
            var tabchar = document.getElementById(tabchar1);
            var lis1 =$(tabchar).find('li:first');
            var lis2 =$(tabchar).find('li:eq(1)');
            var lis3 =$(tabchar).find('li:eq(2)');
            var lis4 =$(tabchar).find('li:eq(3)');
            var lis5 =$(tabchar).find('li:eq(4)');
            var listContainer1 = document.getElementById(list3Container1);
            var listContainer2 = document.getElementById(list3Container2);
            var listContainer3 = document.getElementById(list3Container3);
            lis1.click(function () {
                $(listContainer2).hide();
                $(listContainer3).hide();
                $(listContainer1).show();
                bar('/test/t201',char1);
            });
            lis2.click(function () {
                $(listContainer3).hide();
                $(listContainer1).hide();
                $(listContainer2).show();
                bar('/test/t201',char2);
            });
            lis3.click(function () {
                $(listContainer2).hide();
                $(listContainer1).hide();
                $(listContainer3).show();
                bar('/test/t201',char3);
            });
        }
        function charTabCaky(tabchar1,char1,char2,char3,list3Container1,list3Container2,list3Container3){
            var tabchar = document.getElementById(tabchar1);
            var lis1 =$(tabchar).find('li:first');
            var lis2 =$(tabchar).find('li:eq(1)');
            var lis3 =$(tabchar).find('li:eq(2)');
            var lis4 =$(tabchar).find('li:eq(3)');
            var lis5 =$(tabchar).find('li:eq(4)');
            var listContainer1 = document.getElementById(list3Container1);
            var listContainer2 = document.getElementById(list3Container2);
            var listContainer3 = document.getElementById(list3Container3);
            lis1.click(function () {
                $(listContainer2).hide();
                $(listContainer3).hide();
                $(listContainer1).show();
                caky('/test/t201',char1);
            });
            lis2.click(function () {
                $(listContainer3).hide();
                $(listContainer1).hide();
                $(listContainer2).show();
                // $(listContainer3).css('visibility','hidden');
                // $(listContainer1).css('visibility','hidden');
                // $(listContainer2).css('visibility','visible');
                caky('/test/t201',char2);
            });
            lis3.click(function () {
                $(listContainer2).hide();
                $(listContainer1).hide();
                $(listContainer3).show();
                caky('/test/t201',char3);
            });
        }


        charTabLine1('tabchar1',list1Chart1,list1Chart2,list1Chart3,'list1-container1','list1-container2','list1-container3');
        charTabLine('tabchar2',list2Chart1,list2Chart2,list2Chart3,list2Chart4,'list2-container1','list2-container2','list2-container3','list2-container4');
        charTabBar('tabchar3',list3Chart1,list3Chart2,list3Chart3,'list3-container1','list3-container2','list3-container3');
        charTabBar('tabchar4',list4Chart1,list4Chart2,list4Chart3,'list4-container1','list4-container2','list4-container3');
        charTabCaky('tabchar5',list5Chart1,list5Chart2,list5Chart3,'list5-container1','list5-container2','list5-container3');
        charTabBar('tabchar6',list6Chart1,list6Chart2,list6Chart3,'list6-container1','list6-container2','list6-container3');

        //table渲染
        function tableRender(tableID,url,field1,title1,field2,title2){
            table.render({
                elem:"#"+tableID
                ,url:url
                ,cols:[[
                    {field:field1,title:title1}
                    ,{field:field2,title:title2}
                ]]
            });
        }
        tableRender('list1-table1','/test/t206','age','日期','num','日付费率（%）')
        tableRender('list1-table2','/test/t205','age','日期','num','日付费率（%）')
        tableRender('list1-table3','/test/t204','age','日期','num','日付费率（%）')

        tableRender('list2-table1','/test/t206','age','日期','num','￥ARPPU（日）')
        tableRender('list2-table2','/test/t205','age','日期','num','￥ARPPU（日）')
        tableRender('list2-table3','/test/t204','age','日期','num','￥ARPPU（日）')
        tableRender('list2-table4','/test/t205','age','日期','num','￥ARPPU（日）')


        tableRender('list3-table1','/test/t206','age','地区','num','日均ARPPU（￥）')
        tableRender('list3-table2','/test/t205','age','地区','num','日均ARPPU（￥）')
        tableRender('list3-table3','/test/t204','age','地区','num','日均ARPPU（￥）')

        tableRender('list4-table1','/test/t206','age','渠道','num','日均付费率（%）')
        tableRender('list4-table2','/test/t205','age','渠道','num','日均付费率（%）')
        tableRender('list4-table3','/test/t204','age','渠道','num','日均付费率（%）')



        tableRender('list5-table1','/test/t206','age','性别','num','百分比')
        tableRender('list5-table2','/test/t205','age','性别','num','百分比')
        tableRender('list5-table3','/test/t204','age','性别','num','百分比')

        tableRender('list6-table1','/test/t206','age','年龄','num','日均付费率1（%）')
        tableRender('list6-table2','/test/t205','age','年龄','num','日均付费率2（%）')
        tableRender('list6-table3','/test/t204','age','年龄','num','日均付费率3（%）')

    })
</script>