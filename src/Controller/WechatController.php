<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/1/18
 * Time: 9:33
 */

namespace Controller;


use Constant\WxappAccount;
use FastD\Http\ServerRequest;
use Model\UserModel;
use Wxapp\Wxapp;

class WechatController
{
    public function wxapp(ServerRequest $request)
    {
        $log = myLog("wxappCustomer");
        $open_id = $request->getParam("FromUserName");
        $app = $request->getParam("ToUserName");
        $log->addDebug("open_id:".$open_id);
        if(empty($open_id)){
            return [];
        }

        if(!isset(WxappAccount::$origin[$app])){
            return false;
        }
        $origin = WxappAccount::$origin[$app];
        $conf = config()->get($origin['conf']);

        $app_id = $conf['app_id'];
        $app_secret = $conf['app_secret'];
        $log->addDebug("conf:".json_encode($conf));
        $wxapp = new Wxapp($app_id,$app_secret);

        $path = realpath("./a.jpg");

        $log->addDebug("path:".$path);
        $file = new \CURLFile($path);
        $log->addDebug("file:".json_encode($file, JSON_FORCE_OBJECT));
        $result = $wxapp->bindRedis(redis())->uploadImage($file);
        $log->addDebug("file_result:".json_encode($result));

        $media_id = $result['media_id'];

        $data = [
            "touser" => $open_id,
            "msgtype" => "image",
            "image" => [
                "media_id" => $media_id
            ]
        ];

        $result = $wxapp->bindRedis(redis())->sendCustomerMsg($data);

        $log->addDebug("Result:".$result);


    }
}