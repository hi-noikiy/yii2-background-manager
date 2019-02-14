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
var hyData = {
    player_index : '',
    member_index : '',
    page_index : 1,
    page_size : 10
};

$.ajax({
    url : base_url + '&r=admin/get-member',
    type : 'get',
    data : hyData,
    success : function(res) {
        var json = res;
        if( json.ret_code == 0 ){
            var vmm = new Vue({
                el : 'body',
                data : {
                    hylist : json.data.data,
                    curDaili : {},

                    //总项目数
                    totalCount: json.data.count,
                    //分页数
                    pageCount: json.data.page_count,
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
                    search : function(){
                        //获取数据
                        var that = this;
                        hyData.player_index = $('#daili_id').val();
                        hyData.member_index = $('#huiyuan_id').val();

                        $.ajax({
                            url : base_url + '&r=admin/get-member',
                            type : 'get',
                            data : hyData,
                            success: function(res){
                                var json = res;
                                if( json.ret_code == 0 ){
                                    that.hylist = json.data.data;

                                    //重置分页
                                    that.totalCount = json.data.count;
                                    that.pageCount = json.data.page_count;
                                    that.showPage(1, null, true);
                                }
                            }
                        });
                    },
                    edit : function(val){
                        this.curDaili = val;
                    },
                    editDaili : function(){
                        $.ajax({
                            url : base_url  + '&r=admin/update-member',
                            type : 'post',
                            data : { player_index : this.curDaili.PLAYER_INDEX , member_index : this.curDaili.MEMBER_INDEX },
                            success : function(res){
                                var json = res;
                                if( json.ret_code == 0 ){
                                    alert('修改成功');

                                }else{
                                    alert('修改失败,请重试');
                                }
                            }
                        });
                    },
                    showPage: function (pageIndex, $event, forceRefresh) {
                        var that = this;
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
                            hyData.page_index = pageIndex;
                            hyData.page_size = this.pagesize;

                            $.ajax({
                                url : base_url + '&r=admin/get-member',
                                type : 'get',
                                data : hyData,
                                success: function(res){
                                    var json = res;
                                    if( json.ret_code == 0 ){
                                        that.hylist = json.data.data;
                                        that.pageCurrent = pageIndex;
                                    }
                                }
                            });


                            //计算分页按钮数据
                            if (this.pageCount > this.showPages) {
                                if (pageIndex <= (this.showPages - 1) / 2) {
                                    this.showPagesStart = 1;
                                    this.showPageEnd = this.showPages - 1;
                                    console.log("showPage1")
                                }
                                else if (pageIndex >= this.pageCount - (this.showPages - 3) / 2) {
                                    this.showPagesStart = this.pageCount - this.showPages + 2;
                                    this.showPageEnd = this.pageCount;
                                    console.log("showPage2")
                                }
                                else {
                                    console.log("showPage3")
                                    this.showPagesStart = pageIndex - (this.showPages - 3) / 2;
                                    this.showPageEnd = pageIndex + (this.showPages - 3) / 2;
                                }
                            }
                            console.log("showPagesStart:" + this.showPagesStart + ",showPageEnd:" + this.showPageEnd + ",pageIndex:" + pageIndex);
                        }
                    }
                }
            });
            vmm.showPage(vmm.pageCurrent, null, true);
        }
    }
});