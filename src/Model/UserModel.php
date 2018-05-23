<?php

namespace Model;

use Constant\CacheKey;
use Component\Setting;
use Exception\BaseException;

class UserModel extends BaseModel
{

    public static $table = "`user`";


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

}
