<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>主页</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />
    <meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->

    <link href="css/css/mui.min.css" rel="stylesheet" />
    <link href="css/css/icons-extra.css" rel="stylesheet" />
    <link href="css/css/mui.picker.min.css" rel="stylesheet" />
    <link href="css/css/mui.poppicker.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/css/reset.css">
    <link rel="stylesheet" href="css/css/public.css">
    <link rel="stylesheet" href="css/css/index.css">

</head>
<body>
<!-- 顶部标题  -->
<header class="mui-bar mui-bar-nav">
    <h1 class="mui-title" v-html="userData.user_info.web_daili_title"></h1>
    <p class="game_info">
       
        <span v-html="userData.user_info.play_name"></span>
        <span v-html="userData.user_info.cur_level"></span>
    </p>
</header>
<!--内容区域-->
<div class="mui-content">
    <!-- 跑马灯 -->
    <div class="light">
        <marquee  height="20" behavior="scroll" direction="left" scrollamount="2"  onMouseOut="this.start()">
            <ul class="marquee">
                <li></li>
            </ul>
        </marquee>
    </div>
    <!-- 轮播图 -->

    <div id="home" class="mui-control-content mui-active">
        <!-- 九宫格 -->
        <ul class="mui-table-view mui-grid-view mui-grid-9">
         
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a class="skip_btn" id="toCash_n">
                    <span class="mui-icon-extra mui-icon-extra-prech" style="color:#FED030"></span>
                    <div class="mui-media-body">提现</div>
                </a>
            </li>

            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a class="skip_btn" id="myInfo_n">
                    <span class="mui-icon-extra mui-icon-extra-people" style="color:#4DC6EE"></span>
                    <div class="mui-media-body">我的信息</div>
                </a>
            </li>

        </ul>
        <!-- 数据展示 -->
        <ul class="mui-table-view mui-grid-view mui-grid-9" id="data_show">
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#">
                    <div class="mui-media-body">余额(元)</div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#">
                    <div class="mui-media-body">会员</div>
                </a>
            </li>
            <!--<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">-->
                <!--<a href="#">-->
                    <!--<div class="mui-media-body">桌数</div>-->
                <!--</a>-->
            <!--</li>-->
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="" id="toCash_n">
                    <div style="height: 0px; width:0px; display: none;" id="pay_back_monney" v-html=""></div>
                    <div class="mui-media-body show1" ><?php echo $_SESSION['user_info']['other']['all']['pay_back_monney'];  ?></div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="" id="nextDetail_n">
                    <div style="display: none;" id="member_num" v-html=""></div>
                    <div class="mui-media-body show1" id="member_num" v-html="userData.user_info.member_num"></div>
                </a>
            </li>
            <!--<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">-->
                <!--<a href="#">-->
                    <!--<div class="mui-media-body show1" v-html="">0</div>-->
                <!--</a>-->
            <!--</li>-->
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="" id="billDetail_n">
                    <div class="mui-media-body">今日收益</div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="" id="nextDetail_n">
                    <div class="mui-media-body">今日绑定</div>
                </a>
            </li>
            <!--<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">-->
                <!--<a href="#">-->
                    <!--<div class="mui-media-body">环比昨日</div>-->
                <!--</a>-->
            <!--</li>-->
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#">
                    <div class="mui-media-body show2">+<span v-html="userData.user_info.other.all.today_new_profit_num"></span></div> 
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#">
                    <div class="mui-media-body show2">+<span v-html="userData.user_info.today_new_member_num"></span></div>
                </a>
            </li>
            <!--<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">-->
                <!--<a href="#">-->
                    <!--<div class="mui-media-body show2"><span>+</span><span v-html="">0</span></div>-->
                <!--</a>-->
            <!--</li>-->
        </ul>
       
       <!-- 详细地址 -->
        <section class="panel web_url">
         
            <?php foreach($games as $k => $v){?>
             
                 <a href=" <?php echo $v['url'] ?> " >游戏ID：<?php echo $v['name']; ?></a>
             
            <?php }?>
        </section>
        
        <!-- 数据显示 -->
        <section class="panel">
                  <header class="panel-heading bm0">
                       <span class="tools pull-right"><a href="javascript:;" class="iconpx-chevron-down"></a></span>
                  </header>
                  <div class="panel-body laery-seo-box">
                        <div class="larry-seo-stats" id="larry-seo-stats"></div>
                  </div>
      </section>
        
    </div>
    <div id="my" class="mui-control-content">
        <ul class="mui-table-view">
            <li class="mui-table-view-cell">
                <a class="mui-navigate-right skip_btn" id="myInfo_n">
                    <span class="mui-icon mui-icon-person"></span>
                    <span>我的信息</span>
                </a>
            </li>
            <li class="mui-table-view-cell">
                <a class="mui-navigate-right skip_btn" id="toCash_n">
                    <span class="mui-icon mui-icon-refresh"></span>
                    <span>余额提现</span>
                </a>
            </li>
            <li class="mui-table-view-cell">
                <a class="mui-navigate-right skip_btn" id="mail_n" v-on:tap="editMailStatus()">
                    <span class="mui-icon mui-icon-email"></span>
                    <span class="mymail">我的邮箱<span class="mui-badge mui-badge-danger mui-text-center mailNum" v-html="mailNum"></span></span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- 底部导航 -->
