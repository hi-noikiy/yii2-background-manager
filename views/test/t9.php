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
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("myDiv").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open('GET', '/test/t2', true);
            xmlhttp.send();
        }
    </script>
</head>
<body>
    <h2>AJAX</h2>
    <button type="button" onclick="loadXMLDoc()">请求数据</button>
    <div id="myDiv"></div>
</body>
</html>