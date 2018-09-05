<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/8/31
 * Time: 16:08
 */

namespace Service;
use Exception\UserException;
use Qiutan\RedisHelper;

/**
 * 发送验证码
 * Class ValidCodeService
 * @package Service
 */
class ValidCodeService
{

    const EXPIRE_KEY = "qiuwen:valid_code:expire";
    const SEND_LIMIT_KEY = "qiuwen:valid_code:limit";
    const EXPIRE_TIME = 600;
    const SEND_LIMIT_TIME = 120;
    const TEMPLATE = "SMS_143925326";

    //定义状态
    const STATUS_SMS_NOT_FOUND = -1;
    const STATUS_LOCK = 0;
    const STATUS_SEND_FAIL = -2;
    const STATUS_SUCCESS = 1;

    /**
     * @var SmsService;
     */
    private $sms;

    /**
     * 发送验证码
     * @param $phone
     * @return array
     */
    public function sendCode($phone)
    {
        //是否注入Sms服务
        if(!$this->isSms()){
            return ["status" => self::STATUS_SMS_NOT_FOUND];
        }

        //已被锁定，无法发送
        if($time = $this->isLock($phone)){
            return ["status" => self::STATUS_LOCK, "lock_time" => $time];
        }

        $code = $this->setCode($phone);

        $result = $this->sms->sendSms($phone, self::TEMPLATE, ["code" => $code]);

        if(!$result){
            return ["status" => self::STATUS_SEND_FAIL];
        }else{
            $this->lock($phone);
            return ["status" => self::STATUS_SUCCESS];
        }

    }

    /**
     * 校验验证码
     * @param $phone
     * @param $code
     * @return bool
     */
    public function checkCode($phone, $code)
    {
        $key = self::EXPIRE_KEY.":{$phone}";
        if(!redis()->exists($key))
        {
            UserException::codeNotFound();
        }

        $mycode = redis()->get($key);

        if($mycode != $code)
        {
            UserException::codeInvalid();
        }

        return true;
    }

    /**
     * 获取验证码并设置过期时间
     * @param $phone
     * @return int
     */
    protected function setCode($phone)
    {
        //获取code并且更新redis中的code,重新设置有效期
        $code = $this->getCode();
        $key = self::EXPIRE_KEY.":{$phone}";
        if(redis()->exists($key)){
            redis()->del($key);
        }

        RedisHelper::set($key, $code, self::EXPIRE_TIME);

        return $code;
    }


    /**
     * 判断是否被锁定
     * @param $phone
     * @return bool|int
     */
    protected function isLock($phone)
    {
        $key = self::SEND_LIMIT_KEY.":{$phone}";

        if(redis()->exists($key)){
            $ttl = redis()->ttl($key);
            return $ttl;
        }

        return false;
    }

    /**
     * 锁定手机号
     * @param $phone
     * @return mixed
     */
    protected function lock($phone)
    {
        $key = self::SEND_LIMIT_KEY.":{$phone}";
        return RedisHelper::get($key, redis(), function(){

            return true;

        }, self::SEND_LIMIT_TIME);
    }

    /**
     * 注入发送短信服务
     * @param SmsService $sms
     * @return $this
     */
    public function setSms(SmsService $sms)
    {
        $this->sms = $sms;

        return $this;
    }

    /**
     * 判断是否注入短信服务
     * @return bool
     */
    protected function isSms()
    {
        return $this->sms instanceof SmsService;
    }

    /**
     * 生成验证码
     * @return int
     */
    protected function getCode()
    {
        return rand(100000, 999999);
    }
}