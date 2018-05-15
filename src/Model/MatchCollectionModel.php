<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 14:27
 */

namespace Model;



class MatchCollectionModel extends BaseModel
{

    public static $table = "match_collection";

    public static function delete($where)
    {
        return database()->delete(self::$table, $where);
    }

    public static function fetch($where)
    {
        return parent::fetch("*", $where);
    }

}