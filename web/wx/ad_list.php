<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>客服后台-代理信息-币商列表</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/static/admin/css/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/admin/css/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/admin/css/css/AdminLTE.min.css">
    <!-- skin color -->
    <link rel="stylesheet" href="/static/admin/css/css/skin-blue.min.css">
    <!-- public -->
    <link rel="stylesheet" href="/static/admin/css/css/public.css">


    <style type="text/css">
        .upload_img{ width:70%; margin-left:23%; margin-bottom: 10px; }
        .upload_img img{ width: 350px; }
    </style>
    <!--[if lt IE 9]>
    <script src="/static/admin/js/js/html5shiv.min.js"></script>
    <script src="/static/admin/js/js/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php  require('admin_left.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                客服管理系统
                <small>控制台</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>后台管理系统</a></li>
                <li>代理信息</li>
                <li class="active">会员列表</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body" style="overflow-x:auto">
                         
                            <table class="table table-bordered text-center" id="blist">
                                <tr>
                                    <th colspan="9">活动列表  <button data-toggle="modal" data-target="#edit" v-on:click="edit()" >新增活动</button></th>
                                </tr>
                                
                                <tr>
                                    <th>序号</th>
                                    <th>标题</th>
                                    <th>图片</th>
                                    <th>是否跳转</th>
                                    <th>跳转链接</th>
                                    <th>类型</th>
                                    <th>状态</th>
                                    <th>
                                        <span>过期时间</span>
                                        <span class="sortbox">
                                            <i class="sort sort1" v-on:click="sort_me('time_z')"></i>
                                            <i class="sort sort2" v-on:click="sort_me('time_d')"></i>
                                        </span>
                                    </th>
                                    <th>相关操作</th>

                                </tr>
                                <tr v-show="blist.length==0 && flag==true">
                                    <td colspan="7">暂无数据</td>
                                </tr>
                                <tr v-for="val in blist">
                                    <td v-html="val.ad_id"></td>
                                    <td v-html="val.ad_title"></td>
                                    <td> <img :src="val.ad_pic_url" width="100px;" /> </td>
                                    <td v-html="val.is_jump_name"></td>
                                    <td v-html="val.ad_jump_url"></td>
                                    <td v-html="val.type_name"> </td>
                                    <td v-html="val.states_name"></td>
                                    <td v-html="val.expire_time_name"></td>
                                    <td>
                                        <button data-toggle="modal" data-target="#edit" v-on:click="edit(val.ad_id,val.ad_title,val.ad_pic_url,val.is_jump,val.ad_jump_url,val.expire_time_name)">修改</button>
                                    </td>
                                </tr>
                            </table>
                            <div class="pager" id="pager" v-show="blist.length!=0">
                                <template v-for="item in pageCount+1">
                                    <span v-if="item==1" class="btn btn-default" v-on:click="showPage(1,$event)">
                                        首页
                                    </span>
                                    <span v-if="item==1" class="btn btn-default" v-on:click="showPage(pageCurrent-1,$event)">
                                        上一页
                                    </span>
                                    <span v-if="item==1" class="btn btn-default" v-on:click="showPage(item,$event)">
                                        {{item}}
                                    </span>
                                    <span v-if="item==1&&item<showPagesStart-1" class="btn btn-default disabled">
                                        ...
                                    </span>
                                    <span v-if="item>1&&item<=pageCount-1&&item>=showPagesStart&&item<=showPageEnd&&item<=pageCount" class="btn btn-default" v-on:click="showPage(item,$event)">
                                        {{item}}
                                    </span>
                                    <span v-if="item==pageCount&&item>showPageEnd+1" class="btn btn-default disabled">
                                        ...
                                    </span>
                                    <span v-if="item==pageCount" class="btn btn-default" v-on:click="showPage(item,$event)">
                                        {{item}}
                                    </span>
                                    <span v-if="item==pageCount" class="btn btn-default" v-on:click="showPage(pageCurrent+1,$event)">
                                        下一页
                                    </span>
                                    <span v-if="item==pageCount" class="btn btn-default" v-on:click="showPage(pageCount,$event)">
                                        尾页
                                    </span>
                                </template>
                                <span class="form-inline">
                                    <input class="pageIndex form-control" style="width:60px;text-align:center" type="text" v-model="pageCurrent | onlyNumeric"/>
                                </span>
                                <span class="btn btn-default" v-on:click="showPage(pageCurrent,$event,true)">
                                    跳转
                                </span>
                                <span>{{pageCurrent}}/{{pageCount}}</span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>

            <!-- 退出帐号 模态框-->
            <div role="dialog" class="modal fade bs-example-modal-sm" id="out">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                            <h4 class="modal-title">确认是否退出？</h4>
                        </div>
                        <div class="modal-footer text-right">
                            <button data-dismiss="modal" class="btn btn-default">取消</button>
                            <button data-dismiss="modal" class="btn btn-primary" v-on:click="out()">确认</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- alert 弹出框 -->
            <div id="myalert" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">百万棋牌室管理后台</h4>
                        </div>
                        <div class="modal-body">
                            <p>[Message]</p>
                        </div>
                        <div class="modal-footer" >
                            <button type="button" class="btn btn-primary ok" data-dismiss="modal">确定</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 修改 代理ID-->
            <div role="dialog" class="modal fade bs-example-modal-sm" id="edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                            <h4 class="modal-title">请修改：</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label  style="width: 25%; text-align: center; display: inline-block;">用户ID：</label>
                                <input type="text" class="form-control " readonly="readonly" style="display: inline-block; width: 70%; " v-model="ad_id">
                            </div>
                            <div class="form-group">
                                <label  style="width: 25%; text-align: center; display: inline-block;">标题：</label>
                                <input type="text" class="form-control " style="display: inline-block; width: 70%;" v-model="ad_title">
                            </div>
                            <div class="form-group">
                                <label  style="width: 25%; text-align: center; display: inline-block;">图片：</label>
                                <img :src="ad_pic_url"  width="350px" />
                            </div>
                            <div class="form-group" id ="upload_box">

                            </div>
                            <div class="form-group">
                                <label  style="width: 25%; text-align: center; display: inline-block;">上传新图：</label>
                                <input type="file" class="form-control " id="multiple" style="display: inline-block; width: 70%;" >
                                <input type="hidden" name="pic_url"     id="pic_url">
                                <p style="margin-top:10px; padding-left:26%; color: red; ">注：请选择954*571分辨率的图片，支持jpg和png格式</p>
                            </div>

                            <div class="form-group">
                                <label  style="width: 25%; text-align: center; display: inline-block;">是否跳转：</label>
                                <select class="form-control" id="switch" style=" display: inline-block; width: 60%;" v-model="is_jump">
                                    <option value="1" >—— 跳转 ——</option>
                                    <option value="0" >—— 不跳转 ——</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label  style="width: 25%; text-align: center; display: inline-block;">跳转地址：</label>
                                <input type="text" class="form-control " style="display: inline-block; width: 70%;" v-model="ad_jump_url">
                            </div>
                            <div class="form-group">
                                <label  style="width: 25%; text-align: center; display: inline-block;">过期时间：</label>
                                <input type="text" id="dateinfo1" class="form-control " style="display: inline-block; width: 70%;" v-model="expire_time">
                            </div>
                           
                        </div>
                        <div class="modal-footer text-right">
                            <button data-dismiss="modal" class="btn btn-default">取消</button>
                            <button data-dismiss="modal" class="btn btn-primary" v-on:click="editAd()">确认</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            
     
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            web
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2016 <a href="#">Future Games</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="/static/admin/js/js/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/static/admin/js/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="/static/admin/js/js/app.min.js"></script>
<!-- VUE -->
<script src="/static/admin/js/js/vue.min.js"></script>
<!-- 公用js -->
<script src="/static/admin/js/js/public.js"></script>
<script src="/static/admin/js/js/jedate.min.js"></script>
<script src="/static/admin/js/js/html5ImgCompress.min.js"></script>

