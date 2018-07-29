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

    public static function fetchUserLevel($level = null, $month = null)
    {

        $where = null;
        if(!empty($level)){
            $where[self::$table.".level"] = $level;
        }

        if(!empty($month)){
            $where[self::$table.".month"] = $month;
        }

        $where[IconsModel::$table.".type"]  = 0;

        return database()->select(
            self::$table,
            [
                "[>]".IconsModel::$table => ["level" => "level" ]
            ],
            [
                self::$table.".level",
                self::$table.".month",
                self::$table.".price",
                self::$table.".intro",
                IconsModel::$table.".icon",
            ],
            $where
        );
    }
}