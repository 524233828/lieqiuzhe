<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/18
 * Time: 11:08
 */

namespace Model;


class OptionModel extends BaseModel
{

    public static $table = "odd_option";

    public static function getOptionByOddId($oddid, $columns = "*")
    {
        $where = ["odd_id" => $oddid];
        return database()->select(self::$table, $columns, $where);
    }
}