<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/1/18
 * Time: 9:33
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Model\UserModel;
use Wxapp\Wxapp;

class WechatController
{
    public function wxapp(ServerRequest $request)
    {
        $log = myLog("wxappCustomer");
        $open_id = $request->getParam("FromUserName");
        $log->addDebug("open_id:".$open_id);
        if(empty($open_id)){
            return [];
        }

        $user = UserModel::getUserByOpenId($open_id);
        if($user['openid_type'] == 4){
            $conf = config()->get("wxapp");
        }else{
            $conf = config()->get("zuqiubisai1");
        }

        $app_id = $conf['app_id'];
        $app_secret = $conf['app_secret'];
        $wxapp = new Wxapp($app_id,$app_secret);

        $data = [
            "touser" => $open_id,
            "msgtype" => "link",
            "link" => [
                "title" => "【稳稳收米】欢迎关注夜猫情报局！",
                "description" => "夜猫情报局一直致力于给大家提供迅捷实用的足彩情报 分享足彩技巧干货，帮助大家更稳地收米",
                "url" => "http://mp.weixin.qq.com/s/yfgZZzxVOfwI9a37O7-Uig",
                "thumb_url" => "http://www.ym8800.com/upload/maozhentan.png"
            ]
        ];

        $result = $wxapp->bindRedis(redis())->sendCustomerMsg($data);

        $log->addDebug("Result:".$result);


    }
}