
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
        myData: [],          //代理列表分页数据
        reword : [],              //跟进记录分页数据
        nowIndex:-100 ,
        curItem : {},
        addItem_idx : null,
        curReword : [],
        qita : '',
        qtt : {},
        sort_key : 'profit',
        sort_value : 3,
        //总项目数
        totalCount: '',
        //分页数
        pageCount: '',
        //当前页面
        pageCurrent: 1,
        //分页大小
        pagesize: 15,
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
       
        qt : function(n){
            console.log(this.qita);
            if(n){
                this.curItem.inters.push(n);
            }
            if(this.qtt.key){
                this.curItem.inters.splice(this.qtt.key, 1)
            }
        },
        
        search : function(){
            var that = this;
            //获取数据
            $.ajax({
                type : 'get',
                url : base_url + '&r=admin-oper/get-channels-daili-start',
                data : {
                    page_index : 1 ,
                    page_size : this.pagesize ,
                    sort_key : this.sort_key ,
                    sort_value : this.sort_value,
                    day : $("#dateinfo1").val(),
		            uid : $('#dadaili_id').val(),
                },
                success: function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        if( json.data.data != 0 ){
                            that.myData = json.data.data;
                            that.pageCurrent = 1;
                            that.totalCount = json.data.count;
                            that.pageCount = json.data.page_count;
                        if(json.data.sign == 2){
                         $('#dadaili_id').attr('type','text');
                        }
                        }else{
                            that.myData = '';
			    if(json.data.sign == 2){
                                $('#dadaili_id').attr('type','text');
                            }
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
                    url : base_url + '&r=admin-oper/get-channels-daili-start',
                    data : {
                        page_index : page_index ,
                        page_size  : this.pagesize ,
                        sort_key   : this.sort_key ,
                        sort_value : this.sort_value,
                        day : $("#dateinfo1").val(),
			            uid : $('#dadaili_id').val(),
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
        getReword : function(idx){
            var that = this;

            //每次打开跟进记录清空文本；
            $('#reword').val('');

            //保存当前需要查找跟进记录的id
            this.addItem_idx = idx;

            //默认渲染第一页
            $.ajax({
                type : 'get',
                url : base_url + '&r=admin/follow-message',
                data : { player_index  : idx , page_size : this.reword_pagesize , page_index : 1 },
                success : function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        that.reword = json.data.data;
                        that.reword_pageCurrent = 1;
                        that.reword_totalCount = json.data.count;
                        that.reword_pageCount = json.data.page_count;
                    }
                }
            });

            //处理分页点中样式  第一页
            $("#reword_pager").find("span").eq(2).addClass("active");
        },
        rewordPage : function(pageIndex, $event, forceRefresh){
            var that = this;
            var page_index = pageIndex;
            if (pageIndex > 0) {
                if (pageIndex > this.reword_pageCount) {
                    pageIndex = this.reword_pageCount;
                }

                //判断数据是否需要更新
                var currentPageCount = Math.ceil(this.reword_totalCount / this.reword_pagesize);
                if (currentPageCount != this.reword_pageCount) {
                    pageIndex = 1;
                    this.reword_pageCount = currentPageCount;
                }
                else if (this.reword_pageCurrent == pageIndex && currentPageCount == this.reword_pageCount && typeof (forceRefresh) == "undefined") {
                    console.log("not refresh");
                    return;
                }

                //处理分页点中样式
                var buttons = $("#reword_pager").find("span");
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
                    url : base_url + '&r=admin/follow-message',
                    data : { player_index  : this.addItem_idx , page_size : this.reword_pagesize , page_index : page_index },
                    success : function(res){
                        var json = res;
                        if( json.ret_code == 0 ){
                            that.reword = json.data.data;
                            that.reword_pageCurrent = page_index;
                            that.reword_totalCount = json.data.count;
                            that.reword_pageCount = json.data.page_count;
                        }
                    }
                });

                //计算分页按钮数据
                if (this.reword_pageCount > this.reword_showPages) {
                    if (pageIndex <= (this.reword_showPages - 1) / 2) {
                        this.reword_showPagesStart = 1;
                        this.reword_showPageEnd = this.reword_showPages - 1;
                        console.log("showPage1");
                    }
                    else if (pageIndex >= this.reword_pageCount - (this.reword_showPages - 3) / 2) {
                        this.reword_showPagesStart = this.reword_pageCount - this.reword_showPages + 2;
                        this.reword_showPageEnd = this.reword_pageCount;
                        console.log("showPage2");
                    }
                    else {
                        console.log("showPage3");
                        this.reword_showPagesStart = pageIndex - (this.reword_showPages - 3) / 2;
                        this.reword_showPageEnd = pageIndex + (this.reword_showPages - 3) / 2;
                    }
                }
                console.log("showPagesStart:" + this.reword_showPagesStart + ",showPageEnd:" + this.reword_showPageEnd + ",pageIndex:" + page_index);
            }
        },
       
        sort_me : function(n){    //排序
            var sort_key = 'profit';
            var sort_value = 3;

            if( n == 'id_z' ){                 //id正序
                sort_key = 'player_index';
                sort_value  =  4;
            }else if( n == 'id_d' ){            //id倒序
                sort_key = 'player_index';
                sort_value  =  3;
            }else if( n == 'profit_z' ){            //id倒序
                sort_key = 'profit';
                sort_value  =  4;
            }else if( n == 'profit_d' ){            //id倒序
                sort_key = 'profit';
                sort_value  =  3;
            }

            this.sort_key = sort_key;
            this.sort_value = sort_value;
            this.showPage(1,null,true);
        }
    }
});

