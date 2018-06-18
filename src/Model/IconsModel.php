<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 9:51
 */

namespace Model;


class IconsModel extends BaseModel
{
    const LEAGUE_TABLE = "icons";

    public static $table = "icons";

    public static function getAnalystIcon($level)
    {
        $where = [
            "level" => $level,
            "type" => 1,
        ];
        return database()->get(self::$table, "icon", $where);
    }


    public static function getUserIcon($level)
    {
        $where = [
            "level" => $level,
            "type" => 0,
        ];
        return database()->get(self::$table, "icon", $where);
    }

}