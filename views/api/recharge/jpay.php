<body onLoad='document.yeepay.submit();'>
<form name='yeepay' action="https://order.z.jtpay.com/jh-web-order/order/receiveOrder?p1_yingyongnum={$data['p1_yingyongnum']}&p2_ordernumber={$data['p2_ordernumber']}&p3_money={$data['p3_money']}&p6_ordertime={$data['p6_ordertime']}&p7_productcode={$data['p7_productcode']}&p8_sign={$data['p8_sign']}&p9_signtype={$data['p9_signtype']}&p25_terminal={$data['p25_terminal']}&paytype=zz" method='get' >
    <input type='hidden' name='p1_yingyongnum'			value="{$data['p1_yingyongnum']}">
    <input type='hidden' name='p2_ordernumber'			value="{$data['p2_ordernumber']}">
    <input type='hidden' name='p3_money'				value="{$data['p3_money']}">
    <input type='hidden' name='p6_ordertime'			 	value="{$data['p6_ordertime']}">
    <input type='hidden' name='p7_productcode'			value="{$data['p7_productcode']}">
    <input type='hidden' name='p8_sign'				value="{$data['p8_sign']}">
    <input type='hidden' name='p9_signtype'				value="{$data['p9_signtype']}">
    <input type='hidden' name='p25_terminal'				value="{$data['p25_terminal']}">
    <input type='hidden' name='paytype'				value='zz'>
</form>
</body>
