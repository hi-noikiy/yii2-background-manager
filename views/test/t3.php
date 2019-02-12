<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="https://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("button").click(function(){
                $("#div1").load("/test/t22", function(responseTxt, statusTxt, xhr){
                    alert(xhr);

                    if (statusTxt == 'success') {
                        alert('外部内容加载成功');
                    }
                    if (statusTxt == 'error') {
                        alert('Error ' + xhr.status + ":" + xhr.statusText);
                    }
                })
            });
        });
    </script>
</head>
<body>

<div id="div1"><h2>使用 jQuery AJAX 修改该文本</h2></div>
<button>获取外部内容</button>

</body>
</html>