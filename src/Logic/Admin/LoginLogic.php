<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/17
 * Time: 12:04
 */

namespace Logic\Admin;


use Constant\JWTKey;
use Exception\UserException;
use Firebase\JWT\JWT;
use Logic\BaseLogic;
use Model\AdminModel;

class LoginLogic extends BaseLogic
{

    public function adminLogin($admin,$pass)
    {
        $pass = md5($pass);
        $user = AdminModel::fetch("*", [
            "admin" => $admin,
            "password" => $pass
        ]);

        if(!$user)
        {
            UserException::UserNotFound();
        }

        return [
            'token' => $this->generateAdminJWT($user[0]['id'])
        ];
    }

    /**
     * 生成JWT
     * @param $uid
     * @return string
     */
    protected function generateAdminJWT($uid)
    {
        $token = [
            'iss' => JWTKey::ISS,
            'aud' => (string)$uid,
            'iat' => time(),
            'exp' => time() + (3600 * 24 * 365), // 有效期一年
        ];

        return JWT::encode($token, JWTKey::ADMIN_KEY, JWTKey::ALG);
    }
}