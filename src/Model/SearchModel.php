<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 9:39
 */

namespace Model;

class SearchModel extends BaseModel
{
    public static $table = "search";
    public static function fetchByIsHot($where = [], $columns = "*")
    {

        $search_table = SearchModel::$table;
        is_array($columns) && $columns = implode(",", $columns);
        $sql = <<<SQL
SELECT {$columns}
FROM `{$search_table}`
WHERE is_hot = 1
ORDER BY sort DESC
lIMIT 0,10
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

    }

    public static function fetchBySearchTime($limit, $columns = "*")
    {
        $search_table = SearchModel::$table;
        is_array($columns) && $columns = implode(",", $columns);
        $sql = <<<SQL
SELECT {$columns}
FROM `{$search_table}`
WHERE is_hot = 0
ORDER BY search_times DESC
lIMIT 0,{$limit}
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

    }

    public static function countMatch($where)
    {
        return database()->count(
            self::$table."(m)",
            ["id"],
            $where
        );
    }
}