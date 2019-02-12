<style>
    .item{
        width:98%;
        height:400px;
        border:1px solid #EEEEEE;
        margin-bottom: 20px;
        /*padding:0 15px;*/
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        overflow:hidden;
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
    .containers{width:100%;height:100%;}
    #container1,#container2,#container3,#container4,#container5,#container6{
        margin: 0 auto;
    }
    /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
    .tabs{ margin-left: 20px;}
    .tables{display: flex;justify-content: space-between; }
    .item{margin:5px;}
    .layui-table,.layui-table-view{margin:0!important;}
    hr{margin:20px 0;}
    .helpIcon{float:right;}
    .helpIcon:hover{cursor: pointer;}
    .help1{display: inline-block;float: right;position:relative;}
    .helpText1{padding:0 5px;display: none;position:absolute;top:10px;right:20px;width:300px;height:200px;overflow: auto;background-color: #000;color:#FFF;border-radius: 5px;z-index: 9;opacity: 0.5;}
    .help2{display: inline-block;float: right;position:relative;}
    .helpText2{padding:0 5px;display: none;position:absolute;top:10px;right:20px;width:300px;height:200px;overflow: auto;background-color: #000;color:#FFF;border-radius: 5px;z-index: 9;opacity: 0.5;}

</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">在线分析</a>
            <a>
                <cite>在线分析</cite>
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
    <div class="item">
        <div class="itemTitle">
            <span>在线玩家</span>
            <div class="help1">
                <span class="helpIcon layui-icon">&#xe607;</span>
                <div class="helpText1">
                    <div class="helpTextTitle">数据指标说明</div>
                </div>
            </div>

        </div>
        <div class="containers">
            <div id="container1" style="width: 90%;height:300px;"></div>
        </div>
    </div>
    <hr>
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="start_time" placeholder="开始时间">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="end_time" placeholder="结束时间">
            </div>
            <div class="layui-btn"  data-type="search" id="search"><i class="layui-icon">&#xe615;</i></div>
        </div>
    </form>

    <div class="item" >
        <div class="itemTitle">
            <span>每日在线用户</span>
            <div class="help2">
                <span class="helpIcon layui-icon">&#xe607;</span>
                <div class="helpText2">
                    <div class="helpTextTitle">数据指标说明</div>
                </div>
            </div>

        </div>
        <div class="layui-tab">
            <ul class="layui-tab-title tabs">
                <li class="layui-this">ACU</li>
                <li id="li2">PCU</li>
                <li id="li3">ACU/PCU</li>
                <li id="li4">平均在线时长</li>
                <li id="li5">最高在线时长</li>
            </ul>
            <div class="containers">
                <div class="containerItem" id="container2" style="width: 100%;height:300px;"></div>
                <div class="containerItem" id="container3" style="width: 100%;height:300px;"></div>
                <div class="containerItem" id="container4" style="width: 100%;height:300px;"></div>
                <div class="containerItem" id="container5" style="width: 100%;height:300px;"></div>
                <div class="containerItem" id="container6" style="width: 100%;height:300px;"></div>
            </div>
        </div>
    </div>
    <hr>
    <div class="tables">
        <div class="item" style="border:0;">
            <div class="itemTitle">
                <p>ACU</p>
            </div>
            <table id="ACU" class="layui-table"></table>
        </div>
        <div class="item" style="height:200px;border:0;">
            <div class="itemTitle">
                <p>PCU</p>
            </div>
            <table id="PCU" class="layui-table"></table>
        </div>
        <div class="item" style="height:200px;border:0;">
            <div class="itemTitle">
                <p>ACU/PCU</p>
            </div>
            <table id="AP" class="layui-table"></table>
        </div>
        <div class="item" style="height:200px;border:0;">
            <div class="itemTitle">
                <p>平均在线时长</p>
            </div>
            <table id="AVG" class="layui-table"></table>
        </div>
        <div class="item" style="height:200px;border:0;">
            <div class="itemTitle">
                <p>最高在线时长</p>
            </div>
            <table id="MAX" class="layui-table"></table>
        </div>
    </div>

</div>
</body>
<script src="//cdn.bootcss.com/echarts/3.3.2/echarts.min.js" charset="utf-8"></script>
<script>
// <<<<<<< HEAD
//     layui.use(['table','layer','laydate'],function () {
//         var $ = layui.$;
//         var table = layui.table;
//         var laydate = layui.laydate;
//         laydate.render({elem: '#start_time'});
//         laydate.render({elem: '#end_time'});
//         laydate.render({elem: '#start_time1'});
//         laydate.render({elem: '#end_time1'});
//
//         //帮助的鼠标悬浮事件
//         $(".help1").mouseover(function () {
//             $('.helpText1').css("display","block");
//         })
//         $(".help1").mouseout(function () {
//             $('.helpText1').css("display","none");
//         })
//         $(".help2").mouseover(function () {
//             $('.helpText2').css("display","block");
//         })
//         $(".help2").mouseout(function () {
//             $('.helpText2').css("display","none");
//         })
//
//         //获取前n天日期
//         function preDays(n){
//             var date= new Date();
//             var newDate = new Date(date.getTime() - n*24*60*60*1000);
//             var time = newDate.getFullYear()+"-"+(newDate.getMonth()+1)+"-"+newDate.getDate();
//             return time
//         }
//
//
//         //在线玩家数据
//         function mychart1(url,name) {
//             $.ajax({
//                 type: 'get',
//                 url: url,
//                 success: function(res) {
//                     option = {
//                         title: {
//                             text: ' '
//                         },
//                         tooltip: {
//                             trigger: 'axis'
//                         },
//                         legend: {
//                             data:['今天','昨天','七天前']
//                         },
//                         grid: {
//                             left: '3%',
//                             right: '4%',
//                             bottom: '3%',
//                             containLabel: true
//                         },
//                         // toolbox: {
//                         //     feature: {
//                         //         saveAsImage: {}
//                         //     }
//                         // },
//                         xAxis: {
//                             type: 'category',
//                             boundaryGap: false,
//                             data: ['00:00','00:20','00:40','01:00','01:20','01:40','02:00',
//                             '02:20','02:40','03:00','03:20','03:40','04:00', '04:20',
//                             '04:40','05:00','05:20','05:40','06:00', '06:20','06:40',
//                             '07:00','07:20','07:40','08:00', '08:20','08:40','09:00',
//                             '09:20','09:40','10:00', '11:20','11:40','12:00','12:20',
//                             '12:40','13:00', '13:20','13:40','14:00','14:20','14:40',
//                             '15:00', '15:20','15:40','16:00','16:20','16:40','17:00',
//                             '17:20','17:40','18:00','18:20','18:40','19:00', '19:20',
//                             '19:40','20:00','21:20','21:40','22:00', '21:20','21:40',
//                             '22:00','22:20','22:40','23:00', '23:20','23:40',]
//                         },
//                         yAxis: {
//                             type: 'value'
//                         },
//                         series: [
//                             {
//                                 name:'今天',
//                                 type:'line',
//                                 stack: '总量',
//                                 data:[120, 132, 101, 134, 90, 230, 210]
//                             },
//                             {
//                                 name:'昨天',
//                                 type:'line',
//                                 stack: '总量',
//                                 data:[320, 332, 301, 334, 390, 330, 320]
//                             },
//                             {
//                                 name:'七天前',
//                                 type:'line',
//                                 stack: '总量',
//                                 data:[820, 932, 901, 934, 1290, 1330, 1320]
//                             }
//                         ]
//                     };
//                     name.setOption(option);
//                 }
//             });
//         }
//         //每日在线用户数据
//         function mychart2(url,name1) {
//             $.ajax({
//                 type: 'get',
//                 url: url,
//                 success: function(res) {
//                     option = {
//                         title: {
//                             text: ' '
//                         },
//                         // tooltip: {
//                         //     trigger: 'axis'
//                         // },
//                         // legend: {
//                         //     data:['邮件营销','联盟广告','视频广告','直接访问','搜索引擎']
//                         // },
//                         // grid: {
//                         //     left: '3%',
//                         //     right: '4%',
//                         //     bottom: '3%',
//                         //     containLabel: true
//                         // },
//                         // toolbox: {
//                         //     feature: {
//                         //         saveAsImage: {}
//                         //     }
//                         // },
//                         xAxis: {
//                             type: 'category',
//                             boundaryGap: false,
//                             data: [preDays(0),preDays(1),preDays(2),preDays(3),preDays(4),preDays(5),preDays(6)]
//                         },
//                         yAxis: {
//                             type: 'value'
//                         },
//                         series: [
//                             {
//                                 name:'邮件营销',
//                                 type:'line',
//                                 stack: '总量',
//                                 itemStyle : {
//                                     normal : {
//                                         color:'#6DC5FD',
//                                         lineStyle:{
//                                             color:'#6DC5FD'
//                                         }
//                                     }
//                                 },
//                                 data:[120, 132, 101, 134, 90, 230, 210]
//                             },
//                         ]
//                     };
//                     name1.setOption(option);
//                 }
//             });
//         }
//
//         //图表初始化
//         var myChart1 = echarts.init(document.getElementById('container1'));
//         var myChart2 = echarts.init(document.getElementById('container2'));
//         var myChart3 = echarts.init(document.getElementById('container3'));
//         var myChart4 = echarts.init(document.getElementById('container4'));
//         var myChart5 = echarts.init(document.getElementById('container5'));
//         var myChart6 = echarts.init(document.getElementById('container6'));
//
//         mychart1('/test/t201',myChart1);
//         mychart2('/test/t201',myChart2);
//
//
//
//         $(".tabs li:first").click(function () {
//             $('#container3').hide();
//             $('#container4').hide();
//             $('#container2').show();
//             mychart2('/test/t201',myChart2);
//         })
//         $(".tabs li:eq(1)").click(function () {
//             $('#container5').hide();
//             $('#container6').hide();
//             $('#container4').hide();
//             $('#container2').hide();
//             $('#container3').show();
//             mychart2('/test/t201',myChart3);
//         });
//         $(".tabs li:eq(2)").click(function () {
//             $('#container5').hide();
//             $('#container6').hide();
//             $('#container3').hide();
//             $('#container2').hide();
//             $('#container4').show();
//             mychart2('/test/t201',myChart4);
//         })
//         $(".tabs li:eq(3)").click(function () {
//             $('#container4').hide();
//             $('#container6').hide();
//             $('#container3').hide();
//             $('#container2').hide();
//             $('#container5').show();
//             mychart2('/test/t201',myChart5);
//         });
//         $(".tabs li:eq(4)").click(function () {
//             $('#container5').hide();
//             $('#container4').hide();
//             $('#container3').hide();
//             $('#container2').hide();
//             $('#container6').show();
//             mychart2('/test/t201',myChart6);
//         });
//
//         table.render({
//             elem:"#AP"
//             ,url:"/test/t206"
//             ,cols:[[
//                 {field:"number",title:"MAX"}
//                 ,{field:"ID",title:"AVG"}


layui.use(['table','layer','laydate'],function () {
    var $ = layui.$;
    var table = layui.table;
    var laydate = layui.laydate;
    laydate.render({elem: '#start_time'});
    laydate.render({elem: '#end_time'});
    laydate.render({elem: '#start_time1'});
    laydate.render({elem: '#end_time1'});

    //帮助的鼠标悬浮事件
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

    //获取前n天日期
    function preDays(n){
        var date= new Date();
        var newDate = new Date(date.getTime() - n*24*60*60*1000);
        var time = newDate.getFullYear()+"-"+(newDate.getMonth()+1)+"-"+newDate.getDate();
        return time
    }

    $('#search1').on('click',function () {
        mychart1('',myChart1);
    });


    //在线玩家数据
    function mychart1(url,name) {
        $.ajax({
            type: 'get',
            url: '/online/online-seven',
            data:{time:$('#start_time1').val()},
            success: function(res) {
                res = eval('('+res+')');
                var data = res.data;
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
                        data: data[0].time
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [
                        {
                            name:'今天',
                            type:'line',
                            stack: '总量',
                            data:data[0].num
                        },
                        {
                            name:'昨天',
                            type:'line',
                            stack: '总量',
                            data:data[1].num
                        },
                        {
                            name:'七天前',
                            type:'line',
                            stack: '总量',
                            data:data[2].num
                        }
                    ]
                };
                name.setOption(option);
            }
        });
    }
    //每日在线用户数据
    function mychart2(type,name1) {
        $.ajax({
            type: 'get',
            url: '/online/online-day',
            data:{
                start_time:$('#start_time').val(),
                end_time:$('#end_time').val()
            },
            success: function(res) {
                res = eval('('+res+')');
                var data = res.data;
                var data_y = [];
                var data_x = [];
                switch (type) {
                    case 1:
                        for (var i = 0; i < data.length; i++) {
                            if (typeof data[i].avg_online != "undefined") {
                                data_y.push(data[i].avg_online)
                            } else {
                                data_y.push(0)
                            }
                            data_x.push(preDays(i));
                        }
                        break;
                    case 2:
                        for (var i = 0; i < data.length; i++) {
                            if (typeof data[i].max_online != "undefined") {
                                data_y.push(data[i].max_online)
                            } else {
                                data_y.push(0)
                            }
                            data_x.push(preDays(i));
                        }
                        break;
                    case 3:
                        for (var i = 0; i < data.length; i++) {
                            if (typeof data[i].avg_online != "undefined" && typeof data[i].max_online != "undefined") {
                                data_y.push(data[i].avg_online/data[i].max_online)
                            } else {
                                data_y.push(0)
                            }
                            data_x.push(preDays(i));
                        }
                        break;
                    case 4:
                        for (var i = 0; i < data.length; i++) {
                            if (typeof data[i].avg_time != "undefined") {
                                data_y.push(data[i].avg_time)
                            } else {
                                data_y.push(0)
                            }
                            data_x.push(preDays(i));
                        }
                        break;
                    case 5:
                        for (var i = 0; i < data.length; i++) {
                            if (typeof data[i].max_time != "undefined") {
                                data_y.push(data[i].max_time)
                            } else {
                                data_y.push(0)
                            }
                            data_x.push(preDays(i));
                        }
                        break;
                }
                console.log(data_y);
                option = {
                    title: {
                        text: ' '
                    },
                    // tooltip: {
                    //     trigger: 'axis'
                    // },
                    // legend: {
                    //     data:['邮件营销','联盟广告','视频广告','直接访问','搜索引擎']
                    // },
                    // grid: {
                    //     left: '3%',
                    //     right: '4%',
                    //     bottom: '3%',
                    //     containLabel: true
                    // },
                    // toolbox: {
                    //     feature: {
                    //         saveAsImage: {}
                    //     }
                    // },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: [preDays(0),preDays(1),preDays(2),preDays(3),preDays(4),preDays(5),preDays(6)]
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [
                        {
                            name:'邮件营销',
                            type:'line',
                            stack: '总量',
                            itemStyle : {
                                normal : {
                                    color:'#6DC5FD',
                                    lineStyle:{
                                        color:'#6DC5FD'
                                    }
                                }
                            },
                            data:data_y
                        },
                    ]
                };
                name1.setOption(option);
            }
        });
    }
    $('#search').on('click',function () {
        mychart2(1,myChart2);
        dayUser();
    });

    //图表初始化
    var myChart1 = echarts.init(document.getElementById('container1'));
    var myChart2 = echarts.init(document.getElementById('container2'));
    var myChart3 = echarts.init(document.getElementById('container3'));
    var myChart4 = echarts.init(document.getElementById('container4'));
    var myChart5 = echarts.init(document.getElementById('container5'));
    var myChart6 = echarts.init(document.getElementById('container6'));

    mychart1('/test/t201',myChart1);
    mychart2('/test/t201',myChart2);



    $(".tabs li:first").click(function () {
        $('#container3').hide();
        $('#container4').hide();
        $('#container2').show();
        mychart2(1,myChart2);
    })
    $(".tabs li:eq(1)").click(function () {
        $('#container5').hide();
        $('#container6').hide();
        $('#container4').hide();
        $('#container2').hide();
        $('#container3').show();
        mychart2(2,myChart3);
    });
    $(".tabs li:eq(2)").click(function () {
        $('#container5').hide();
        $('#container6').hide();
        $('#container3').hide();
        $('#container2').hide();
        $('#container4').show();
        mychart2(3,myChart4);
    })
    $(".tabs li:eq(3)").click(function () {
        $('#container4').hide();
        $('#container6').hide();
        $('#container3').hide();
        $('#container2').hide();
        $('#container5').show();
        mychart2(4,myChart5);
    });
    $(".tabs li:eq(4)").click(function () {
        $('#container5').hide();
        $('#container4').hide();
        $('#container3').hide();
        $('#container2').hide();
        $('#container6').show();
        mychart2(5,myChart6);
    });


    function dayUser(){
        table.render({
            elem:"#AP"
            ,url:"/online/online-day"
            ,page:false
            ,where:{
                start_time:$('#start_time').val(),
                end_time:$('#end_time').val()
            }
            ,cols:[[
                {field:"stat_date",title:"MAX"}
                ,{field:"",title:"AVG",templet:function (d) {
                    return Math.round(isNaN(d.avg_online/d.max_online)?0:(d.avg_online/d.max_online))*100+'%';
                }}
            ]]
        });
        table.render({
            elem:"#ACU"
            ,url:"/online/online-day"
            ,page:false
            ,where:{
                start_time:$('#start_time').val(),
                end_time:$('#end_time').val()
            }
            ,cols:[[
                {field:"stat_date",title:"date"}
                ,{field:"avg_online",title:"value"}
            ]]
        });
        table.render({
            elem:"#PCU"
            ,url:"/online/online-day"
            ,page:false
            ,where:{
                start_time:$('#start_time').val(),
                end_time:$('#end_time').val()
            }
            ,cols:[[
                {field:"stat_date",title:"date"}
                ,{field:"max_online",title:"value"}
            ]]
        });
        table.render({
            elem:"#AVG"
            ,url:"/online/online-day"
            ,page:false
            ,where:{
                start_time:$('#start_time').val(),
                end_time:$('#end_time').val()
            }
            ,cols:[[
                {field:"stat_date",title:"date"}
                ,{field:"avg_time",title:"value"}
            ]]
        });
        table.render({
            elem:"#MAX"
            ,url:"/online/online-day"
            ,page:false
            ,where:{
                start_time:$('#start_time').val(),
                end_time:$('#end_time').val()
            }
            ,cols:[[
                {field:"stat_date",title:"date"}
                ,{field:"max_time",title:"value"}
            ]]
        });
    }
    dayUser();
})
</script>
