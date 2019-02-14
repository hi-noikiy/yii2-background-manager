/**
 * Created by lenovo on 2017/3/24.
 */

var vm = new Vue({
    el : 'body',
    ready : function(){
        this.init();
    },
    data : {
        zsList : null,
        success_id : null,
        refuse_id : null,
        del_id : null
    },
    methods : {
        init : function(){
            var that = this;
            $.ajax({
                type: 'get',
                url: base_url + '&r=admin/get-apply-formal-daili-list',
                success: function (res) {
                    console.log(res);
                    var json = res;
                    that.zsList = json.data;
                }
            });
        },
        success : function(id){
            this.success_id = id;
        },
        refuse : function(id){
            this.refuse_id = id;
        },
        del : function(id){
            this.del_id = id;
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
                        that.init();
                    }else{
                        alert(json.ret_msg);
                    }
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
                        that.init();
                    }else{
                        alert(json.ret_msg);
                    }
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
                        that.init();
                    }else{
                        alert(json.ret_msg);
                    }
                }
            });
        }
    }
});