<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/26
 * Time: 16:16
 */

namespace Model;


class AnalystLevelModel extends BaseModel
{

    public static $table = "analyst_level";

    public static function getAnalystLevelByInfo($level, $month)
    {
        return database()->get(self::$table, "*", [
            "level" => $level,
            "month" => $month
        ]);
    }
}