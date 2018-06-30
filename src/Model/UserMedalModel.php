<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/6/30
 * Time: 14:45
 */

namespace Model;


class UserMedalModel extends BaseModel
{
    public static $table = "user_medal";

    public static function fetchUserMedalByUserId($uid)
    {
        database()->select(self::$table,
            [
                "[>]".MedalModel::$table => ["medal_id" => "id"]
            ],
            [
                MedalModel::$table.".name",
                MedalModel::$table.".icon",
                self::$table.".num(value)",
            ],
            [self::$table.".user_id"=> $uid]
        );
    }
}