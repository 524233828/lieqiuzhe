<?php

namespace Model;

use Constant\CacheKey;
use Exception\BaseException;
use Logic\UserLogic;

class UserModel extends BaseModel
{

    public static $table = "user";


    /**
     * 获取用户信息
     * @param $uid
     * @param $columns
     * @return bool|array
     */
    public static function getUserInfo($uid, $columns = null)
    {
        if (is_null($columns)) {
            $columns = [
                'id'
            ];
        }

        $user = database()->get(
            self::$table,
            $columns,
            [
                'id' => $uid
            ]
        );

        return $user;
    }

    public static function getUser($uid)
    {
        $user = database()->get(self::$table,"*",[
            "id" => $uid
        ]);

        return $user;
    }

    public static function addUser($data)
    {
        $data['create_time'] = time();
        $user = database()->insert(self::$table,$data);

        if(!$user){
            BaseException::SystemError();
        }
        return database()->id();
    }

    public static function getUserByOpenId($openid)
    {
        $user = database()->get(self::$table,"*",[
            "openid" => $openid
        ]);

        return $user;
    }

    public static function listUser($where = [])
    {
        return database()->select(self::$table,"*",$where);
    }

    public static function countUser($where = [])
    {
        return database()->count(self::$table,[],$where);
    }

    public static function getUserByUnionId($unionid)
    {
        $user = database()->get(self::$table,"*",[
            "unionid" => $unionid
        ]);

        return $user;
    }

    public static function updateUserByUid($uid, $data)
    {
        return database()->update(self::$table, $data, ["id" => $uid]);
    }

    public static function getUserByPhone($phone)
    {
        return database()->get(self::$table, "*", ["phone" => $phone, "status" => 1]);
    }

    public static function getUserByNotRigisterFinish($phone)
    {
        return database()->get(self::$table, ["id"], ["phone" => $phone, "status" => 0]);
    }

    public static function fetchUserWithAnalyst($columns = "*", $where = null)
    {

        $where[self::$table.'.user_type'] = 1;
        return database()->select(
            self::$table,
            [
                "[>]".AnalystInfoModel::$table => ["id"=>"user_id"]
            ],
            $columns,
            $where
        );

    }

    public static function countUserWithAnalyst($where = null)
    {

        $where[self::$table.'.user_type'] = 1;
        return database()->count(
            self::$table,
            [
                "[>]".AnalystInfoModel::$table => ["id"=>"user_id"]
            ],
            "*",
            $where
        );

    }

}
