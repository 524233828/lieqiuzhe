<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/8/31
 * Time: 18:38
 */

namespace Logic;


use Helper\Aliyun\DySDKLite\SignatureHelper;
use Service\SmsService;
use Service\ValidCodeService;

class ValidCodeLogic extends BaseLogic
{
    public function sendCode($phone)
    {
        //sms配置
        $config = config()->get("sms");

        //发送短信助手类
        $helper = new SignatureHelper();

        //短信服务
        $sms = new SmsService($config['accessKeyId'], $config['accessKeySecret'],$config['signName']);

        $sms->setSignatureHelper($helper);

        //验证码服务
        $valid_code = new ValidCodeService();

        $result = $valid_code->setSms($sms)->sendCode($phone);

        switch($result['status']){
            case ValidCodeService::STATUS_SUCCESS:
                return [];
                break;
            case ValidCodeService::STATUS_LOCK:
                return ["expired_time" => $result['lock_time']];
                break;
            case ValidCodeService::STATUS_SEND_FAIL:
            case ValidCodeService::STATUS_SMS_NOT_FOUND:
            default:
                error(1800);

        }
    }
}