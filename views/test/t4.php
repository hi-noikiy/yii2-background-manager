<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>菜鸟教程(runoob.com)</title>
    <script src="https://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('button').click(function(){
                $.get('/test/t5', function(data, status){
                    alert('数据：' + data + '状态：' + status);
                });
            });
        });
    </script>
</head>
<body>

<button>发送一个 HTTP GET 请求并获取返回结果</button>

</body>
</html>