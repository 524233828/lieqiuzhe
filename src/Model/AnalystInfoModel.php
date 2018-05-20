<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/20
 * Time: 16:17
 */

namespace Model;


class AnalystInfoModel extends BaseModel
{

    public static $table = "analyst_info";

    public static function getInfoByUserId($user_id, $columns = "*")
    {
        return database()->get(self::$table, $columns, ["user_id"=>$user_id]);
    }
}