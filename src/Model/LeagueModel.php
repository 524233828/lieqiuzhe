<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 9:51
 */

namespace Model;


class LeagueModel
{
    const LEAGUE_TABLE = "league";

    public static function fetch($where = [], $columns = "*")
    {
        return database()->select(self::LEAGUE_TABLE, $columns, $where);
    }
}