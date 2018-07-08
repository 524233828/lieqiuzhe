<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/7
 * Time: 10:38
 */

namespace Model;


class VideoModel extends BaseModel
{
    public static $table = "video";

    public static function fetchVideo($columns = "*", $where = null)
    {
        return database()->select(
            self::$table,
            [
                "[>]".VideoVCateModel::$table => ["id"=>"video_id"]
            ],
            $columns,
            $where
        );
    }

    public static function fetchVideoWithUser($columns = "*", $where = null)
    {
        return database()->select(
            self::$table,
            [
                "[>]".UserModel::$table => ["user_id"=>"id"]
            ],
            $columns,
            $where
        );
    }

}