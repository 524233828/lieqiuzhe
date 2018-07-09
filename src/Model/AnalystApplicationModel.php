<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/4
 * Time: 23:05
 */

namespace Model;


class AnalystApplicationModel extends BaseModel
{

    public static $table = "analyst_application";

    public static function fetchWithUser($columns = "*" ,$where = null)
    {
        return database()->select(self::$table, [
            "[>]".UserModel::$table => ["user_id"=>"id"]
        ], $columns, $where);
    }


}