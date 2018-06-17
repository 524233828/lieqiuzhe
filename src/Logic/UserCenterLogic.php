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

}