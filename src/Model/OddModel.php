<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/18
 * Time: 10:40
 */

namespace Model;


class OddModel extends BaseModel
{

    public static $table = "odd";

    public static function getOddByMatchId($match_id, $columns = "*")
    {
        $where = ["match_id" => $match_id];

        return database()->get(self::$table, $columns, $where);
    }
}