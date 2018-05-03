<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 14:27
 */

namespace Model;


use FastD\Model\Model;

class MatchCollectionModel extends Model
{

    const MATCH_COLLECT_TABLE = "match_collection";

    public static function add($data)
    {
        return database()->insert(self::MATCH_COLLECT_TABLE,$data);
    }

    public static function delete($where)
    {
        return database()->delete(self::MATCH_COLLECT_TABLE, $where);
    }

    public static function fetch($where)
    {
        return database()->select(self::MATCH_COLLECT_TABLE, $where);
    }
}