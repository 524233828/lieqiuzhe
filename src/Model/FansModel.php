<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 9:39
 */

namespace Model;

class FansModel extends BaseModel
{
    public static $table = "fans";

    public static function getCountFansByAnalystId($analyst_id)
    {
        $where = [ 'analyst_id' => $analyst_id];
        return database()->count(
            self::$table."(m)",
            ["id"],
            $where
        );
    }
}