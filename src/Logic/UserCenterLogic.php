<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/3
 * Time: 10:24
 */

namespace Logic;


use Exception\BaseException;
use Model\UserModel;

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
}