<?php

namespace App\Http\Controllers;
use Cart;
use Illuminate\Http\Request;
use DB;
use App\Order;
use App\Item;
use App\Fee;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
   public function insert(){
   	$data = [
['goods_name'=>'⽉季','goods_price'=>'23.8','goods_img'=>'goods.jpg'],
['goods_name'=>'玫瑰','goods_price'=>'45.6','goods_img'=>'goods.jpg'],
['goods_name'=>'桃花','goods_price'=>'30.8','goods_img'=>'goods.jpg'],
['goods_name'=>'妖姬','goods_price'=>'55.6','goods_img'=>'goods.jpg']
];
	DB::table('goods')->insert($data);
   }
   public function index(){
   	//获取全部的商品信息
   	$goods = DB::table('goods')->get();
   	return view('index',['goods'=>$goods]);
   }

   public function goods($gid){
   	$goods_info = DB::table('goods')->where('gid',$gid)->first();
   	return view('goods',['goods_info'=>$goods_info]);
   }

   public function cart(Request $res){

   	$goods_info = DB::table('goods')->where('gid',$res->gid)->first();
   	Cart::add($goods_info->gid,$goods_info->goods_name,$goods_info->goods_price,1,[]);
   	// var_dump(Cart::get($goods_info->gid));
   	return redirect('buy');

   }

   public function buy(){
   	 $totall = Cart::getContent();
   	 $xj = Cart::getSubTotal();
   	 // $totall->toArray();
   	 return view('cart',['cart'=>$totall,'xj'=>$xj]);
   }
   public function clear(){
   	Cart::clear();
   	return redirect('/');
   }

   public function done(Request $res){
   	//获取用户的登录信息 从session 中去
   	$u = session()->get('users');
   	//获取用户的openid
   	$openid = $u->getId();
   	//获取数据库里面的用户信息
   	$user_info = DB::table('users')->where('openid',$openid)->first();
   	//将获取到的信息 存入到order表里面
   	$order = new Order();
   	//共计多少钱
   	$totall = Cart::getTotal(); 
   	//写入数据库
   	$order->ordsn = date('Ymdhis',time()).mt_rand(10000,99999);
   	$order->uid = $user_info->uid;
   	$order->openid = $openid;
   	$order->xm = $res->xm;
   	$order->address = $res->address;
   	$order->money = $totall;
   	$order->tel = $res->mobile;
   	$order->ispay = 0;
   	$order->ordtime = time();
   	$order->save();

   	//获取购物车面里面的内容 可能是多个商品 要循环(商品快照)
   	//先获取到购物车里面的信息
   	$goods = Cart::getContent();
   	foreach($goods as $g){
   		$item = new Item();
   		//将获取的信息存入到item表中
   		$item->oid = $order->oid;//关联
   		$item->gid = $g->id;
   		$item->goods_name = $g->name;
   		$item->price = $g->price;
   		$item->amount = $g->quantity;
   		$item->save();
   	}
   	$this->clear(); 
   	return view('/zhifu',['oid'=>$order->oid,'openid'=>$openid]);

   }
  public function pay(Request $res){
  	//查询到该订单号
  	$oid = $res->oid;
  	//将该订单号的订单状态修改为1
  	DB::table('orders')->where('oid',$oid)->update(['ispay'=>1]);
  	$order = Order::where('oid',$oid)->first();
  	//产生利润
  	//谁买的
  	$users = DB::table('users')->where('openid',$res->openid)->first();
  	//定义一个数组 
  	$shouyi = [$users->p1,$users->p2,$users->p3];
  	$lr = [0.4,0.2,0.1];
  	foreach($shouyi as $k=>$v){
  		$fee = new Fee();
  		$fee->uid = $v;
  		$fee->money =$order->money * $lr[$k];
  		$fee->oid = $oid;
  		$fee->byid = $users->uid;
  		$fee->save();
  	}
  	return '购买成功';
  }
}