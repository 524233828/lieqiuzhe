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


    public function bindPhone($phone, $code)
    {
        $key = CacheKey::BIND_PHONE_CODE_KEY.":{$phone}";
        if(!redis()->exists($key))
        {
            UserException::codeNotFound();
        }

        $mycode = redis()->get($key);

        if($mycode != $code)
        {
            UserException::codeInvalid();
        }

        $my_user = UserModel::getUserByPhone($phone);
        if($my_user)
        {
            UserException::phoneExists();
        }

        $data = [
            "phone" => $phone,
        ];

        $result = UserModel::updateUserByUid(UserLogic::$user['id'], $data);
        if($result){
            return [];
        }else{
            UserException::SystemError();
        }

    }

    public function sendCode($phone, $type)
    {

        $config = config()->get("sms");
        $helper = new SignatureHelper();

        $params["PhoneNumbers"] = $phone;

        $params["SignName"] = $config['signName'];

        $params["TemplateCode"] = "SMS_126650401";

        $key = $type.":{$phone}";

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

    public function validCode($phone, $code)
    {

        $key = CacheKey::FORGET_PHONE_CODE_KEY.":{$phone}";
        if(!redis()->exists($key))
        {
            UserException::codeNotFound();
        }

        $mycode = redis()->get($key);

        if($mycode != $code)
        {
            UserException::codeInvalid();
        }

        $my_user = UserModel::getUserByPhone($phone);

        if($my_user){
            return [
                "token" => $this->generateJWT($my_user['id'])
            ];
        }else{
            UserException::UserNotFound();
        }

    }

    public function updateUserPassword($password, $re_password)
    {
        $id = UserLogic::$user['id'];
        if(!$password || !$re_password){
            UserException::emptyPassowrd();
        }

        if($password != $re_password){
            UserException::twicePasswordNotEqual();
        }

        $result = UserModel::update([
            "password" => md5($password),
        ],["id" => $id]);

        if($result){
            return [];
        }else{
            BaseException::SystemError();
        }
    }

}