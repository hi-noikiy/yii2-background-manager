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
                <cite>充值额度分析</cite>
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
    <div class="itemPare">
        <div class="item list3" >
            <div class="itemTitle">
                <span>充值方式</span>
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
                        <li class="layui-this">收费金额</li>
                        <li>充值人次</li>
                        <!--<li>30日内平均贡献</li>-->
                        <!--<li>60日内平均贡献</li>-->
                        <!--<li>90日内平均贡献</li>-->
                    </ul>
                    <div class="containers" id="tabcharcont3">
                        <div class="containerItem" id="list3-container1" style="width: 100%;height:300px;"></div>
                        <div class="containerItem" id="list3-container2" style="width: 100%;height:300px;"></div>
                        <!--<div class="containerItem" id="list3-container3" style="width: 100%;height:300px;"></div>-->
                        <!--<div class="containerItem" id="list3-container4" style="width: 100%;height:300px;"></div>-->
                        <!--<div class="containerItem" id="list3-container5" style="width: 100%;height:300px;"></div>-->
                    </div>
                </div>
                <!--表-->
                <div class="layui-tab tabs tabs2" >
                    <ul class="layui-tab-title " id="tabtable3" >
                        <li class="layui-this">收费金额</li>
                        <li>充值人次</li>
                        <!--<li>30日内平均贡献</li>-->
                        <!--<li>60日内平均贡献</li>-->
                        <!--<li>90日内平均贡献</li>-->
                    </ul>
                    <div class="list1Icon" >
                        <div class="containers" id="tabtablecont3">
                            <div class="containerItem" >
                                <table class="layui-table" id="list3-table1"></table>
                            </div>
                            <div class="containerItem"  style="display: none">
                                <table class="layui-table" id="list3-table2" ></table>
                            </div>
                            <!--<div class="containerItem"  style="display: none">-->
                            <!--<table class="layui-table" id="list3-table3"></table>-->
                            <!--</div>-->
                            <!--<div class="containerItem"  style="display: none">-->
                            <!--<table class="layui-table" id="list3-table4"></table>-->
                            <!--</div>-->
                            <!--<div class="containerItem"  style="display: none">-->
                            <!--<table class="layui-table" id="list3-table5"></table>-->
                            <!--</div>-->
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
                <span>消费包类型</span>
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
                        <li class="layui-this">收入金额</li>
                        <li>充值人次</li>
                    </ul>
                    <div class="containers" id="tabcharcont4">
                        <div class="containerItem" id="list4-container1" style="width: 100%;height:300px;"></div>
                        <div class="containerItem" id="list4-container2" style="width: 100%;height:300px;"></div>
                    </div>
                </div>
                <!--表-->
                <div class="layui-tab tabs tabs2" >
                    <ul class="layui-tab-title " id="tabtable4" >
                        <li class="layui-this">收入金额</li>
                        <li>充值人次</li>
                    </ul>
                    <div class="list1Icon" >
                        <div class="containers" id="tabtablecont4">
                            <div class="containerItem" >
                                <table class="layui-table" id="list4-table1"></table>
                            </div>
                            <div class="containerItem" style="display: none">
                                <table class="layui-table" id="list4-table2"></table>
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
    <div class="item list1" >
        <div class="itemTitle">
            <span>充值频次和额度</span>
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
                    <li class="layui-this">每周充值次数</li>
                    <li>每月充值次数</li>
                    <li>每周充值额度</li>
                    <li>每月充值额度</li>
                </ul>
                <div class="containers" id="tabcharcont1">
                    <div class="containerItem" id="list1-container1" style="width: 100%;height:300px;"></div>
                    <div class="containerItem" id="list1-container2" style="width: 100%;height:300px;"></div>
                    <div class="containerItem" id="list1-container3" style="width: 100%;height:300px;"></div>
                    <div class="containerItem" id="list1-container4" style="width: 100%;height:300px;"></div>
                </div>
            </div>
            <!--表-->
            <div class="layui-tab tabs tabs2" >
                <ul class="layui-tab-title " id="tabtable1" >
                    <li class="layui-this">每周充值次数</li>
                    <li>每月充值次数</li>
                    <li>每周充值额度</li>
                    <li>每月充值额度</li>
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
                        <div class="containerItem"  style="display:none">
                            <table class="layui-table" id="list1-table4"></table>
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
            <span>充值间隔</span>
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
                <!--<ul class="layui-tab-title " id="tabchar2" >-->
                    <!--<li class="layui-this">7日内平均贡献</li>-->
                    <!--<li>14日内平均贡献</li>-->
                    <!--<li>30日内平均贡献</li>-->
                    <!--<li>60日内平均贡献</li>-->
                    <!--<li>90日内平均贡献</li>-->
                <!--</ul>-->
                <div class="containers" id="tabcharcont2">
                    <div class="containerItem" id="list2-container1" style="width: 100%;height:300px;"></div>
                    <!--<div class="containerItem" id="list1-container2" style="width: 100%;height:300px;"></div>-->
                    <!--<div class="containerItem" id="list1-container3" style="width: 100%;height:300px;"></div>-->
                    <!--<div class="containerItem" id="list1-container4" style="width: 100%;height:300px;"></div>-->
                    <!--<div class="containerItem" id="list1-container5" style="width: 100%;height:300px;"></div>-->
                </div>
            </div>
            <!--表-->
            <div class="layui-tab tabs tabs2" >
                <!--<ul class="layui-tab-title " id="tabtable2" >-->
                    <!--<li class="layui-this">7日内平均贡献</li>-->
                    <!--<li>14日内平均贡献</li>-->
                    <!--<li>30日内平均贡献</li>-->
                    <!--<li>60日内平均贡献</li>-->
                    <!--<li>90日内平均贡献</li>-->
                <!--</ul>-->
                <div class="list1Icon" >
                    <div class="containers" id="tabtablecont2">
                        <div class="containerItem"  style="">
                            <table class="layui-table" id="list2-table1"></table>
                        </div>
                        <!--<div class="containerItem"  style="display:none">-->
                            <!--<table class="layui-table" id="list1-table2"></table>-->
                        <!--</div>-->
                        <!--<div class="containerItem"  style="display:none">-->
                            <!--<table class="layui-table" id="list1-table3"></table>-->
                        <!--</div>-->
                        <!--<div class="containerItem"  style="display:none">-->
                            <!--<table class="layui-table" id="list1-table4"></table>-->
                        <!--</div>-->
                        <!--<div class="containerItem"  style="display:none">-->
                            <!--<table class="layui-table" id="list1-table5"></table>-->
                        <!--</div>-->
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

    <!--<div class="itemPare">-->
        <!--<div class="item list5" >-->
            <!--<div class="itemTitle">-->
                <!--<span>ARPU</span>-->
                <!--<div class="help5">-->
                    <!--<span class="helpIcon layui-icon">&#xe607;</span>-->
                    <!--<div class="helpText5">-->
                        <!--<div class="helpTextTitle">数据指标说明</div>-->
                    <!--</div>-->
                <!--</div>-->
                <!--<div class="load">-->
                    <!--<span class="layui-icon" style="font-weight: bold">&#xe601;</span>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div id="tabcontent5">-->
                <!--&lt;!&ndash;图&ndash;&gt;-->
                <!--<div class="layui-tab tabs">-->
                    <!--<ul class="layui-tab-title " id="tabchar5" >-->
                        <!--<li class="layui-this">7日内平均贡献</li>-->
                        <!--<li>14日内平均贡献</li>-->
                        <!--<li>30日内平均贡献</li>-->
                        <!--<li>60日内平均贡献</li>-->
                        <!--<li>90日内平均贡献</li>-->
                    <!--</ul>-->
                    <!--<div class="containers" id="tabcharcont5">-->
                        <!--<div class="containerItem" id="list5-container1" style="width: 100%;height:300px;"></div>-->
                        <!--<div class="containerItem" id="list5-container2" style="width: 100%;height:300px;"></div>-->
                        <!--<div class="containerItem" id="list5-container3" style="width: 100%;height:300px;"></div>-->
                        <!--<div class="containerItem" id="list5-container4" style="width: 100%;height:300px;"></div>-->
                        <!--<div class="containerItem" id="list5-container5" style="width: 100%;height:300px;"></div>-->
                    <!--</div>-->
                <!--</div>-->
                <!--&lt;!&ndash;表&ndash;&gt;-->
                <!--<div class="layui-tab tabs tabs2" >-->
                    <!--<ul class="layui-tab-title " id="tabtable5" >-->
                        <!--<li class="layui-this">7日内平均贡献</li>-->
                        <!--<li>14日内平均贡献</li>-->
                        <!--<li>30日内平均贡献</li>-->
                        <!--<li>60日内平均贡献</li>-->
                        <!--<li>90日内平均贡献</li>-->
                    <!--</ul>-->
                    <!--<div class="list1Icon" >-->
                        <!--<div class="containers" id="tabtablecont5">-->
                            <!--<div class="containerItem" >-->
                                <!--<table class="layui-table" id="list5-table1"></table>-->
                            <!--</div>-->
                            <!--<div class="containerItem" style="display: none">-->
                                <!--<table class="layui-table" id="list5-table2"></table>-->
                            <!--</div>-->
                            <!--<div class="containerItem" style="display: none">-->
                                <!--<table class="layui-table" id="list5-table3"></table>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="tabIcons">-->
                <!--<ul class="listIcon" id="tab5">-->
                    <!--<li><i class="layui-icon">&#xe629;</i></li>-->
                    <!--<li><i class="layui-icon">&#xe62d;</i></li>-->
                <!--</ul>-->

            <!--</div>-->
        <!--</div>-->
        <!--<div class="item list6" >-->
            <!--<div class="itemTitle">-->
                <!--<span>ARPU</span>-->
                <!--<div class="help6">-->
                    <!--<span class="helpIcon layui-icon">&#xe607;</span>-->
                    <!--<div class="helpText6">-->
                        <!--<div class="helpTextTitle">数据指标说明</div>-->
                    <!--</div>-->
                <!--</div>-->
                <!--<div class="load">-->
                    <!--<span class="layui-icon" style="font-weight: bold">&#xe601;</span>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div id="tabcontent6">-->
                <!--&lt;!&ndash;图&ndash;&gt;-->
                <!--<div class="layui-tab tabs">-->
                    <!--<ul class="layui-tab-title " id="tabchar6" >-->
                        <!--<li class="layui-this">7日内平均贡献</li>-->
                        <!--<li>14日内平均贡献</li>-->
                        <!--<li>30日内平均贡献</li>-->
                        <!--<li>60日内平均贡献</li>-->
                        <!--<li>90日内平均贡献</li>-->
                    <!--</ul>-->
                    <!--<div class="containers" id="tabcharcont6">-->
                        <!--<div class="containerItem" id="list6-container1" style="width: 100%;height:300px;"></div>-->
                        <!--<div class="containerItem" id="list6-container2" style="width: 100%;height:300px;"></div>-->
                        <!--<div class="containerItem" id="list6-container3" style="width: 100%;height:300px;"></div>-->
                        <!--<div class="containerItem" id="list6-container4" style="width: 100%;height:300px;"></div>-->
                        <!--<div class="containerItem" id="list6-container5" style="width: 100%;height:300px;"></div>-->
                    <!--</div>-->
                <!--</div>-->
                <!--&lt;!&ndash;表&ndash;&gt;-->
                <!--<div class="layui-tab tabs tabs2" >-->
                    <!--<ul class="layui-tab-title " id="tabtable6" >-->
                        <!--<li class="layui-this">7日内平均贡献</li>-->
                        <!--<li>14日内平均贡献</li>-->
                        <!--<li>30日内平均贡献</li>-->
                        <!--<li>60日内平均贡献</li>-->
                        <!--<li>90日内平均贡献</li>-->
                    <!--</ul>-->
                    <!--<div class="list1Icon" >-->
                        <!--<div class="containers" id="tabtablecont6">-->
                            <!--<div class="containerItem" >-->
                                <!--<table class="layui-table" id="list6-table1"></table>-->
                            <!--</div>-->
                            <!--<div class="containerItem" style="display: none">-->
                                <!--<table class="layui-table" id="list6-table2"></table>-->
                            <!--</div>-->
                            <!--<div class="containerItem" style="display: none">-->
                                <!--<table class="layui-table" id="list6-table3"></table>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="tabIcons">-->
                <!--<ul class="listIcon" id="tab6">-->
                    <!--<li><i class="layui-icon">&#xe629;</i></li>-->
                    <!--<li><i class="layui-icon">&#xe62d;</i></li>-->
                <!--</ul>-->

            <!--</div>-->
        <!--</div>-->

    <!--</div>-->
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
            var colors = ['#5793f3', '#d14a61', '#675bba'];
            $.ajax({
                type: 'get',
                url: url,
                success: function(res) {
                    option = {
                        color: colors,

                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross'
                            }
                        },
                        grid: {
                            right: '20%'
                        },
                        toolbox: {
                            feature: {
                                dataView: {show: true, readOnly: false},
                                restore: {show: true},
                                saveAsImage: {show: true}
                            }
                        },
                        legend: {
                            data:['','','']
                        },
                        xAxis: [
                            {
                                type: 'category',
                                axisTick: {
                                    alignWithLabel: true
                                },
                                data: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value',
                                name: '',
                                min: 0,
                                max: 250,
                                position: 'right',
                                axisLine: {
                                    lineStyle: {
                                        color: colors[1]
                                    }
                                },
                                axisLabel: {
                                    formatter: '{value}'
                                }
                            },
                            // {
                            //     type: 'value',
                            //     name: '降水量',
                            //     min: 0,
                            //     max: 250,
                            //     position: 'right',
                            //     offset: 80,
                            //     axisLine: {
                            //         lineStyle: {
                            //             color: colors[1]
                            //         }
                            //     },
                            //     axisLabel: {
                            //         formatter: '{value} ml'
                            //     }
                            // },
                            {
                                type: 'value',
                                name: '',
                                min: 0,
                                max: 25,
                                position: 'left',
                                axisLine: {
                                    lineStyle: {
                                        color: colors[2]
                                    }
                                },
                                axisLabel: {
                                    formatter: '{value}'
                                }
                            }
                        ],
                        series: [
                            {
                                name:'',
                                type:'bar',
                                data:[2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3]
                            },
                            // {
                            //     name:'降水量',
                            //     type:'bar',
                            //     yAxisIndex: 1,
                            //     data:[2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3]
                            // },
                            {
                                name:'',
                                type:'line',
                                yAxisIndex: 1,
                                data:[2.0, 2.2, 3.3, 4.5, 6.3, 10.2, 20.3, 23.4, 23.0, 16.5, 12.0, 6.2]
                            }
                        ]
                    };
                    name.setOption(option);
                }
            });
        }

        help('help1','helpText1');
        help('help3','helpText3');
        help('help4','helpText4');
        help('help5','helpText5');
        help('help6','helpText6');


        tabs('tab1','tabcontent1');
        tabs('tabtable1','tabtablecont1');
        tabs('tab2','tabcontent2');
        tabs('tab3','tabcontent3');
        tabs('tabtable3','tabtablecont3');
        tabs('tab4','tabcontent4');
        tabs('tabtable4','tabtablecont4');
        // tabs('tab5','tabcontent5');
        // tabs('tabtable5','tabtablecont5');
        // tabs('tab6','tabcontent6');
        // tabs('tabtable6','tabtablecont6');


        var list1Chart1 = echarts.init(document.getElementById('list1-container1'));
        var list1Chart2 = echarts.init(document.getElementById('list1-container2'));
        var list1Chart3 = echarts.init(document.getElementById('list1-container3'));
        var list1Chart4 = echarts.init(document.getElementById('list1-container4'));

        var list2Chart1 = echarts.init(document.getElementById('list2-container1'));

        var list3Chart1 = echarts.init(document.getElementById('list3-container1'));
        var list3Chart2 = echarts.init(document.getElementById('list3-container2'));
        // var list3Chart3 = echarts.init(document.getElementById('list3-container3'));
        // var list3Chart4 = echarts.init(document.getElementById('list3-container4'));
        // var list3Chart5 = echarts.init(document.getElementById('list3-container5'));

        var list4Chart1 = echarts.init(document.getElementById('list4-container1'));
        var list4Chart2 = echarts.init(document.getElementById('list4-container2'));
        // var list4Chart3 = echarts.init(document.getElementById('list4-container3'));
        // var list4Chart4 = echarts.init(document.getElementById('list4-container4'));
        // var list4Chart5 = echarts.init(document.getElementById('list4-container5'));

        // var list5Chart1 = echarts.init(document.getElementById('list5-container1'));
        // var list5Chart2 = echarts.init(document.getElementById('list5-container2'));
        // var list5Chart3 = echarts.init(document.getElementById('list5-container3'));
        // var list5Chart4 = echarts.init(document.getElementById('list5-container4'));
        // var list5Chart5 = echarts.init(document.getElementById('list5-container5'));
        //
        // var list6Chart1 = echarts.init(document.getElementById('list6-container1'));
        // var list6Chart2 = echarts.init(document.getElementById('list6-container2'));
        // var list6Chart3 = echarts.init(document.getElementById('list6-container3'));
        // var list6Chart4 = echarts.init(document.getElementById('list6-container4'));
        // var list6Chart5 = echarts.init(document.getElementById('list6-container5'));

        bar('/test/t201',list1Chart1);
        bar('/test/t201',list2Chart1);
        bar('/test/t201',list3Chart1);
        bar('/test/t201',list4Chart1);
        // bar('/test/t201',list5Chart1);
        // bar('/test/t201',list6Chart1);

        //图表的li切换功能，参数：图表对应的tab的ID，需要渲染的图表的名称，cavas的ID

        function charTab(tabchar1,char1,char2,char3,char4,list3Container1,list3Container2,list3Container3,list3Container4){
            var tabchar = document.getElementById(tabchar1);
            var lis1 =$(tabchar).find('li:first');
            var lis2 =$(tabchar).find('li:eq(1)');
            var lis3 =$(tabchar).find('li:eq(2)');
            var lis4 =$(tabchar).find('li:eq(3)');
            var lis5 =$(tabchar).find('li:eq(4)');
            var listContainer1 = document.getElementById(list3Container1);
            var listContainer2 = document.getElementById(list3Container2);
            var listContainer3 = document.getElementById(list3Container3);
            var listContainer4 = document.getElementById(list3Container4);
            lis1.click(function () {
                $(listContainer4).hide();
                $(listContainer2).hide();
                $(listContainer3).hide();
                $(listContainer1).show();
                bar('/test/t201',char1);
            });
            lis2.click(function () {
                $(listContainer4).hide();
                $(listContainer3).hide();
                $(listContainer1).hide();
                $(listContainer2).show();
                // $(listContainer3).css('visibility','hidden');
                // $(listContainer1).css('visibility','hidden');
                // $(listContainer2).css('visibility','visible');
                bar('/test/t201',char2);
            });
            lis3.click(function () {
                $(listContainer4).hide();
                $(listContainer2).hide();
                $(listContainer1).hide();
                $(listContainer3).show();
                bar('/test/t201',char3);
            });
            lis4.click(function () {
                $(listContainer3).hide();
                $(listContainer2).hide();
                $(listContainer1).hide();
                $(listContainer4).show();
                bar('/test/t201',char4);
            });
        }
        function charTab1(tabchar1,char1,char2,list3Container1,list3Container2){
            var tabchar = document.getElementById(tabchar1);
            var lis1 =$(tabchar).find('li:first');
            var lis2 =$(tabchar).find('li:eq(1)');
            var listContainer1 = document.getElementById(list3Container1);
            var listContainer2 = document.getElementById(list3Container2);
            lis1.click(function () {
                $(listContainer2).hide();
                $(listContainer1).show();
                bar('/test/t201',char1);
            });
            lis2.click(function () {
                $(listContainer1).hide();
                $(listContainer2).show();
                bar('/test/t201',char2);
            });
        }
        charTab('tabchar1',list1Chart1,list1Chart2,list1Chart3,list1Chart4,'list1-container1','list1-container2','list1-container3','list1-container4');
        charTab1('tabchar3',list3Chart1,list3Chart2,'list3-container1','list3-container2');
        charTab1('tabchar4',list4Chart1,list4Chart2,'list4-container1','list4-container2');


        //table渲染
        function tableRender(tableID,url,field1,title1,field2,title2,field3,title3){
            table.render({
                elem:"#"+tableID
                ,url:url
                ,cols:[[
                    {field:field1,title:title1}
                    ,{field:field2,title:title2}
                    ,{field:field3,title:title3}
                ]]
            });
        }
        tableRender('list1-table1','/test/t206','age','月充值次数','num','付费人数（账户数）','age','百分比')
        tableRender('list1-table2','/test/t205','age','月充值次数','num','付费人数（账户数）','age','百分比')
        tableRender('list1-table3','/test/t206','age','月充值次数','num','付费人数（账户数）','age','百分比')
        tableRender('list1-table4','/test/t205','age','月充值次数','num','付费人数（账户数）','age','百分比')
        tableRender('list1-table5','/test/t208','age','月充值次数','num','付费人数（账户数）','age','百分比')

        tableRender('list2-table1','/test/t206','age','充值间隔','num','付费人数（账户数）','age','百分比')

        tableRender('list3-table1','/test/t205','age','充值方式','num','收入金额（￥）','age','百分比')
        tableRender('list3-table2','/test/t206','age','充值方式','num','收入金额（￥）','age','百分比')

        tableRender('list4-table1','/test/t206','age','消费包类型','num','收入金额（￥）','age','百分比')
        tableRender('list4-table2','/test/t205','age','消费包类型','num','收入金额（￥）','age','百分比')




    })
</script>