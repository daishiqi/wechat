<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
   public function index(){

    //----HTTP post请求需要的参数信息--------------------------
    //获取$code
    $code = $_GET['code'];
    //回调地址
    $post_url = 'https://api.weibo.com/oauth2/access_token';
    $data = [
        'client_secret' => '38b9ed87af4bf53c1d13b7d2fe2fdf66',
        'redirect_uri' => 'http://daisy.ittun.com/weibo',
        'grant_type' => 'authorization_code',
        'client_id' => '1995086425',
        'code'=>$code,
    ];
    
    //------------------POST请求 curl方式---------------------------------
    $info = $this->curls($post_url,'post',$data) ;
    //将获取到的info进行转换为数组
    $i = json_decode($info,true);
    $user_url = 'https://api.weibo.com/2/users/show.json?access_token='.$i['access_token'].'&uid='.$i['uid'];
    //获取到用户微博信息
    $user_info = file_get_contents($user_url);
    var_dump($user_info);

   }

   //--------------------------post方式发送请求---------------------------
   public function curls($post_url,$type,$data){
    //1 初始化
    $curl = curl_init();

    //2 设置选项
    curl_setopt($curl,CURLOPT_URL,$post_url);
    curl_setopt($curl,CURLOPT_HEADER,0);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);

    //设置为post方式发送
    if($type=='post'){
        curl_setopt($curl,CURLOPT_POST,1); 
        //此处的$date要进行转换
        curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($data));

    }

    //3 执行并返回
    $info = curl_exec($curl);

    //4,关闭
    curl_close($curl);
    return $info;
    
    
   }
}
