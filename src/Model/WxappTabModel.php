<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/14
 * Time: 12:07
 */

namespace Model;


class WxappTabModel extends BaseModel
{

    const WXAPP_TAB_TABLE = "wxapp_tab";

    public static function add($data)
    {
        return database()->insert(self::WXAPP_TAB_TABLE, $data);
    }

    public static function fetch($columns = "*" ,$where = null)
    {
        return database()->select(self::WXAPP_TAB_TABLE, $columns, $where);
    }

    public static function update($data, $where)
    {
        return database()->update(self::WXAPP_TAB_TABLE, $data, $where);
    }

    public static function get($id, $columns = "*")
    {
        $where = ["id" => $id];

        return database()->get(self::WXAPP_TAB_TABLE, $columns, $where);
    }

    public static function delete($id)
    {
        $where = ["id" => $id];

        return database()->delete(self::WXAPP_TAB_TABLE, $where);
    }
}