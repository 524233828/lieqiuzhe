<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/20
 * Time: 11:46
 */

namespace Model;


use FastD\Model\Model;

class MatchInfoModel extends BaseModel
{

    public static $table = "match_info";

    public static function detail($match_id)
    {
        $match_info_table = MatchInfoModel::$table;
        $columns = [
            'team_type',
            '`desc`',
            '`match_id`',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $sql = <<<SQL
SELECT {$column}
FROM `{$match_info_table}`
WHERE `match_id` = {$match_id}
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

    }
}