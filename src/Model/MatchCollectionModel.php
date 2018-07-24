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

    public static function deleteMatchCollect($where)
    {
        return database()->delete(self::$table, $where);
    }

    public static function fetchWithMatchId($match_id)
    {
        return database()->select(
            self::$table,
            [
                "[>]".UserModel::$table => ["user_id" => "id"]
            ],
            [
                self::$table.".form_id",
                self::$table.".device_token",
                UserModel::$table.".openid_type"
            ],
            [self::$table.".match_id" => $match_id]
        );
    }
}