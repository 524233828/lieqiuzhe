<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 9:51
 */

namespace Model;


class TeamModel extends BaseModel
{
    const TEAM_TABLE = "team";
    public static $table = "team";

    public static function getTeamDetailById($id, $columns = "*")
    {
        $where = ["id" => $id];
        return database()->get(self::$table, $columns, $where);
    }

    public static function fetchByKeyWords($keywords, $columns = "*")
    {
        return database()->select(self::$table, $columns, [
            "gb[~]" => "$keywords"
        ]);
    }
}