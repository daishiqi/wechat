<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;

class UserController extends Controller
{

    //---------------构造函数-------------------------------
    public $app = null;
    public $oauth = null;
    public function __construct (){
        $options = [
            'debug'  => true,
            'app_id' => 'wxc168659108ca6ace',
            'secret' => 'fbe53ca8d28eb6d6c726cfd06ad9329e',
            'token'  => 'daishiqi',
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file'  => 'E:\xampp\htdocs\fx\tmp\easywechat.log', 
            ],
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => '/login',
            ],  
    ];
        $this->app = new Application($options);
        $this->oauth = $this->app->oauth;
    }

    //-------------登录方法-----------------------------------------
   public function login(){
      // $oauth = $this->app->oauth;
        $user = $this->oauth->user();
        session()->put('users',$user);
        return redirect('center');
    //file_put_contents(public_path().'a.txt',$user->getId());
   }
   //----------用户登录页面------------------------------------------
   public function center(Request $res){
    if(!$res->session()->has('users')){
        $oauth = $this->app->oauth;
        return $oauth->redirect();
    }
    return 'hey!周总 ';
   }
   //--------------退出登录--------------------------------------------
   public function logout(Request $res){

    $res->session()->flush();
   }
}
