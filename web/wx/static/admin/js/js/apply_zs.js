/**
 * Created by lenovo on 2017/3/24.
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


var vm = new Vue({
    el : 'body',
    ready : function(){
        this.search();
    },
    data : {
        zsList : null,
        curid : null,

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
        successBtn : function(id){
            var that = this;
            $.ajax({
                type: 'post',
                url: base_url + '&r=admin/success-apply-formal-daili',
                data: {id: id},
                success: function (res) {
                    var json = res;
                    if (json.ret_code == 0) {
                        Modal.alert({msg: '已通过'}).on(function(){
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
        refuseBtn : function(id){
            var that = this;
            $.ajax({
                type: 'post',
                url: base_url + '&r=admin/fail-apply-formal-daili',
                data: {id: id},
                success: function (res) {
                    var json = res;
                    if (json.ret_code == 0) {
                        Modal.alert({msg: '已拒绝'}).on(function(){
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
        delBtn : function(id){
            var that = this;
            $.ajax({
                type: 'post',
                url: base_url + '&r=admin/del-apply-formal-daili',
                data: {id: id},
                success: function (res) {
                    var json = res;
                    if (json.ret_code == 0) {
                        Modal.alert({msg: '已删除'}).on(function(){
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
        search : function(){
            var that = this;
            //获取数据
            $.ajax({
                type: 'get',
                url: base_url + '&r=admin/get-apply-formal-daili-list',
                data : { page_index : 1 , page_count : this.pagesize },
                success: function (res) {
                    var json = res;
                    that.zsList = json.data.data;
                    that.pageCurrent = 1;
                    that.totalCount = json.data.count;
                    that.pageCount = json.data.page_count;

                }
            });
            //处理分页点中样式  第一页
            $("#pager").find("span").eq(2).addClass("active");
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
                    type: 'get',
                    url: base_url + '&r=admin/get-apply-formal-daili-list',
                    data : { page_index : page_index , page_count : this.pagesize },
                    success: function (res) {
                        var json = res;
                        that.zsList = json.data.data;
                        that.pageCurrent = page_index;
                        that.totalCount = json.data.count;
                        that.pageCount = json.data.page_count;

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