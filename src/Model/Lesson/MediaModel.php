<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/16
 * Time: 22:50
 */

namespace Model\Lesson;

use Model\BaseModel;
use Exception\BaseException;

class MediaModel extends BaseModel
{

    const MEDIA_TABLE = "db_media";

    const IMAGE_TABLE = "db_image";

    public static function getMedia()
    {

    }

    public static function addMedia($data)
    {
        $data['create_time'] = time();
        $result = database("lesson")->insert(self::MEDIA_TABLE,$data);

        if(!$result){
            BaseException::SystemError();
        }
        return database("lesson")->id();
    }

    public static function listMedia($where = [])
    {
        $db = database("lesson");

        $result = $db->select(self::MEDIA_TABLE,"*",
            $where
        );

        return $result;
    }

    public static function addImage($data)
    {
        $data['create_time'] = time();
        $result = database("lesson")->insert(self::IMAGE_TABLE,$data);

        if(!$result){
            BaseException::SystemError();
        }
        return database("lesson")->id();
    }

    public static function getImageByResourceId($resource_id)
    {
        $result = database("lesson")->get(self::IMAGE_TABLE,"*",[
            "resource_id" => $resource_id
        ]);

        return $result;
    }

    public static function getVideoByResourceId($resource_id)
    {
        $result = database("lesson")->get(self::MEDIA_TABLE,"*",[
            "resource_id" => $resource_id
        ]);

        return $result;
    }

    public static function getVideo($id)
    {
        $result = database("lesson")->get(self::MEDIA_TABLE,"*",[
            "id" => $id
        ]);

        return $result;
    }

    public static function updateVideoMediaTime($resource_id,$media_time)
    {
        $where = ["resource_id"=>$resource_id];
        $data = ["media_time"=>$media_time];

        return database("lesson")->update(self::MEDIA_TABLE,$data,$where);
    }
}