<!--<nav class="mui-bar mui-bar-tab">
    <a class="mui-tab-item mui-active" href="#home">
        <span class="mui-icon mui-icon-home"></span>
        <span class="mui-tab-label">首页</span>
    </a>
    <a class="mui-tab-item" href="#daili">
        <span class="mui-icon mui-icon-personadd"></span>
        <span class="mui-tab-label">代理</span>
    </a>
    <a class="mui-tab-item" href="#fuwu">
        <span class="mui-icon mui-icon-info"></span>
        <span class="mui-tab-label">服务</span>
    </a>
    <a class="mui-tab-item" href="#my">
        <span class="mui-icon mui-icon-gear"><span class="mui-badge mui-badge-danger"></span></span>
        <span class="mui-tab-label">我的</span>
    </a>
    <a class="mui-tab-item" href="#customer" id="nav_customer" style="display: none;">
        <span class="mui-icon mui-icon-person"></span>
        <span class="mui-tab-label">客户经理</span>
    </a>
</nav> -->


<!-- 代理升级提示 -->

<div class="loading">
    <div class="loading_inner">
        <img src="img/loading1.gif" style="width:30px;height:30px;">
    </div>
</div>

<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/mui.picker.min.js?v=2"></script>
<script type="text/javascript" src="js/js/mui.poppicker.js?v=2"></script>
<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<script type="text/javascript" src="js/js/jQuery.autoIMG.min.js?v=2"></script>
<script type="text/javascript" src="js/js/md5.js?v=2"></script>
<script type="text/javascript" src="js/js/countUp.min.js?v=2"></script>
<script type="text/javascript" src="js/js/echarts.min.js?v=2"></script>
<script type="text/javascript" src="js/js/public.js?v=2"></script>
<script type="text/javascript" src="js/js/index.js?v=2"></script>

<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('larry-seo-stats'));
            option = {
                title : {
                    text: '收益来源',
                    subtext: '',
                    left:'center'
                },
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c}元({d}%)"
                },
                legend: {
                    bottom:10 ,
                    left: 'center',
                    data: ['焖鸡','山西麻将','山西扣点','推倒胡']
                },
                series : [
                    {
                        name: '收益来源',
                        type: 'pie',
                        radius : '50%',
                        center: ['50%', '50%'],
                        data:[
                            {value: <?php echo $_SESSION['user_info']['other']['524803']['all_pay_back_monney']; ?> , name:'焖鸡'},
                            {value: <?php echo $_SESSION['user_info']['other']['524560']['all_pay_back_monney']; ?> , name:'山西麻将'},
                            {value: <?php echo $_SESSION['user_info']['other']['524561']['all_pay_back_monney']; ?> , name:'山西扣点'},
                            {value: <?php echo $_SESSION['user_info']['other']['524563']['all_pay_back_monney']; ?> , name:'推倒胡'},
                        ],
                        itemStyle: {
                            normal:{ 
                              label:{ 
                                show: true, 
                                formatter: "{b}\n({d}%)"
                              }, 
                              labelLine :{show:true} 
                            },
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
     myChart.setOption(option);
     mui('.web_url').on('tap','a',function(){document.location.href=this.href;});

</script>

</body>
</html>