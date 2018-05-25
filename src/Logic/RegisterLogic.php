<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/24
 * Time: 16:14
 */

namespace Logic;


use Constant\CacheKey;
use Exception\UserException;
use Helper\Aliyun\DySDKLite\SignatureHelper;
use Model\UserModel;
use Qiutan\RedisHelper;

class RegisterLogic extends BaseLogic
{

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

        $key = CacheKey::REGISTER_CODE_KEY.":{$phone}";

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

        $content = false;

        // 此处可能会抛出异常，注意catch
        try{
            $content = $helper->request(
                $config['accessKeyId'],
                $config['$accessKeySecret'],
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

    public function validCode($phone, $code)
    {

        $key = CacheKey::REGISTER_CODE_KEY.":{$phone}";
        if(!redis()->exists($key))
        {
            UserException::codeNotFound();
        }

        $mycode = redis()->get($key);

        if($mycode != $code)
        {
            UserException::codeInvalid();
        }

        $data = [
            "phone" => $phone,
            "openid" => $phone,
            "unionid" => $phone,
            "openid_type" => 0
        ];

        $my_user = UserModel::getUserByPhone($phone);
        if($my_user)
        {
            UserException::phoneExists();
        }

        $user['id'] = UserModel::addUser($data);
        return [
            "token" => $this->generateJWT($user['id'])
        ];
    }

    public function addInfo($nickname, $password, $confirm)
    {
        $uid = UserLogic::$user['id'];
        if($password!==$confirm)
        {
            UserException::passwordNotConfirm();
        }

        $password = md5($password);

        $data = [
            "nickname" => $nickname,
            "password" => $password
        ];

        $where = ["id" => $uid];

        $user = UserModel::update($data, $where);
        if($user){
            return [];
        }
    }

    private function getCode()
    {
        return rand(100000, 999999);
    }
}