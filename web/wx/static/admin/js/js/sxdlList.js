
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
        sxList: [],          //代理列表分页数据

        sort_key : 'player_index',
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
        showPageEnd: 8
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
        search : function(){
            var that = this;
            //获取数据
            $.ajax({
                type : 'get',
                url : base_url + '&r=admin/get-shixi-daili-list',
                data : {
                    page_index : page_index ,
                    page_size : this.pagesize ,
                    sort_key : this.sort_key ,
                    sort_value : this.sort_value
                },
                success: function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        if( json.data.data != 0 ){
                            that.sxList = json.data.data;
                            that.pageCurrent = page_index;
                            that.totalCount = json.data.count;
                            that.pageCount = json.data.page_count;
                        }else{
                            that.sxList = '';
                        }
                    }
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
                    type : 'get',
                    url : base_url + '&r=admin/get-shixi-daili-list',
                    data : {
                        page_index : page_index ,
                        page_size : this.pagesize ,
                        sort_key : this.sort_key ,
                        sort_value : this.sort_value
                    },
                    success: function(res){
                        var json = res;
                        if( json.ret_code == 0 ){
                            if( json.data.data != 0 ){
                                that.sxList = json.data.data;
                                that.pageCurrent = page_index;
                                that.totalCount = json.data.count;
                                that.pageCount = json.data.page_count;
                            }else{
                                that.sxList = '';
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
        }
    }
});