
//代理列表

function change1(){
  var num = $("#range").val();
  $("#range_show_data").html(num)
}
function change2(){
  var num = $("#ability").val();
  $("#ability_show_data").html(num)
}

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

var daili_list = {
  page_index : 1,
  page_size : 10,
  sort_key : 'create_time',   //默认按时间排序
  sort_value : 3              //倒序
};

$.ajax({
  type : 'get',
  url : base_url + '&r=admin/get-daili-list',
  data : daili_list,
  success : function(res){
    var json = res;
    var resData = json.data.data ;
    if( json.ret_code == 0 ){
      var vm =  new Vue({
          el:'body',
          data:{
            myData: resData,          //代理列表分页数据
            reword : [],              //跟进记录分页数据
            nowIndex:-100 ,
            curItem : [],
            addItem_idx : null,
            curReword : [],
            qita : '',
            qtt : {},

            sort_key : 'create_time',
            sort_value : 3,

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
            edit : function(item){
              var vm = this ;
              //清空详细地址
              $('#add').val('');
              //复选框
              var arr = [] ;
              var t = $('input[type="checkbox"]') ;
              for(var i = 0 ,len = t.length ;i < len ;i++){
                arr.push($(t[i]).val());
              }
              this.curItem =  item ;
              this.curItem.inters = [] ;
              if(this.curItem.INTEREST){
                this.curItem.inters = this.curItem.INTEREST.split(",");
                this.curItem.inters.forEach(function(v,k){
                  if(arr.indexOf(v) < 0){
                    vm.qita = v ;
                    vm.qtt.val = v ;
                    vm.qtt.key = k ;
                  }
                });
                if(vm.qita){
                  if(this.curItem.inters.indexOf(vm.qita) < 0){
                    vm.qita = '' ;
                  }
                }
              }else{
                if(vm.qita){
                  this.curItem.inters.push(vm.qita) ;
                  vm.qita = '' ;
                }
              }
              $('#checks').on('change','input[type="checkbox"]',function(event){
                if($(this).is(':checked')){
                  vm.curItem.inters.push($(this).val());
                  vm.curItem.inters = unique(vm.curItem.inters);
                }else{
                  var curAh =  $(this).val();
                  var idx = vm.curItem.inters.indexOf(curAh) ;
                  if(idx >= 0){
                    vm.curItem.inters.splice(idx,1);
                    vm.curItem.inters = unique(vm.curItem.inters);
                  }
                }
              }) ;
            },
            editInfo : function(n){      //信息编辑
              var that = this ;
              var chk_value = n.inters.join(",");

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


              var dataObj = {
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
              };

              $.ajax({
                type : 'post',
                url : base_url + '&r=admin/updata-daili-info',
                data : dataObj,
                success : function(res){
                  var json = res;
                  if( json.ret_code == 0 ){
                    alert('修改成功');
                    $.ajax({
                      type : 'get',
                      url : base_url + '&r=admin/get-daili-list',
                      data : {
                        page_size : that.pagesize ,
                        page_index : that.pageCurrent,
                        sort_key : that.sort_key ,
                        sort_value : that.sort_value
                      },
                      success : function(res){
                        var json = res;
                        var resData = json.data.data ;
                        that.myData = resData ;
                        $('#distpicker').distpicker('reset', true);

                      }
                    });
                  }
                },
                error : function(err){
                  alert(err);
                }
              });
            },
            pChange : function () {
              // this.box.province = $('#province option:selected').val() ;
              this.box.city = '';
              this.box.district = '' ;
            },
            cChange : function(){
              // this.box.city = $('#city option:selected').val() ;
              this.box.district = '' ;
            },
            dChange : function(){
              // this.box.district = $('#district option:selected').val() ;
            },
            qt : function(n){
              var _this = this ;
              console.log(_this.qita);
              if(n){
                this.curItem.inters.push(n);
              }
              if(_this.qtt.key){
                this.curItem.inters.splice(_this.qtt.key, 1)
              }
            },
            telTest : function(){
              var tel = $('#edit_tel').val();
              if( tel != ''){
                var res = /^[0-9]{11}$/.test(tel);
                if(res){

                }else{
                  alert('请输入十一位手机号');
                }
              }
            },
            getReword : function(idx){                      //获取跟进记录
              var that = this;
              this.addItem_idx = idx;
              $.ajax({
                type : 'get',
                url : base_url + '&r=admin/follow-message',
                data : { player_index  : idx , page_size : 5 , page_index : 1 },
                success : function(res){
                  var json = res;
                  if( json.ret_code == 0 ){
                    that.reword = json.data.data;
                    that.reword_totalCount = json.data.count;
                    that.reword_pageCount = json.data.page_count;

                    that.reword_pageCurrent = 1;
                    that.reword_pagesize = 5;

                    that.rewordPage(1, null, true);
                  }
                }
              });

            },
            rewordPage : function(pageIndex, $event, forceRefresh){
              var that = this;
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
                  data : { player_index  : this.addItem_idx , page_size : this.reword_pagesize , page_index : pageIndex },
                  success : function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                      that.reword = json.data.data;
                      that.reword_pageCurrent = pageIndex;
                    }
                  }
                });

                //计算分页按钮数据
                if (this.reword_pageCount > this.reword_showPages) {
                  if (pageIndex <= (this.reword_showPages - 1) / 2) {
                    this.reword_showPagesStart = 1;
                    this.reword_showPageEnd = this.reword_showPages - 1;
                    console.log("showPage1")
                  }
                  else if (pageIndex >= this.reword_pageCount - (this.reword_showPages - 3) / 2) {
                    this.reword_showPagesStart = this.reword_pageCount - this.reword_showPages + 2;
                    this.reword_showPageEnd = this.reword_pageCount;
                    console.log("showPage2")
                  }
                  else {
                    console.log("showPage3")
                    this.reword_showPagesStart = pageIndex - (this.reword_showPages - 3) / 2;
                    this.reword_showPageEnd = pageIndex + (this.reword_showPages - 3) / 2;
                  }
                }
                console.log("showPagesStart:" + this.reword_showPagesStart + ",showPageEnd:" + this.reword_showPageEnd + ",pageIndex:" + pageIndex);
              }
            },
            addReword : function(){                     //添加跟进记录
              var that = this ;
              var dataObj = {
                player_index : this.addItem_idx,
                follow_remark : $('#reword').val()
              };

              if( dataObj.follow_remark != '' ){
                $.ajax({
                  type : 'post',
                  url : base_url + '&r=admin/follow-message',
                  data : dataObj,
                  success : function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                      alert('添加记录成功');
                      $('#reword').val('');
                      that.getReword(that.addItem_idx);
                    }
                  },
                  error : function(){
                    $('#reword').val('');
                  }
                });
              }else{
                alert('请输入跟进内容');
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
                    alert('修改跟进记录成功');
                    that.getReword(that.addItem_idx);
                  }
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
                    alert('删除成功');
                    that.getReword(that.addItem_idx);
                  }
                }
              });
            },
            deleteMsg:function(n){         //删除代理
              var that = this;
              $.ajax({
                type : 'post',
                url : base_url + '&r=admin/del-daili',
                data : { user_id : n },
                success : function(res){
                  var json = res;
                  if( json.ret_code == 0 ){
                    alert('删除成功');
                    $.ajax({
                      type : 'get',
                      url : base_url + '&r=admin/get-daili-list',
                      data : {
                        page_size : that.pagesize ,
                        page_index : that.pageCurrent,
                        sort_key : that.sort_key ,
                        sort_value : that.sort_value
                      },
                      success : function(res){
                        var json = res;
                        var resData = json.data.data ;
                        that.myData = resData ;
                        $('#distpicker').distpicker('reset', true);
                      }
                    });
                  }
                }
              });
            },
            sort_me : function(n){    //排序
              var that = this;
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

              $.ajax({
                type : 'get',
                url : base_url+ '&r=admin/get-daili-list',
                data : {
                  page_size : this.pagesize ,
                  page_index : this.pageCurrent,
                  sort_key : sort_key ,
                  sort_value : sort_value
                },
                success : function(res){
                  var json = res;
                  if( json.ret_code == 0 ){
                    var resData = json.data.data;
                    that.myData = resData;
                    that.sort_key = sort_key;
                    that.sort_value = sort_value;
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
                $.ajax({
                  type : 'get',
                  url : base_url + '&r=admin/get-daili-list',
                  data : {page_index : pageIndex , page_size : this.pagesize , sort_key : this.sort_key ,sort_value : this.sort_value},
                  success: function(res){
                    // console.log(res);
                    var json = res;
                    var resData = json.data.data ;
                    if( json.ret_code == 0 ){
                      console.log(resData);
                      that.myData = resData;
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
      vm.showPage(vm.pageCurrent, null, true);
    }
  }
});