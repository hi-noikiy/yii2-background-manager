/**
 * Created by lenovo on 2017/3/26.
 */

//申请状态 自定义过滤器
Vue.filter('status', function (value) {
    switch(value) {
        case '0':
            return '已拒绝';
            break;
        case '1':
            return '申请中';
            break;
        case '2':
            return '申请通过';
            break;
    }
});

//图片展示处理 自定义过滤器
Vue.filter('imgdl', function (value) {
    // alert(value);
    if( value != '' && value != null){
        var img_arr = value.split(",");
        var img_str = '';
        var img_src = '';
        for (var i = 0; i < img_arr.length; i++) {
            img_src = 'http://oi61q7eoj.bkt.clouddn.com/' + img_arr[i];
            img_str += '<img src="' + img_src + '" data-toggle="modal" data-target="#tobig" title="点击放大" width="100" height="100">';
        }
        return img_str;
    }else{
        return '';
    }
});

var vm = new Vue({
    el : 'body',
    ready : function(){
        this.init();
    },
    data : {
        List : '',
        cur : '',
        bigimg : ''
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
        init: function () {
            var that = this;
            $.ajax({
                type: 'get',
                url: base_url + '&r=admin/show-appay-daili',
                success : function(res){
                    var json = res;
                    if( json.ret_code == 0 ){
                        that.List = json.data;
                    }
                }
            });
        },
        clone : function(item){
            //复制克隆当前审核信息
            this.cur = JSON.parse(JSON.stringify(item));
        },
        submit : function(cur){
            var that = this;
            $.ajax({
                type: 'post',
                url: base_url + '&r=admin/audit',
                data: {
                    ID : cur.ID,
                    player_index : cur.PLAYER_INDEX,
                    status : $('#status').val(),
                    remark : $('#remark').val()
                },
                success: function (res) {
                    var json = res;
                    if (json.ret_code == 0) {
                        Modal.alert({msg: '提交成功'}).on(function(){
                            that.init();
                        });
                    } else {
                        Modal.alert({msg: json.ret_msg});
                    }
                },
                error : function(){
                    Modal.alert({msg: '请求失败'});
                }
            });
        },
        tobig : function(ev){
            //console.log(ev);
            var src = ev.srcElement || ev.target;   // 获取触发事件的源对象  谷歌/火狐
            var imgsrc = '';

            if( ev.srcElement ){         //非火狐

                if( src.currentSrc ){
                    console.log('谷歌，360');
                    imgsrc = ev.srcElement.currentSrc;
                }else{
                    console.log('IE');
                    imgsrc = ev.srcElement.src;
                }

            }else{
                console.log('火狐');
                imgsrc = ev.target.currentSrc
            }

            this.bigimg = imgsrc;
        }
    }
});