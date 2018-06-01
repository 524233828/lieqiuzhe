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

        return database()->get(self::$table, null,"level", $where);
    }
}