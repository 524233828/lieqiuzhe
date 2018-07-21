<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 15:33
 */

namespace Logic;


use Exception\BaseException;
use Exception\UserException;
use Helper\Login\Provider\QQProvider;
use Model\UserModel;
use Overtrue\Socialite\AccessToken;
use Overtrue\Socialite\SocialiteManager;
use Symfony\Component\HttpFoundation\Request;
use Wxapp\Wxapp;

class LoginLogic extends BaseLogic
{

    const PHONE_LOGIN = 0;
    const WECHAT_LOGIN = 1;
    const QQ_LOGIN = 2;
    const WEIBO_LOGIN = 3;
    const WXAPP_LOGIN = 4;
    const ZUQIUBISAI_LOGIN = 5;
    const SHIJIEBEI_LOGIN = 6;

    public function login($login_type, $params)
    {
        switch ($login_type){
            case self::PHONE_LOGIN://手机登陆
                $uid = $this->mobile($params);
                break;
            case self::WECHAT_LOGIN://微信登录
                $uid = $this->wechat($params);
                break;
            case self::QQ_LOGIN://QQ登录
                $uid = $this->qq($params);
                break;
            case self::WEIBO_LOGIN://微博登录
                $uid = $this->weibo($params);
                break;
            case self::WXAPP_LOGIN://赛事比分小程序
                $uid = $this->wxapp($params);
                break;
            case self::ZUQIUBISAI_LOGIN://小程序登录
                $uid = $this->zuQiuBiSai1($params);
                break;

            case self::SHIJIEBEI_LOGIN://小程序登录
                $uid = $this->shijiebei($params);
                break;

            default:
                break;
        }

        if(!isset($uid)){
            UserException::LoginFail();
            return false;
        }

        $token = $this->generateJWT($uid);

        return ["token" => $token];
    }

    /**
     * 赛事比分小程序
     * @param $params
     * @return int|string
     */
    private function wxapp($params)
    {
        $log = myLog("wxapp_login");
        $conf = config()->get("wxapp");

        $log->addDebug("conf:".json_encode($conf));

        $wxapp = new Wxapp($conf['app_id'], $conf['app_secret']);

        $code = $params['code'];

        $log->addDebug("code:".$code);
        $result = $wxapp->login($code);
        $log->addDebug("result:".json_encode($result));
        if(!isset($result['openid'])){
            UserException::LoginFail();
        }

        if(!isset($result['unionid'])||empty($result['unionid'])||!$user = UserModel::getUserByUnionId($result['unionid'])){
            $data = [
                "openid" => $result['openid'],
                "unionid" => isset($result['unionid'])?$result['unionid']:"",
                "openid_type" => 4,
            ];
            $my_user = UserModel::getUserByOpenId($data['openid']);
            if(!$my_user)
            {
                $my_user['id'] = UserModel::addUser($data);
            }

        }else{
            $my_user['id'] = $user['id'];
        }

        $log->addDebug("my_user:".json_encode($my_user));
        if(!$my_user['id']){
            UserException::LoginFail();
        }

        return $my_user['id'];

    }

   /**
    * 足球比赛1小程序
    * @param $params
    * @return int|string
    */
    private function zuQiuBiSai1($params)
    {
        $log = myLog("wxapp_login");
        $conf = config()->get("zuqiubisai1");

        $log->addDebug("conf:".json_encode($conf));

        $wxapp = new Wxapp($conf['app_id'], $conf['app_secret']);

        $code = $params['code'];

        $log->addDebug("code:".$code);
        $result = $wxapp->login($code);
        $log->addDebug("result:".json_encode($result));
        if(!isset($result['openid'])){
            UserException::LoginFail();
        }

        if(!isset($result['unionid'])||empty($result['unionid'])||!$user = UserModel::getUserByUnionId($result['unionid'])){
            $data = [
                "openid" => $result['openid'],
                "unionid" => isset($result['unionid'])?$result['unionid']:"",
                "openid_type" => 5,
            ];
            $my_user = UserModel::getUserByOpenId($data['openid']);
            if(!$my_user)
            {
                $my_user['id'] = UserModel::addUser($data);
            }

        }else{
            $my_user['id'] = $user['id'];
        }

        $log->addDebug("my_user:".json_encode($my_user));
        if(!$my_user['id']){
            UserException::LoginFail();
        }

        return $my_user['id'];

    }

