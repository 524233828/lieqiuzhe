<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 18:53
 */

namespace Model;


class TopLineModel extends BaseModel
{

    public static $table = "top_line";

    public static function fetchWithPage($columns = "*" ,$where = null)
    {
        return database()->select(self::$table, [
            "[>]".PageModel::$table => ["page_id"=>"id"]
        ], $columns, $where);
    }

}