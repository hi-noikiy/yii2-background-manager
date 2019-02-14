
//代理列表

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
$('#distpicker').distpicker({
    autoSelect: false
});

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

//数组删除某项功能
Array.prototype.remove = function (dx) {
    if (isNaN(dx) || dx > this.length) { return false; }
    for (var i = 0, n = 0; i < this.length; i++) {
        if (this[i] != this[dx]) {
            this[n++] = this[i]
        }
    }
    this.length -= 1
};

var vm =  new Vue({
    el:'body',
    ready : function(){
        this.search();
    },
    data:{
        sub_arr: [],
        myData: [],  //代理列表分页数据
        LogData: [],       //记录数据
        reword : [],              //跟进记录分页数据
        nowIndex:-100 ,
        curItem : {},
        addItem_idx : null,
        curReword : [],
        qita : '',
        qtt : {},

        sort_key : 'create_time',
        filter_key: 'all',
        sort_value : 3,

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
        showPageEnd: 8,
        //分页数据
        // arrayData: []

        //总项目数
        reword_totalCount: '',
        //分页数
        reword_pageCount: '',
        //当前页面
        reword_pageCurrent: 1,
        //分页大小
        reword_pagesize: 5,
        //显示分页按钮数
        reword_showPages: 5,
        //开始显示的分页按钮
        reword_showPagesStart: 1,
        //结束显示的分页按钮
        reword_showPageEnd: 5
        //分页数据
        // arrayData: []

    },
    methods:{
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
        edit : function(item){
            var that = this ;

            //复制克隆当前用户信息
            this.curItem = JSON.parse(JSON.stringify(item));

            //清空地址信息
            $('#editContent').val(this.curItem.content);
            $('#edit_start').val(this.curItem.start_time);
            $('#edit_end').val(this.curItem.end_time);
            $('#edit_id').val(this.curItem.id);
            if(this.curItem.notice_sign == 1){
                $("input[name='edit_notice']").prop("checked",true);
            }else{
                $("input[name='edit_notice']").prop("checked",false);
            }

            //获取所有的复选框value值
            var arr = [] ;
            var t = $('input[type="checkbox"]') ;
            for(var i = 0 ,len = t.length ;i < len ;i++){
                arr.push($(t[i]).val());
            }

            //保存并渲染已存在的兴趣爱好
            this.curItem.inters = [] ;
            if(this.curItem.INTEREST){       //存在已有的兴趣爱好
                this.curItem.inters = this.curItem.INTEREST.split(",");
                this.curItem.inters.forEach(function(v,k){                //遍历每一个，判断是否 存在于 arr数组中
                    if(arr.indexOf(v) < 0){
                        that.qita = v ;
                        that.qtt.key = k ;
                        that.qtt.val = v ;                                //存在的保存为key=value格式
                    }
                });
                if(this.qita){
                    if(this.curItem.inters.indexOf(this.qita) < 0){
                        this.qita = '' ;
                    }
                }
            }else{                            //兴趣爱好为空
                if(this.qita){
                    this.curItem.inters.push(this.qita) ;
                    this.qita = '' ;
                }
            }

            //复选框切换状态
            $('#checks').on('change','input[type="checkbox"]',function(event){
                if($(this).is(':checked')){
                    that.curItem.inters.push($(this).val());
                    that.curItem.inters = unique(that.curItem.inters);
                }else{
                    var curAh =  $(this).val();
                    var idx = that.curItem.inters.indexOf(curAh) ;
                    if(idx >= 0){
                        that.curItem.inters.splice(idx,1);
                        that.curItem.inters = unique(that.curItem.inters);
                    }
                }
            }) ;
        },
        createpm : function(){
            //$('#createpm').toggle();
        },
        telTest : function(){
            var tel = $('#edit_tel').val();
            if( tel != ''){
                var res = /^[0-9]{11}$/.test(tel);
                if(!res){
                    Modal.alert({msg: '请输入十一位手机号'});
                }
            }
        },
        qt : function(n){
            if(n){
                this.curItem.inters.push(n);
            }
            if(this.qtt.key){
                this.curItem.inters.splice(this.qtt.key, 1)
            }
        },
        editInfo : function(n){      //信息编辑
            var that = this ;

            //获取兴趣爱好选项
            var chk_value = n.inters.join(",");

            //获取地址选项
            var cityStr = '';
            if ($('#province').val() == ''){
                if( $('#add').val() == '' ){
                    cityStr =  this.curItem.ADDRESS;
                }else{
                    cityStr = $('#add').val();
                }
            }else{
                cityStr = $('#province').val() + $('#city').val() + $('#district').val() + $('#add').val();
            }

            $.ajax({
                type : 'post',
                url : base_url + '&r=admin/updata-daili-info',
                data : {
                    PLAYER_INDEX : this.curItem.PLAYER_INDEX,
                    TRUE_NAME : this.curItem.TRUE_NAME,
                    TEL : this.curItem.TEL,
                    ADDRESS : cityStr,
                    DAILI_LEVEL : this.curItem.DAILI_LEVEL,
                    FOLLOW : this.curItem.FOLLOW,
                    SEX : n.SEX,
                    AGE : this.curItem.AGE,
                    JOBS : this.curItem.JOBS,
                    EDUCATION : this.curItem.EDUCATION,
                    INCOME : this.curItem.INCOME,
                    INTEREST : chk_value,
                    ASPIRATION : this.curItem.ASPIRATION,
                    ABILITY : this.curItem.ABILITY,
                    SWOT : this.curItem.SWOT,
                    REMARK : this.curItem.REMARK
                },
                success : function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        Modal.alert({msg: '修改成功'}).on(function(){
                            that.showPage(1,null,true);
                            $('#distpicker').distpicker('reset', true);
                        });
                    }else{
                        Modal.alert({msg: json.ret_msg});
                    }
                },
                error : function(err){
                    Modal.alert({msg: '请求失败'});
                }
            });
        },
        search : function(){
            var that = this;
            var uid = $('#search_uid').val();
            //获取数据
            $.ajax({
                type : 'get',
                url : base_url + '&r=admin/get-white-list',
                data : {
                    page_index : 1 ,
                    page_size : this.pagesize ,
                    uid : uid,
                    sign : 1
                },
                success: function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        if( json.data.data != 0 ){
                            that.myData = json.data.data;
                            that.pageCurrent = 1;
                            that.totalCount = json.data.count;
                            that.pageCount = json.data.page_count;
                            that.sub_arr = json.data.sub_arr;
                        }else{
                            that.myData = '';
                        }
                    }
                },
                error: function(){
                    that.myData = '';
                }
            });

            //处理分页点中样式  第一页
            $("#pager").find("span").eq(2).addClass("active");
        },
        refreshFilter : function(key,bid){
            if(key == 1){
                var filter = 'all';
            }else if(key == 2){
                var filter = 'before';
            }else if(key == 3){
                var filter = 'after';
            }
            if(filter == this.filter_key){
                console.log('not refresh');
                return false;
            }
            $('#111').attr('class','btn btn-block btn-default');
            $('#222').attr('class','btn btn-block btn-default');
            $('#333').attr('class','btn btn-block btn-default');
            $('#'+bid).attr('class','btn btn-block btn-danger');
            this.filter_key = filter;
            this.search();

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
                    type : 'get',
                    url : base_url + '&r=admin/get-white-list',
                    data : {
                        page_index : page_index ,
                        page_size : this.pagesize ,
                        sign : 1,
                    },
                    success: function(res){
                        var json = res;
                        if( json.ret_code == 0 ){
                            if( json.data.data != 0 ){
                                that.myData = json.data.data;
                                that.pageCurrent = page_index;
                                that.totalCount = json.data.count;
                                that.pageCount = json.data.page_count;
                            }else{
                                that.myData = '';
                            }
                        }
                    }
                });


                //计算分页按钮数据
                if (this.pageCount > this.showPages) {
                    if (pageIndex <= (this.showPages - 1) / 2) {
                        this.showPagesStart = 1;
                        this.showPageEnd = this.showPages - 1;
                        console.log("showPage1");
                    }
                    else if (pageIndex >= this.pageCount - (this.showPages - 3) / 2) {
                        this.showPagesStart = this.pageCount - this.showPages + 2;
                        this.showPageEnd = this.pageCount;
                        console.log("showPage2");
                    }
                    else {
                        console.log("showPage3");
                        this.showPagesStart = pageIndex - (this.showPages - 3) / 2;
                        this.showPageEnd = pageIndex + (this.showPages - 3) / 2;
                    }
                }
                console.log("showPagesStart:" + this.showPagesStart + ",showPageEnd:" + this.showPageEnd + ",pageIndex:" + pageIndex);
            }
        },
        refreshPage: function (pageIndex) {
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
                    type : 'get',
                    url : base_url + '&r=admin/get-white-list',
                    data : {
                        page_index : page_index,
                        page_size : this.pagesize ,
                        sign : 1,
                    },
                    success: function(res){
                        var json = res;
                        if( json.ret_code == 0 ){
                            if( json.data.data != 0 ){
                                that.myData = json.data.data;
                                that.pageCurrent = 1;
                                that.totalCount = json.data.count;
                                that.pageCount = json.data.page_count;
                                that.sub_arr = json.data.sub_arr;
                            }else{
                                that.myData = '';
                            }
                        }
                    }
                });


                //计算分页按钮数据
                if (this.pageCount > this.showPages) {
                    if (pageIndex <= (this.showPages - 1) / 2) {
                        this.showPagesStart = 1;
                        this.showPageEnd = this.showPages - 1;
                        console.log("showPage1");
                    }
                    else if (pageIndex >= this.pageCount - (this.showPages - 3) / 2) {
                        this.showPagesStart = this.pageCount - this.showPages + 2;
                        this.showPageEnd = this.pageCount;
                        console.log("showPage2");
                    }
                    else {
                        console.log("showPage3");
                        this.showPagesStart = pageIndex - (this.showPages - 3) / 2;
                        this.showPageEnd = pageIndex + (this.showPages - 3) / 2;
                    }
                }
                console.log("showPagesStart:" + this.showPagesStart + ",showPageEnd:" + this.showPageEnd + ",pageIndex:" + pageIndex);
            }
        },
        getLog : function(item){
            var id = item.id;
            var that = this;
            $.ajax({
                type : 'get',
                url : base_url + '&r=admin/get-pm-log',
                data : {
                    id:id,
                },
                success: function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        if( json.data.data != 0 ){
                            that.LogData = json.data.data;
                        }else{
                            that.detailData = '';
                        }
                    }
                }
            });

        },
        addReword : function(){                     //添加跟进记录
            var that = this ;

            if( $('#reword').val() != '' ){
                $.ajax({
                    type : 'post',
                    url : base_url + '&r=admin/follow-message',
                    data : {
                        player_index : this.addItem_idx,
                        follow_remark : $('#reword').val()
                    },
                    success : function(res){
                        var json = res;
                        if( json.ret_code == 0 ){
                            Modal.alert({msg: '添加记录成功'}).on(function(){
                                $('#reword').val('');
                                that.getReword(that.addItem_idx);
                            });
                        }else{
                            Modal.alert({msg: json.ret_msg});
                        }
                    },
                    error : function(){
                        $('#reword').val('');
                        Modal.alert({msg: '请求失败'});
                    }
                });
            }else{
                Modal.alert({msg: '请输入跟进内容'});
            }
        },
        editReword : function (n) {                    //修改添加记录
            var that = this;
            $.ajax({
                type : 'post',
                url : base_url + '&r=admin/follow-message',
                data : { f_id :  n.F_ID , follow_remark : n.FOLLOW_REMARK},
                success : function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        Modal.alert({msg: '修改跟进记录成功'}).on(function(){
                            that.getReword(that.addItem_idx);
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
        delReword : function(n){    //删除记录
            var that = this;
            $.ajax({
                type : 'post',
                url : base_url + '&r=admin/del-follow-message',
                data : { f_id :  n.F_ID },
                success : function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        Modal.alert({msg: '删除成功'}).on(function(){
                            that.getReword(that.addItem_idx);
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
        deleteMsg:function(n){         //删除代理
            var that = this;
            $.ajax({
                type : 'post',
                url : base_url + '&r=admin/del-user-list',
                data : { id : n },
                success : function(res){
                    var json = res;
                    if( json.data == 1 ){
                        Modal.alert({msg: '删除成功'});
                        that.refreshPage(that.pageCurrent);
                    }else{
                        Modal.alert({msg: json.ret_msg});
                    }
                },
                error : function(){
                    Modal.alert({msg: '请求失败'});
                }
            });
        },
        sort_me : function(n){    //排序
            var sort_key = 'create_time';
            var sort_value = 3;
            if( n == 'id_z' ){                 //id正序
                sort_key = 'player_index';
                sort_value  =  4;
            }else if( n == 'id_d' ){            //id倒序
                sort_key = 'player_index';
                sort_value  =  3;
            }else if( n == 'level_z' ){          //代理级别正序
                sort_key = 'daili_level';
                sort_value  =  4;
            }else if( n == 'level_d' ){    //代理级别倒序
                sort_key = 'daili_level';
                sort_value  =  3;
            }else if( n == 'time_z' ){     //创建时间正序
                sort_key = 'create_time';
                sort_value  =  4;
            }else if( n == 'time_d' ){     //创建时间倒序
                sort_key = 'create_time';
                sort_value  =  3;
            }else if( n == 'yue_z' ){      //余额正序
                sort_key = 'pay_back_money';
                sort_value  =  4;
            }else if( n == 'yue_d' ){       //余额倒序
                sort_key = 'pay_back_money';
                sort_value  =  3;
            }else if( n == 'all_yue_z' ){       //历史余额正序
                sort_key = 'all_pay_back_money';
                sort_value  =  4;
            }else if( n == 'all_yue_d' ){          //历史余额倒序
                sort_key = 'all_pay_back_money';
                sort_value  =  3;
            }

            this.sort_key = sort_key;
            this.sort_value = sort_value;
            this.showPage(1,null,true);
        },
        delBtn : function(){
            //批量删代理
            if($('#op_token').val() == '') {
                Modal.alert({msg: '请输入操作验证码！'});
            } else if( $('#ids').val() == '' ){
                Modal.alert({msg: '请输入要删除的ID'});
            }else{
                var that = this;
                var data = $('#ids').val() + ',';
                var op_token = $('#op_token').val();
                $.ajax({
                    type : 'post',
                    url : base_url + '&r=admin/del-daili',
                    data : { user_id_str  : data, op_token : op_token },
                    success : function(res){
                        var json = res;
                        if( json.ret_code == 0 ){
                            Modal.alert({msg: '删除成功'}).on(function(){
                                $('#ids').val('');
                                that.showPage(1,null,true);
                                $('#distpicker').distpicker('reset', true);
                            });
                        }else{
                            Modal.alert({msg: json.ret_msg});
                        }
                    },
                    error : function(){
                        Modal.alert({msg: '请求失败'});
                    }
                });
            }
        }
    }
});

