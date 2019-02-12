<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script>
        function loadXMLDoc()
        {
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    document.getElementById("myDiv").innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open('GET', '/test/t2', true);
            xmlhttp.send();
        }
    </script>
</head>
<body>
    <div id="myDiv"><h2>使用ajax修改该文本</h2></div>
    <button type="button" onclick="loadXMLDoc()">修改内容</button>
</body>