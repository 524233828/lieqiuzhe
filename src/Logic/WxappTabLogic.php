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

        $data['status'] = 1;
        $data['create_time'] = time();
        $data['update_time'] = time();
        return WxappTabModel::add($data);
    }

    public function listTab()
    {
        $list = WxappTabModel::fetch(
            [
                "type",
                "img",
                "url",
                "appid",
                "title"
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

    public function updateTab($data, $where)
    {
        return WxappTabModel::update($data, $where);
    }
}