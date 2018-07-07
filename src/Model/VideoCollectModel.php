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
}