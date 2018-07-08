<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 18:53
 */

namespace Model;


class BannerModel extends BaseModel
{

    public static $table = "banner";

    public static function listBanner($where = [])
    {
        $db = database("lesson");

        $result = $db->select("db_banner","*",
            $where
        );

        return $result;
    }


}