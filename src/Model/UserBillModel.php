<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/6/25
 * Time: 14:28
 */

namespace Model;


class UserBillModel extends BaseModel
{
    public static $table = "user_bill";

    public static function getCurrentBill($uid)
    {
        $result = database()->get(self::$table,["current_bill"],[
            "user_id" => $uid,
            "ORDER" => ["create_at" => "DESC"],
        ]);

        return isset($result['current_bill'])?$result['current_bill'] : 0;
    }

}