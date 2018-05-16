<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/3
 * Time: 10:24
 */

namespace Logic;


class UserCenterLogic extends BaseLogic
{

    public function getInfo()
    {
        $uid = UserLogic::$user['id'];
    }
}