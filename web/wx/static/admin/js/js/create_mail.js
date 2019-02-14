/**
 * Created by lenovo on 2017/3/25.
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

$('#distpicker').distpicker({
    autoSelect: false
});


//创建代理
var vm = new Vue({
    el : 'body',
    data : {
        userid : '',
        daili_level : 3,
        username : '',
        truename : '',
        tel : '',
        province : '',
        city : '',
        district : '',
        address : ''
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
        addbtn : function(){
            if ( this.title == '') {
                $('#title').attr('placeholder', '邮件标题不能为空！');
                $('#title').focus();
                return false;
            } else if ( this.content == '') {
                $('#content').attr('placeholder', '邮件内容不能为空！');
                $('#content').focus();
                return false;
            } else{
                $('#addbtn').attr('data-target','#add');
            }
        },
        add_daili : function(){
            var dateinfo3 = $('#dateinfo3').val();
            var dateinfo4 = $('#dateinfo4').val();
            $.ajax({
                type: 'post',
                url: base_url + '&r=admin/create-mail&gid=524803',
                data: {
                    target: this.target,
                    userid: this.userid,
                    title: this.title,
                    content: this.content,
                    enclosure: this.enclosure,
                    dateinfo3: dateinfo3,
                    pop: this.pop,
                    dateinfo4: dateinfo4
                },
                success: function (res) {
                    var json = res;
                    if (json.data.ret_code == 0) {
                        Modal.alert({msg: '创建成功,请在邮件列表查看'}).on(function(){
                            location.reload();
                        });
                    } else if (json.data.ret_code == -3) {
                        Modal.alert({msg : '发送时间必须大于当前时间'});
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
});
