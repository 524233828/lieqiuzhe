<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/3
 * Time: 10:24
 */

namespace Logic;


use Constant\CacheKey;
use Exception\BaseException;
use Exception\UserException;
use Helper\Aliyun\DySDKLite\SignatureHelper;
use Illuminate\Support\Facades\Redis;
use Model\UserModel;
use Qiutan\RedisHelper;

class UserCenterLogic extends BaseLogic
{

    public function getInfo()
    {
        $uid = UserLogic::$user['id'];
    }

    public function updateUserInfo($nickname, $avatar, $sex)
    {
        $id = UserLogic::$user['id'];

        $result = UserModel::update([
            "nickname" => $nickname,
            "avatar" => $avatar,
            "sex" => $sex
        ],["id" => $id]);

        if($result){
            return [];
        }else{
            BaseException::SystemError();
        }
    }

    public function bindPhone()
    {

    }

    public function sendCode($phone)
    {

        $config = config()->get("sms");
        $helper = new SignatureHelper();

        $params["PhoneNumbers"] = $phone;

        $params["SignName"] = $config['signName'];

        $params["TemplateCode"] = "SMS_126650401";

        $my_user = UserModel::getUserByPhone($phone);
        if($my_user)
        {
            UserException::phoneExists();
        }

        $key = CacheKey::BIND_PHONE_CODE_KEY.":{$phone}";

        if(redis()->exists($key)){
            UserException::sendCodeTooMuch();
        }

        $time_out = 600;

        $code = RedisHelper::get($key, redis(), function(){

            return $this->getCode();

        }, $time_out);

        $params['TemplateParam'] = [
            "code" => $code,
        ];

        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        $content = false;

        // 此处可能会抛出异常，注意catch
        try{
            $content = $helper->request(
                $config['accessKeyId'],
                $config['accessKeySecret'],
                "dysmsapi.aliyuncs.com",
                array_merge($params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" =>  "SendSms",
                    "Version" => "2017-05-25",
                ))
            );
        }catch (\Exception $e){
            UserException::sendCodeFail();
        }
        if($content)
        {
            return [];
        }
    }
}