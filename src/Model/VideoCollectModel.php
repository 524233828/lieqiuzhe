<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/7
 * Time: 15:41
 */

namespace Model;


class VideoCollectModel extends BaseModel
{

    public static $table = "video_collect";

    public static function deleteVideoCollect($where)
    {
        return database()->delete(self::$table,$where);
    }

    public static function getVideoCollect($where)
    {
        return database()->get(self::$table, "*", $where);
    }


    public static function countCollectNum()
    {
        $table = self::$table;
        $sql = <<<SQL
SELECT video_id, count(*) as num
FROM {$table} 
GROUP BY video_id
SQL;

        return database()->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}