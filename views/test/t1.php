<html>
    <head>
        <meta charset="utf-8">
        <title>jquery 获取数据</title>
        <script src="https://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $('button').click(function(){
                    $('#div1').load('/test/t2', 'id=1', function(){
                        alert('lang');
                    });
                });
            });
        </script>
    </head>

    <body>
        <div id="div1">
            <h2>使用jquery修改文本内容</h2>
        </div>
        <button>修改</button>
    </body>
</html>