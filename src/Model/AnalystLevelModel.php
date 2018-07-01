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

    public static function fetchAnalystLevel($level = null, $month = null)
    {

        $where = null;
        if(!empty($level)){
            $where[self::$table.".level"] = $level;
        }

        if(!empty($month)){
            $where[self::$table.".month"] = $month;
        }

        return database()->select(
            self::$table,
            [
                "[>]".IconsModel::$table => ["level" => "level" , "type" => 1]
            ],
            [
                self::$table.".level",
                self::$table.".month",
                self::$table.".price",
                IconsModel::$table.".icon",
            ],
            $where
        );
    }
}