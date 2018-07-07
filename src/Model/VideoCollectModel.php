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

    public static function videoCollectList($user_id, $where = [])
    {

        $where = array_merge($where,[
            self::$table.".user_id" => $user_id
        ]);

        return database()->select(
            self::$table,
            [
                "[>]".VideoModel::$table => ["video_id" => "id"]
            ],
            [
                VideoModel::$table.".id",
                VideoModel::$table.".user_id",
                VideoModel::$table.".url",
                VideoModel::$table.".img_url",
                VideoModel::$table.".viewer",
                VideoModel::$table.".times",
                VideoModel::$table.".status",
                VideoModel::$table.".update_time",
                VideoModel::$table.".create_time",
            ],
            $where
        );
    }
}