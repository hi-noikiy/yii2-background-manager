/**
 * Created by lenovo on 2017/2/20.
 */

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
        hylist : [],
        pi : '',
        mi : '',

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
            var islogin = 0;
            if($("#islogin").is(":checked")){
                islogin = 1;
            }
            //获取数据
            $.ajax({
                url : base_url + '&r=admin/get-member',
                type : 'get',
                data : {
                    player_index : $('#daili_id').val(),
                    member_index : $('#huiyuan_id').val(),
                    page_index : 1,
                    islogin:islogin,
                    page_size : this.pagesize
                },
                success: function(res){
                    // console.log(res);
                    var json = res;
                    if( json.ret_code == 0 ){
                        that.hylist = json.data.data;
                        that.pageCurrent = 1;
                        that.totalCount = json.data.count;
                        that.pageCount = json.data.page_count;
                    }
                }
            });
        },
        edit : function (pi,mi) {
            this.pi = pi;
            this.mi = mi;
        },
        editDaili : function(){
            var that = this;
            $.ajax({
                url : base_url  + '&r=admin/update-member',
                type : 'post',
                data : { player_index : this.pi , member_index : this.mi, op_token: this.op_token },
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
        showPage: function (pageIndex, $event, forceRefresh) {
            var that = this;
            var page_index = pageIndex;
            var islogin = 0;
            if($("#islogin").is(":checked")){
                islogin = 1;
            }

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
                    url : base_url + '&r=admin/get-member',
                    type : 'get',
                    data : {
                        player_index : $('#daili_id').val(),
                        member_index : $('#huiyuan_id').val(),
                        page_index : page_index,
                        islogin:islogin,
                        page_size : this.pagesize
                    },
                    success: function(res){
                       // console.log(res);
                        var json = res;
                        if( json.ret_code == 0 ){
                            that.hylist = json.data.data;
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

