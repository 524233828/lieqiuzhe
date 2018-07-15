<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/26
 * Time: 16:18
 */

namespace Model;


class UserLevelOrderModel extends BaseModel
{

    public static $table = "user_level_order";

    public static function getUserCurrentLevel($uid)
    {
        $where = [
            "end_time[>=]" => time(),
            "uid" => $uid,
            "status" => 1,

            "ORDER" => [
                "level" => "DESC",
                "end_time" => "DESC"
            ]
        ];

        return database()->get(self::$table, "*", $where);
    }

    public static function getUserLevelOrderByOrderId($order_id, $columns = "*")
    {
        $where = ["order_id" => $order_id];

        return database()->get(self::$table, $columns, $where);
    }

    public static function fetchUserCurrentLevel($uid)
    {
        $where = [
            "end_time[>=]" => time(),
            "uid" => $uid,
            "status" => 1,

            "ORDER" => [
                "level" => "DESC",
                "end_time" => "DESC"
            ],

            "GROUP" => "uid"
        ];

        return database()->select(self::$table, "*", $where);
    }
}