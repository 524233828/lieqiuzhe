<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/24
 * Time: 14:14
 */

namespace Logic\Lesson;

use Logic\BaseLogic;
use Model\Lesson\BuyModel;

class MyClassListLogic extends BaseLogic
{
    /**
     * 获取我购买的课程列表
     * @param $user_id
     * @return array
     */
    public function listUserClass($user_id)
    {
        return BuyModel::listUserClass($user_id);
    }
}