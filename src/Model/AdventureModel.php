<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/28
 * Time: 10:33
 */

namespace Model;


class AdventureModel extends BaseModel
{
    public static $table = "adventure";

    public static function fetchWithPage($columns = "*" ,$where = null)
    {
        return database()->select(self::$table, [
            "[>]".PageModel::$table => ["page_id"=>"id"]
        ], $columns, $where);
    }
}