/**
 * Created by lenovo on 2017/3/27.
 */

jeDate({
    dateCell:"#dateinfo3",
    format:"YYYY-MM-DD hh:mm:ss",
    isinitVal:true,
    isTime:true, //isClear:false,
    minDate:"2014-09-19 00:00:00"
});

jeDate({
    dateCell:"#dateinfo4",
    format:"YYYY-MM-DD hh:mm:ss",
    isinitVal:true,
    isTime:true, //isClear:false,
    minDate:"2014-09-19 00:00:00"
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

//自定义过滤器
Vue.filter('status', function (value) {
    switch(value) {
        case '0':
            return '准备支付';
            break;
        case '1':
            return '支付成功';
            break;
        case '2':
            return '支付失败 待重新查询';
            break;
        case '3':
            return '支付失败 解除冻结金额';
            break;
        case '4':
            return '支付成功 但更新订单状态失败';
            break;
        case '5':
            return '支付成功  但减少用户冻结金额失败';
            break;
        case '6':
            return '转账失败 未成功';
            break;
        case '7':
            return '处理中订单 需要重试';
            break;
        case '999':
            return '余额不足 单独处理 人工跟进中';
            break;
    }
});


var vm = new Vue({
    el : 'body',
    ready : function(){
        this.searchOrder();
    },
    data : {
        order : [],

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
        showPageEnd: 8
    },
    methods :{
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
        searchOrder : function(){
            var that = this;
            //获取数据
            $.ajax({
                url: base_url + '&r=admin/get-take-money-order',
                type: 'get',
                data: {
                    player_index: $('#cash_id').val(),
                    order_id: $('#order_id ').val(),
                    status: $('#order').val(),
                    start_time: $('#dateinfo3').val(),
                    end_time: $('#dateinfo4').val(),
                    page_index: 1,
                    page_size: this.pagesize
                },
                success : function(res){
                    var json = res;
                    that.order = json.data.data;
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
                    url: base_url + '&r=admin/get-take-money-order',
                    type: 'get',
                    data: {
                        player_index: $('#cash_id').val(),
                        order_id: $('#order_id ').val(),
                        status: $('#order').val(),
                        start_time: $('#dateinfo3').val(),
                        end_time: $('#dateinfo4').val(),
                        page_index: page_index,
                        page_size: this.pagesize
                    },
                    success : function(res){
                        var json = res;
                        that.order = json.data.data;
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
                console.log("showPagesStart:" + this.showPagesStart + ",showPageEnd:" + this.showPageEnd + ",pageIndex:" + page_index);
            }
        }
    }
});






