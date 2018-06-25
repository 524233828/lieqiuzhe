<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/19
 * Time: 10:34
 */

namespace Model\Lesson;

use Exception\BaseException;

class BuyModel extends BaseModel
{
    const BUY_CLASS_TABLE = "db_user_class";

    const CLASS_TABLE = "db_class";

    public static function addUserClass($data)
    {
        $data['create_time'] = time();
        $buy = database("lesson")->insert(self::BUY_CLASS_TABLE,$data);

        if(!$buy){
            BaseException::SystemError();
        }
        return database("lesson")->id();
    }

    public static function listUserClass($user_id)
    {
        $class = database("lesson")->select(
            self::BUY_CLASS_TABLE."(b)",
            ["[>]db_class(c)"=>["b.class_id"=>"id"]],
            ["c.img_url","c.title","c.desc","c.tag","c.id","b.learn_percent"],
            ["b.user_id"=>$user_id,"b.status"=>1]
            );

        return $class;
    }

    public static function updateLearnPercent($user_id,$class_id,$percent)
    {
        $where = [
            "user_id" => $user_id,
            "class_id" => $class_id,
            "status" => 1,
        ];

        $data = [
            "learn_percent" => $percent,
            "update_time" => time()
        ];

        return database("lesson")->update(self::BUY_CLASS_TABLE,$data,$where);
    }


    public static function getUserClass($user_id,$class_id)
    {
        $where = [
            "user_id" => $user_id,
            "class_id" => $class_id,
            "status" => 1,
        ];

        return database("lesson")->get(self::BUY_CLASS_TABLE,"*",$where);
    }

    public static function buySuccess($order_id, $expire_month)
    {
        $where = [
            "order_id" => $order_id
        ];

        $data['status'] = 1;
        $data['update_time'] = time();
        if($expire_month !== 0){
            $data['end_time'] = strtotime(date("Y-m-d H:i:s",$data['update_time'])." +{$expire_month} month");
        }

        return database("lesson")->update(self::BUY_CLASS_TABLE,$data,$where);
    }

    public static function getUserClassByOrderId($order_id, $column = null)
    {
        if(empty($column)){
            $column = "*";
        }
        $where = [
            "order_id" => $order_id
        ];

        return database("lesson")->get(self::BUY_CLASS_TABLE, $column, $where);
    }

    public static function updateUserClass($where = [], $data = [])
    {
        return database("lesson")->update(self::BUY_CLASS_TABLE, $data, $where);
    }
}