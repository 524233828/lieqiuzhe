<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/26
 * Time: 16:18
 */

namespace Model;


class AnalystLevelOrderModel extends BaseModel
{

    public static $table = "analyst_level_order";

    public static function getAnalystCurrentLevel($uid)
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
}