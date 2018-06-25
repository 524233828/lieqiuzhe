<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/24
 * Time: 11:31
 */

namespace Logic\Lesson;

use Logic\BaseLogic;
use Model\BannerModel;
use Model\ClassModel;

class IndexLogic extends BaseLogic
{
    /**
     * 首页获取课程列表
     * @param int $page
     * @param int $row
     * @return array
     */
    public function listClass($page = 1,$row = 10)
    {

        $page = $page<1?1:$page;

        $row = $row<1?1:$row;

        $first_row = ($page-1)*$row;

        $where = ["status"=>1];

        $count = ClassModel::countClass($where);

        $total_page = floor($count/$row)+1;

        $where["ORDER"] = ["id"=>"DESC"];
        $where["LIMIT"] = [$first_row,$row];

        $class_list = ClassModel::listClass($where);
        $class['list'] = $class_list;
        $class['total_page'] = $total_page;
        $class['current_page'] = $page;

        return $class;
    }

    /**
     * 首页获取banner列表
     * @return array
     */
    public function listBanner()
    {
        $banner = BannerModel::listBanner([
            "status"=>1,
            "ORDER" => ["sort"]
        ]);

        return $banner;
    }
}