<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/6/26
 * Time: 10:11
 */

namespace Model;


use Exception\BaseException;

class UserClassModel extends BaseModel
{

    public static $table = "user_class";

    public static function getUserClass($user_id,$class_id)
    {
        $where = [
            "user_id" => $user_id,
            "class_id" => $class_id,
            "end_time[>=]" => time(),
            "status" => 1,
        ];

        return database()->get(self::$table,"*",$where);
    }

    public static function addUserClass($data)
    {
        $data['create_time'] = time();
        $buy = database()->insert(self::$table,$data);

        if(!$buy){
            BaseException::SystemError();
        }
        return database()->id();
    }
}