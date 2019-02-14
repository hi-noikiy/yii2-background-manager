<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>百万棋牌室代理平台</title>
    <link rel="stylesheet" href="static/mobile/agent/css/common.min.css">
    <script type="text/javascript" src="static/mobile/agent/lib/jquery.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/sky.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/common.js"></script>
</head>
<body>
<div class="panel panel-index">
    <div class="nav-wrap">
        <div class="nav">
            <a onclick="urlto('wx/route&url=index_all')"></a>
            <h1>举报/反馈</h1>
        </div>
    </div>
    
   <!-- 头部信息 -->
    <?php include 'base_head.php' ?>

    <div class="assign-card-wrap">
        <div class="assign-card assign-agency">
            <div class="tab-container">
               <!-- <div class="tab-nav" style="border-bottom: none;">
                    <a onclick="urlto('wx/route&url=nw_question')"  class="on" >举报/反馈</a>
                    <a onclick="urlto('wx/route&url=nw_questionlist')" >举报/反馈记录</a>
                </div>-->
                <div class="tab-main" style="padding-top:0.1rem;">
                    <div class="card-add" style="padding-bottom: 0.5rem;">
                        <div class="form-wrap form-wrap-horizontal">
                            <div class="form-group" style="padding:0 0.2rem 0 0;">
                                <label style="width: 70px">内容</label>
                                <div class="ipt-container" style="overflow: hidden;">
                                    <textarea
                                            style="width: 100%; height: 180px; border: 1px #ccc solid; border-radius: 0.1rem; line-height: 0.4rem; padding: 0.1rem; color: #111"
                                            id="content"></textarea>
                                </div>
                            </div>
                            <div class="form-group" style="padding:0 0.2rem 0 0;">
                                <label style="width: 70px">上传截图</label>
                                <div class="ipt-container upload-file-box" style="overflow: hidden;">
                                    <div class="upload-img-box"></div>
                                    <div class="upload-file-box">
                                        <input type="file" class="upload-photo-file" name="file" onchange="imgPreview(this)">
                                        <p class="red font-size-12 upload-photo-word">点击上传照片</p>
                                    </div>
                                    <span id="tip1" style="color:red"></span>
                                    <br/><img id="custom_img1" src=""
                                              style="max-height:70px;max-width:200px;display:none"><br/>
                                    <input type="hidden" value="" id="img1"/>
                                </div>
                            </div>
                            <div class="form-btn" style="width:80%;margin:0 auto;">
                                <button class="btn-submit" onclick="add()">提交举报/反馈</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="index-wrap">
        <div class="footer-note">
            <p style="text-align:center"><span>百万棋牌室代理平台</span></p>
        </div>
    </div>
    <!--alert弹框-->
    <div class="alert-mask popup-mask" id="alerttip" style="display: none;z-index: 1001;"></div>
    <div class="alert-box popup popup-agency-confirm" id="alertbody" style="display: none;z-index: 1002;">
        <div class="main">
            <div class="alert-tip" id="alerttitle" style="text-align: center;line-height: 0.5rem;padding:0.2rem;">
                请填写正确的数据
            </div>
            <div class="dbtn"><input type="button" value="确定" onclick="closealert();"></div>
        </div>
    </div>
    <!---->
    <!--loading组件-->
    <div class="loading-box" id="loading">
        <img src="static/mobile/agent/images/loading.gif" alt="" class="img">
    </div>
    <!---->
    <script type="text/javascript" src="static/mobile/script/ajax.js"></script>
    <script src="static/mobile/script/ajaxfileupload.js"></script>
    <script>
        //    alert弹框出现
        function showalert($msg) {
            $("#alerttitle").html($msg);
            $('#alerttip').fadeIn();
            $('#alertbody').fadeIn();
        }
        //    关闭alert弹框
        function closealert() {
            $('#alerttip').fadeOut();
            $('#alertbody').fadeOut();
        }
      
        // 图片上传
        var imgArray = [];
        var images = [];
        function imgPreview(fileDom){
            //判断是否支持FileReader
            if (window.FileReader) {
                var reader = new FileReader();
            } else {
                alert("您的设备不支持图片预览功能，如需该功能请升级您的设备！");
            }

            //获取文件
            var file = fileDom.files[0];
            var imageType = /^image\//;
            //是否是图片
            if (!imageType.test(file.type)) {
                alert("请选择图片！");
                return;
            }
            //读取完成
            reader.onload = function(e) {


                $("#loading").show();
                
                var maxSize = 1000*1024;
                if (file.size > maxSize) {
                    showalert('图片过大，请重新上传');
                    $('#loading').hide();
                    return;
                }

                $.ajax({
                    url : base_url + '&r=qiniu/upload' + sign,
                    type : 'post',
                    async: false,
                    data : {image_str : e.target.result},
                    success : function(res){
                        $("#loading").hide();
                        //console.log(res);
                        if(res.ret_code ==0){
                            images.push(res.data);
                        }
                    }
                });

                //获取图片dom
                var imgBox = document.getElementsByClassName("upload-img-box")[0];
                var img = document.createElement('img');
                img.src = e.target.result;
                
                img.style.height = '1rem';
                img.style.margin = '0 0.1rem 0.1rem 0';
                imgArray.push(e.target.result)
                imgBox.appendChild(img);
                console.log(imgArray);

            };
            reader.readAsDataURL(file);
        }

        function add(){
            $("#loading").show();
            var content = $("#content").val();
            $.ajax({
                url : base_url + '&r=wxdaili/question' + sign,
                type : 'post',
                data : {content:content,images:images},
                success : function(res){
                    //console.log(res);
                    $("#loading").hide();
                    showalert(res.ret_msg);
                }
            });
        }

    </script>
</div>
</body>
</html>