    /**
     * 微信登录
     * @param $params
     * @return mixed
     */
    private function wechat($params)
    {
        $log = myLog("wechat_login");

        $request = Request::createFromGlobals();
        $socialite = new SocialiteManager(config()->get("socialite"),$request);

        $token = null;
        if(isset($params['token']) && !empty($params['token']) && isset($params['openid']) && !empty($params['openid'])){
            $attributes['access_token'] = $params['token'];
            $attributes['openid'] = $params['openid'];
            $token = new AccessToken($attributes);
        }
        $user = $socialite->driver("wechat")->user($token);

        $log->addDebug("user",$user->toArray());

        $result = $user->toArray();

        if(!isset($result['original']['unionid'])||empty($result['original']['unionid'])||!$user = UserModel::getUserByUnionId($result['original']['unionid'])){
            $data = [
                "openid" => $result['id'],
                "unionid" => isset($result['original']['unionid'])?$result['original']['unionid']:"",
                "openid_type" => 1,
                "nickname" => $result['nickname'],
                "avatar" => $result['avatar'],
                "sex" => $result['original']['sex'],
                "language" => $result['original']['language'],
                "city" => $result['original']['city'],
                "country" => $result['original']['country'],
                "province" => $result['original']['province'],
            ];
            $my_user = UserModel::getUserByOpenId($data['openid']);
            if(!$my_user)
            {
                $my_user['id'] = UserModel::addUser($data);
            }

        }else{
            $my_user['id'] = $user['id'];
        }

        $log->addDebug("my_user:".json_encode($my_user));
        if(!$my_user['id']){
            UserException::LoginFail();
        }

        return $my_user['id'];
    }

    /**
     * 手机号登录
     * @param $params
     * @return mixed
     */
    private function mobile($params)
    {
        $user = UserModel::getUserByPhone($params['phone']);

        if(!$user){
            UserException::UserNotFound();
        }
        if(md5($params['password']) != $user['password']){
            UserException::UserNotFound();
        }

        return $user['id'];
    }

    /**
     * 世界杯小程序
     * @param $params
     * @return int|string
     */
    private function shijiebei($params)
    {
        $log = myLog("wxapp_login");
        $conf = config()->get("shijiebei");

        $log->addDebug("conf:".json_encode($conf));

        $wxapp = new Wxapp($conf['app_id'], $conf['app_secret']);

        $code = $params['code'];

        $log->addDebug("code:".$code);
        $result = $wxapp->login($code);
        $log->addDebug("result:".json_encode($result));
        if(!isset($result['openid'])){
            UserException::LoginFail();
        }

        if(!isset($result['unionid'])||empty($result['unionid'])||!$user = UserModel::getUserByUnionId($result['unionid'])){
            $data = [
                "openid" => $result['openid'],
                "unionid" => isset($result['unionid'])?$result['unionid']:"",
                "openid_type" => 6,
            ];
            $my_user = UserModel::getUserByOpenId($data['openid']);
            if(!$my_user)
            {
                $my_user['id'] = UserModel::addUser($data);
            }

        }else{
            $my_user['id'] = $user['id'];
        }

        $log->addDebug("my_user:".json_encode($my_user));
        if(!$my_user['id']){
            UserException::LoginFail();
        }

        return $my_user['id'];

    }


    public function qq($params)
    {

        $log = myLog("qq_login");

        $request = Request::createFromGlobals();

        $token = null;
        if(isset($params['token']) && !empty($params['token'])){
            $attributes['access_token'] = $params['token'];
            $log->addDebug("token:". $params['token']);
            $token = new AccessToken($attributes);
        }
        $config = config()->get("socialite");
        $user = (
            new QQProvider(
                $request,
                $config['qq']['client_id'],
                $config['qq']['client_secret']
            )
        )->user($token);

        $log->addDebug("user",$user->toArray());

        $result = $user->toArray();

        if(!isset($result['unionid'])||empty($result['unionid'])||!$user = UserModel::getUserByUnionId($result['unionid'])){
            $data = [
                "openid" => $result['id'],
                "unionid" => isset($result['unionid'])?$result['unionid']:"",
                "openid_type" => 2,
                "nickname" => $result['nickname'],
                "avatar" => $result['avatar'],
                "sex" => $result['original']['gender'],
                "city" => $result['original']['city'],
                "province" => $result['original']['province'],
            ];
            $my_user = UserModel::getUserByOpenId($data['openid']);
            if(!$my_user)
            {
                $my_user['id'] = UserModel::addUser($data);
            }

        }else{
            $my_user['id'] = $user['id'];
        }

        $log->addDebug("my_user:".json_encode($my_user));
        if(!$my_user['id']){
            UserException::LoginFail();
        }

        return $my_user['id'];
    }

    public function weibo($params)
    {
        $log = myLog("weibo_login");

        $request = Request::createFromGlobals();
        $socialite = new SocialiteManager(config()->get("socialite"),$request);

        $token = null;
        if(isset($params['token']) && !empty($params['token']) && isset($params['openid']) && !empty($params['openid'])){
            $attributes['access_token'] = $params['token'];
            $attributes['uid'] = $params['openid'];
            $token = new AccessToken($attributes);
        }
        $user = $socialite->driver("weibo")->user($token);

        $log->addDebug("user",$user->toArray());

        $result = $user->toArray();

        return $result['id'];
    }
}