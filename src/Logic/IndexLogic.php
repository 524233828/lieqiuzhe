<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 18:49
 */

namespace Logic;


use Model\BannerModel;
use Model\TopLineModel;
use Model\UserModel;

class IndexLogic extends BaseLogic
{

    public function userInfo()
    {
        $uid = UserLogic::$user['id'];

        return UserModel::getUserInfo($uid,["avatar"]);
    }

    public function banner()
    {
        $list =  BannerModel::fetch(
            [
                "status" => 1,
                "ORDER" => ["sort" => "DESC"]
            ],
            [
                "image_url",
                "page_id",
                "url",
                "params",
            ]
        );
        return ["list" => $list];
    }

    public function topLine()
    {
        $list =  TopLineModel::fetch(
            [
                "status" => 1,
                "ORDER" => ["sort" => "DESC"]
            ],
            [
                "content",
                "page_id",
                "url",
                "params",
            ]
        );
        return ["list" => $list];
    }
}