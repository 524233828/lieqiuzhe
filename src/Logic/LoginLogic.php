<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 15:33
 */

namespace Logic;


use Exception\UserException;
use Model\UserModel;
use Wxapp\Wxapp;

class LoginLogic extends BaseLogic
{

    public function login($login_type, $params)
    {
        switch ($login_type){
            case 0://手机登陆
                break;
            case 1://微信登录
                break;
            case 2://QQ登录
                break;
            case 3://微博登录
                break;
            case 4://小程序登录
                $uid = $this->wxapp($params);
                break;
            default:
                break;
        }

        $token = $this->generateJWT($uid);

        return ["token" => $token];
    }

    /**
     * @param $params
     * @return int|string
     */
    private function wxapp($params)
    {
        $conf = config()->get("wxapp");

        $wxapp = new Wxapp($conf['app_id'], $conf['app_secret']);

        $code = $params['code'];

        $result = $wxapp->login($code);

        if(!isset($result['open_id'])){
            UserException::LoginFail();
        }

        if(!isset($result['unionid'])||empty($result['unionid'])||!$user = UserModel::getUserByUnionId($result['unionid'])){
            $data = [
                "openid" => $result['openid'],
                "unionid" => isset($result['unionid'])?$result['unionid']:"",
                "openid_type" => 1,
            ];
            $my_user = UserModel::getUserByOpenId($data['openid']);
            if(!$my_user)
            {
                $my_user['id'] = UserModel::addUser($data);
            }

        }else{
            $my_user['id'] = $user['id'];
        }

        if(!$my_user['id']){
            UserException::LoginFail();
        }

        return $my_user['id'];
    }

}