<!-- 代理列表js -->
<script type="text/javascript">
    /**
 * Created by lenovo on 2017/2/20.
 */

var upload_img_url;
//只能输入正整数过滤器
Vue.filter('onlyNumeric', {
    // model -> view
    // 在更新 `<input>` 元素之前格式化值
    read: function (val) {
        return val;
    },
    // view -> model
    // 在写回数据之前格式化值
    write: function (val, oldVal) {
        var number = +val.replace(/[^\d]/g, '');
        return isNaN(number) ? 1 : parseFloat(number.toFixed(2))
    }
});

//格式化时间戳 自定义过滤器
Vue.filter('date', function (value) {
    return new Date(parseInt(value) * 1000).toLocaleString();
});
function unique(arr){
    var res = [];
    var json = {};
    for(var i = 0; i < arr.length; i++){
        if(!json[arr[i]]){
            res.push(arr[i]);
            json[arr[i]] = 1;
        }
    }
    return res;
}

//会员列表
var vm = new Vue({
    el : '.content',
    data : {
        blist : [],
        ad_id:'',   
        ad_title:'',    
        ad_pic_url:'', 
        is_jump:'', 
        ad_jump_url:'',
        type:'',   
        expire_time:'', 
        create_time:'',
        create_admin_id:'', 
        states:'',  
        flag : false,
        //总项目数
        totalCount: '',
        //分页数
        pageCount: '',
        //当前页面
        pageCurrent: 1,
        //分页大小
        pagesize: 10,
        //显示分页按钮数
        showPages: 10,
        //开始显示的分页按钮
        showPagesStart: 1,
        //结束显示的分页按钮
        showPageEnd: 10
    },
    ready : function(){
        this.search();
    },
    methods : {
        out : function(){
            $.ajax({
                type: 'post',
                url: base_url + '&r=admin/user/logout',
                success: function (res) {
                    var json = res;
                    if (json.ret_code == 0) {
                        window.location.href = base_url + '&r=admin/user/login';
                    }
                }
            });
        },
        search : function(){
            var that = this;
            //获取数据
            $.ajax({
                url : base_url + '&r=admin/get-ad-list',
                type : 'get',
                data : {
                    page_index : 1,
                    page_size : this.pagesize
                },
                success: function(res){
                    // console.log(res);
                    var json = res;
                    if( json.ret_code == 0 ){
                        that.blist = json.data.data;
                        that.pageCurrent = 1;
                        that.totalCount = json.data.count;
                        that.pageCount = json.data.page_count;
                    }
                }
            });
        },
     
        edit : function (ad_id,ad_title,ad_pic_url,is_jump,ad_jump_url,expire_time) {

            this.ad_id = ad_id;
            this.ad_title = ad_title;
            this.ad_pic_url = ad_pic_url;
            this.ad_jump_url = ad_jump_url;
            this.is_jump = is_jump;
            this.expire_time = expire_time;
            $("#upload_box").html('');
        },
        del : function (BID) {
            
            var that = this;
            $.ajax({
                url : base_url  + '&r=admin/del-business',
                type : 'post',
                data : { BID :BID},
                success : function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        Modal.alert({msg: '修改成功'}).on(function(){
                            that.showPage(1,null,true);
                        });
                    }else{
                        Modal.alert({msg: json.ret_msg});
                    }
                },
                error : function(){
                    Modal.alert({msg: '请求失败'});
                }
            });
        },

        editAd : function(){
            var that = this;
            this.SWITCH = $("#switch option:selected").val();
            $.ajax({
                url : base_url  + '&r=admin/ad-create',
                type : 'post',
                data : { 
                    adId: this.ad_id,
                    title:this.ad_title,
                    pic_url: upload_img_url,//$("#pic_url")[0].src,this.ad_pic_url,
                    isJump:  this.is_jump,
                    jumpUrl: this.ad_jump_url,
                    expireTime: this.expire_time,
                },
                success : function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        Modal.alert({msg: json.ret_msg}).on(function(){
                            that.showPage(this.pageCurrent,null,true);
                        });
                    }else{
                        Modal.alert({msg: json.ret_msg});
                    }
                },
                error : function(){
                    Modal.alert({msg: '请求失败'});
                }
            });
        },
        showPage: function (pageIndex, $event, forceRefresh) {
            var that = this;
            var page_index = pageIndex;

            if (pageIndex > 0) {
                if (pageIndex > this.pageCount) {
                    pageIndex = this.pageCount;
                }
                //判断数据是否需要更新
                var currentPageCount = Math.ceil(this.totalCount / this.pagesize);
                if (currentPageCount != this.pageCount) {
                    pageIndex = 1;
                    this.pageCount = currentPageCount;
                }
                else if (this.pageCurrent == pageIndex && currentPageCount == this.pageCount && typeof (forceRefresh) == "undefined") {
                    console.log("not refresh");
                    return;
                }
                //处理分页点中样式
                var buttons = $("#pager").find("span");
                for (var i = 0; i < buttons.length; i++) {
                    if (buttons.eq(i).html() != pageIndex) {
                        buttons.eq(i).removeClass("active");
                    }
                    else {
                        buttons.eq(i).addClass("active");
                    }
                }

                //获取数据
                $.ajax({
                    url : base_url + '&r=admin/get-ad-list',
                    type : 'get',
                    data : {
                        page_index : page_index,
                        page_size : this.pagesize
                    },
                    success: function(res){
                       // console.log(res);
                        var json = res;
                        if( json.ret_code == 0 ){
                            that.blist = json.data.data;
                            that.pageCurrent = pageIndex;
                            that.totalCount = json.data.count;
                            that.pageCount = json.data.page_count;
                        }
                    }
                });


                //计算分页按钮数据
                if (this.pageCount > this.showPages) {
                    if (pageIndex <= (this.showPages - 1) / 2) {
                        this.showPagesStart = 1;
                        this.showPageEnd = this.showPages - 1;
                    }
                    else if (pageIndex >= this.pageCount - (this.showPages - 3) / 2) {
                        this.showPagesStart = this.pageCount - this.showPages + 2;
                        this.showPageEnd = this.pageCount;
                    }
                    else {
                        this.showPagesStart = pageIndex - (this.showPages - 3) / 2;
                        this.showPageEnd = pageIndex + (this.showPages - 3) / 2;
                    }
                }
            }
        }
    }
});

        window.sendImgData = [];
        var j = 1;
        $('#multiple').on('change', function (e) {
            var
                i = 0,
                files = e.target.files,
                len = files.length,
                notSupport = false;

            // 循环多张图片，需要for循环和notSupport变量支持（检测）
            for (; i < len; i++) {
                j++;
                if (!notSupport) {
                    (function(i) {
                        new html5ImgCompress(files[i], {
                            before: function(file) {
                                console.log('多张: ' + i + ' 压缩前...');
                            },
                            done: function (file, base64) { // 这里是异步回调，done中i的顺序不能保证
                                console.log('多张: ' + i + ' 压缩成功...');
                                insertImg(base64, j);
                                $.ajax({
                                    url : base_url + '&r=qiniu/mobile-upload',
                                    type : 'post',
                                    data : {image_str : base64},
                                    dataType:'json',
                                    success : function(json){
                                        if( json.ret_code == 0 ){
                                        /*  alert(json.data);
                                            $('#up_states_'+j).html("成功");
                                            window.sendImgData.push(json.data);
                                            $("#pic_url").val(window.sendImgData.join(','));*/
                                            upload_img_url = json.data;
                                            alert("上传成功！");
                                        }else{
                                            alert("上传失败！");
                                        }
                                        
                                    },
                                   
                                    error : function () {
                                        //$('#up_states_'+j).html("上传失败");
                                        alert("网络错误！");
                                    }

                                });

                            },
                            fail: function(file) {
                                console.log('多张: ' + i + ' 压缩失败...');
                            },
                            complete: function(file) {
                                console.log('多张: ' + i + ' 压缩完成...');
                            },
                            notSupport: function(file) {
                                notSupport = true;
                                alert('浏览器不支持！');
                            }
                        });
                    })(i);
                }
            }
        });
        // html中插入图片
        function insertImg(file, j) {
            var
                img = new Image(),
                title, src, size, base;
        

            if (typeof file === 'object') {
                title = '前';
                size = file.size;
                src = URL.createObjectURL(file);
                base = 1024;
            } else {
                title = '后';
                size = file.length;
                src = file;
                base = 1333;
            }
            var str = "<div class='col-sm-10 upload_img box_"+j+"'></div>"
            if (!$('.box_' + j).length) {
                $('#upload_box').prepend(str); // 逆序，方便demo多次上传预览
            }
            img.onload = function() {
                $('.box_' + j).prepend(img);
            };
            img.src = src;
            file = null; // 处理完后记得清缓存
        }




</script>

</body>
</html>
