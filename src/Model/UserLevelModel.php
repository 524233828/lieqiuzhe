<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/26
 * Time: 16:16
 */

namespace Model;


class UserLevelModel extends BaseModel
{

    public static $table = "user_level";

    public static function getUserLevelByInfo($level, $month)
    {
        return database()->get(self::$table, "*", [
            "level" => $level,
            "month" => $month
        ]);
    }
}