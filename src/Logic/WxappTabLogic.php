<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/14
 * Time: 12:13
 */

namespace Logic;


use Model\WxappTabModel;

class WxappTabLogic extends BaseLogic
{

    public function add($data)
    {
        return WxappTabModel::add($data);
    }

    public function listTab()
    {
        $list = WxappTabModel::fetch(
            [
                "type",
                "img",
                "url",
                "appid"
            ],
            [
                "status" => 1
            ]
        );

        return ["list" => $list];
    }

    public function deleteTab($id)
    {
        return WxappTabModel::delete($id);
    }
}