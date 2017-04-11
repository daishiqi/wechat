<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;
use App\User;

class IndexController extends Controller
{
    public $app = null;
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
        $oauth = $this->app->oauth;
    }
    public function index(){
        // echo 123;exit;

        $server = $this->app->server;

        $server->setMessageHandler(function($msg){
            // return '欢迎关注西岭老师的测试号';
            if($msg->MsgType == 'event'){
                return $this->sj($msg);
            }
        });

        $response = $server->serve();

        // ->serve();
        // 将响应输出
        return $response;
    }

    //事件处理
    public function sj($msg){
        $user_model = new User();
        $openid = $msg->FromUserName;
        $info = $user_model->where('openid',$openid)->first();

        if($msg->Event == 'subscribe'){
            if(!$info){


                //生成场景二维码；
                $this->create_code($openid);

                $key_code = $msg->EventKey;//你扫的二维码的参数
                // return $key_code;
                if($key_code){
                    $p_openid = str_replace('qrscene_','',$key_code);
                    $um = new User();
                    $p_info = $um->where('openid',$p_openid)->first();
                    $user_model->p1 = $p_info->uid;
                    $user_model->p2 = $p_info->p1;
                    $user_model->p3 = $p_info->p2;
                }

                $user_server = $this->app->user;
                $user = $user_server->get($openid);
                $user_name = $user->nickname;
                $user_model->name = $user_name;
                $user_model->openid = $openid;
                $user_model->subtime = time();

                $user_model->save();
            }else{
                $info->status = 1;
                $info->save();
            }

            return '恭喜你没有bug!';
        }elseif ($msg->Event == 'unsubscribe') {
            if($info){
                $info->status = 0;
                $info->save();
            }
        }
    }

    public function mkd(){
        $today = date('/Ymd/',time());
        if(!is_dir(public_path().$today)){
            mkdir(public_path().$today,0777,true);
        }
        return public_path().$today;
    }

    public function create_code($openid){
        $qrcode = $this->app->qrcode;
        $result=$qrcode->forever($openid);
        $ticket = $result->ticket;
        $url = $qrcode->url($ticket);
        $content = file_get_contents($url); // 得到二进制图片内容
        $p = $this->mkd();
        file_put_contents($p.$openid.'.jpg', $content); // 写入文件
    }
}
