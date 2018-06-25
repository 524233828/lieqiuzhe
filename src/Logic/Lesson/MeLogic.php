<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/24
 * Time: 14:08
 */

namespace Logic\Lesson;

use Logic\BaseLogic;
use Model\UserModel;

class MeLogic extends BaseLogic
{
    public function getUser($user_id)
    {
        return UserModel::getUser($user_id);
    }
}