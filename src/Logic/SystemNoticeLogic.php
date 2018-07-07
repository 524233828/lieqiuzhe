<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/7
 * Time: 19:47
 */

namespace Logic;


use Model\SystemNoticeModel;
use Service\Pager;

class SystemNoticeLogic extends BaseLogic
{

    public function fetchAction($page = 1, $size = 20)
    {
        $pager = new Pager($page, $size);

        $count = SystemNoticeModel::countMatch();

        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = SystemNoticeModel::fetch(["content", "update_time"],$where);

        return ["list" => $list, "meta"=> $pager->getPager($count)];
    }
}