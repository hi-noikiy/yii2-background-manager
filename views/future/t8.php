<table lay-filter="demo">
    <thead>
    <tr>
        <th lay-data="{field:'username', width:100}">昵称</th>
        <th lay-data="{field:'experience', width:80, sort:true}">积分</th>
        <th lay-data="{field:'sign'}">签名</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>贤心1</td>
        <td>66</td>
        <td>人生就像是一场修行a</td>
    </tr>
    <tr>
        <td>贤心2</td>
        <td>88</td>
        <td>人生就像是一场修行b</td>
    </tr>
    <tr>
        <td>贤心3</td>
        <td>33</td>
        <td>人生就像是一场修行c</td>
    </tr>
    </tbody>
</table>

<script>
    layui.use('table', function(){
        var table = layui.table;

        table.init('demo', {
            height: 350
            ,limit: 10
        })
    });
</script>