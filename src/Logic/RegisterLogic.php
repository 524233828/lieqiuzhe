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

        $my_user = UserModel::getUserByPhone($phone);
        if($my_user)
        {
            UserException::phoneExists();
        }

        //之前提交过手机号信息，用上次创建的ID生成token
        $my_user = UserModel::getUserByNotRigisterFinish($phone);

        if($my_user){
            return [
                "token" => $this->generateJWT($my_user['id'])
            ];
        }

        //初步创建手机号账号，但状态为0，表示未注册完成
        $data = [
            "phone" => $phone,
            "openid" => $phone,
            "unionid" => $phone,
            "status" => 0,
            "openid_type" => 0
        ];

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
            "password" => $password,
            "status" => 1
        ];

        $where = ["id" => $uid];

        $user = UserModel::update($data, $where);
        if($user){
            return [];
        }
    }


}