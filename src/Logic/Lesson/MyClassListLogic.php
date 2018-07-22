<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/24
 * Time: 14:14
 */

namespace Logic\Lesson;

use Logic\BaseLogic;
use Logic\UserLogic;
use Model\Lesson\BuyModel;
use Model\Lesson\ClassModel;
use Model\UserClassModel;

class MyClassListLogic extends BaseLogic
{
    /**
     * 获取我购买的课程列表
     * @return array
     */
    public function listUserClass()
    {

        //获取用户所有课程
        $user_id = UserLogic::$user['id'];

        $user_class = UserClassModel::fetch("*",["user_id" => $user_id, "status" => 1]);

        $class_id = [];
        $class_list = [];
        foreach ($user_class as $v){
            $class_id[] = $v['class_id'];
            $class_list[$v['class_id']] = $v;
        }

        if(empty($class_id)){
            return ["list" => []];
        }
        $class = ClassModel::fetchClass(["img_url","title","desc","tag","id"],["id" => $class_id]);

        foreach ($class as $item)
        {
            $class_list[$item["id"]]["img_url"] = $item['img_url'];
            $class_list[$item["id"]]["title"] = $item['title'];
            $class_list[$item["id"]]["desc"] = $item['desc'];
            $class_list[$item["id"]]["tag"] = $item['tag'];
        }

        array_values($class_list);

        return ["list" => $class_list ];
    }
}