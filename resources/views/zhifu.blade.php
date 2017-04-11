<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

<form class="" action="/pay" method="post">
	{!!csrf_field()!!} 
	<input type="hidden" name="openid" value="{{$openid}}">
	<input type="hidden" name="oid" value="{{$oid}}">
	<input type="submit" name="" value="支付成功">
	

</form>
</body>
</html>