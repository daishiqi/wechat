<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<link rel="stylesheet" href="/css/bootstrap.min.css">
<style>
.goods {
    margin: 2% 0;
}
.goods img {
    width:90%;
}
#navb li {
    float: left;
    width: 33%;
    text-align: center;
    list-style: none;
    line-height: 50px;
}
body{
    padding-bottom: 70px;
}
</style>
<body>
    <h1>简洁大气的商城</h1>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 goods">
                <h2>购物车结算</h2>
                <table class="table">
                    <tr>
                        <th>商品</th>
                        <th>价格</th>
                        <th>数量</th>
                    </tr>

                    @foreach($cart as $c)
                    <tr>
                        <td>{{$c->name}}</td>
                        <td>{{$c->price}}</td>
                        <td>{{$c->quantity}}</td>
                    </tr>
                   @endforeach

                    <tr>
                        <td colspan="3">小计:&yen;{{$xj}}元</td>
                    </tr>
                </table>
                <form action="/done">
                    <div class="form-group">
                      <input type="text" class="form-control" name="address" placeholder="收货地址">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" name="xm" placeholder="收货人姓名">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" name="mobile" placeholder="手机号">
                    </div>
                    <input class="btn btn-primary" type="submit" value="确认下单">
                    <a class="btn btn-primary" href="/clear">清空购物车</a>
                </form>
            </div>
        </div>
        <div class="col-xs-12 navbar-fixed-bottom">
          <ul class="navbar-fixed-bottom navbar-default row" id="navb">
            <li><a href="/">首页</a></li>
            <li><a href="/home">个人中心</a></li>
            <li><a href="">帮助</a></li>
          </ul>
        </div>
    </div>
</body>
<!-- <script src="http://libs.useso.com/js/jquery/2.1.0/jquery.min.js"></script> -->
<script src="/js/bootstrap.min.js"></script>
</html>