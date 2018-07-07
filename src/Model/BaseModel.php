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
        $class = get_called_class();
        return database()->insert($class::$table, $data);
    }

    public static function fetch($columns = "*" ,$where = null)
    {
        $class = get_called_class();
        return database()->select($class::$table, $columns, $where);
    }

    public static function update($data, $where)
    {
        $class = get_called_class();
        return database()->update($class::$table, $data, $where);
    }

    public static function get($id, $columns = "*")
    {
        $class = get_called_class();
        $where = ["id" => $id];

        return database()->get($class::$table, $columns, $where);
    }

    public static function delete($id)
    {
        $class = get_called_class();
        $where = ["id" => $id];

        return database()->delete($class::$table, $where);
    }

    public static function countMatch($where = null)
    {
        $class = get_called_class();
        return database()->count($class::$table, "*", $where);
    }
}
