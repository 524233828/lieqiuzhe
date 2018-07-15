<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/7
 * Time: 10:57
 */

namespace Model;


class VideoVCateModel extends BaseModel
{

    public static $table = "video_vcate";

    public static function getVideoCate($cate_id,$video_id)
    {
        return database()->get(self::$table,"*", ["video_id"=>$video_id,"cate_id" => $cate_id]);
    }

    public static function deleteVideoCate($cate_id,$video_id)
    {
        return database()->delete(self::$table, ["video_id"=>$video_id,"cate_id" => $cate_id]);
    }

}