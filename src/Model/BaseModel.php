<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/16
 * Time: 22:18
 */

namespace Model;

/**
 * 模型层，用于简单的数据库操作
 * Class BaseModel
 * @package Model
 */
class BaseModel
{

    public static $table;

    public static function add($data)
    {
        return database()->insert(self::$table, $data);
    }

    public static function fetch($columns = "*" ,$where = null)
    {
        return database()->select(self::$table, $columns, $where);
    }

    public static function update($data, $where)
    {
        return database()->update(self::$table, $data, $where);
    }

    public static function get($id, $columns = "*")
    {
        $where = ["id" => $id];

        return database()->get(self::$table, $columns, $where);
    }

    public static function delete($id)
    {
        $where = ["id" => $id];

        return database()->delete(self::$table, $where);
    }

    public static function count($where)
    {
        return database()->count(self::$table, "*", $where);
    }
}
