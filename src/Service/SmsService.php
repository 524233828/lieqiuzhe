<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/8/31
 * Time: 14:15
 */

namespace Service;


use Exception\UserException;
use Helper\Aliyun\DySDKLite\SignatureHelper;

/**
 * 发送短信
 * Class SmsService
 * @package Service
 */
class SmsService
{

    private $access_key_id;
    private $access_key_secret;
    private $sign_name;
    /**
     * @var SignatureHelper
     */
    private $helper;

    public function __construct($access_key_id, $access_key_secret, $sign_name)
    {
        $this->access_key_id = $access_key_id;
        $this->access_key_secret = $access_key_secret;
        $this->sign_name = $sign_name;
    }

    public function sendSms($phone, $template_code, array $template_param)
    {
        if(!$this->isSetHelper()){
            return false;
        }

        //设置参数
        $params["PhoneNumbers"] = $phone;

        $params["SignName"] = $this->sign_name;

        $params["TemplateCode"] = $template_code;

        if(!empty($template_param) && is_array($template_param)) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        //发送短信
        $content = false;

        // 此处可能会抛出异常，注意catch
        try{
            $content = $this->helper->request(
                $this->access_key_id,
                $this->access_key_secret,
                "dysmsapi.aliyuncs.com",
                array_merge($params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" =>  "SendSms",
                    "Version" => "2017-05-25",
                ))
            );
        }catch (\Exception $e) {
            UserException::sendCodeFail();
        }

        if($content){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 注入助手类
     * @param SignatureHelper $helper
     * @return $this
     */
    public function setSignatureHelper(SignatureHelper $helper)
    {
        $this->helper = $helper;
        return $this;
    }

    /**
     * 判断是否已注入助手类
     * @return bool
     */
    protected function isSetHelper(){

        return ($this->helper instanceof SignatureHelper);

    }
}