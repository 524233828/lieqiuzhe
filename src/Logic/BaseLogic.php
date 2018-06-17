<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/18
 * Time: 13:32
 */

namespace Logic;
use Constant\JWTKey;
use Firebase\JWT\JWT;

/**
 * 逻辑层，做逻辑处理进行数据组装，供控制层调用，单例模式
 * Class BaseLogic
 * @package Logic
 */
class BaseLogic
{

    private static $instance;

    final private function __construct()
    {
    }

    /**
     * 获取当前对象实例
     * @return $this
     */
    public static function getInstance()
    {
        $class_name = get_called_class();
        if (!isset(self::$instance[$class_name]) || !self::$instance[$class_name] instanceof self) {
            self::$instance[$class_name] = new static;
        }

        return self::$instance[$class_name];
    }

    /**
     * 生成JWT
     * @param $uid
     * @return string
     */
    protected function generateJWT($uid)
    {
        $token = [
            'iss' => JWTKey::ISS,
            'aud' => (string)$uid,
            'iat' => time(),
            'exp' => time() + (3600 * 24 * 365), // 有效期一年
        ];

        return JWT::encode($token, JWTKey::KEY, JWTKey::ALG);
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
