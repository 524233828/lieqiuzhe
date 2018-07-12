<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/12
 * Time: 23:20
 */

namespace Logic\Admin;


use Model\MatchModel;
use Model\TeamModel;
use Service\Pager;

class MatchLogic extends AdminBaseLogic
{

    protected $list_filter = [
        "m" => "is_recommend",
        "l" => "gb_short",
    ];

    public function listAction($params)
    {
        //列表分页参数
        $page = isset($params['page'])?$params['page']:1;
        $size = isset($params['size'])?$params['size']:20;

        $pager = new Pager($page, $size);

        $where = null;

        //检查请求参数中是否有筛选可用参数，有则按参数筛选
        foreach ($this->list_filter as $k => $v){
            if(isset($params[$v]))
            {
                $where[$k.".".$v] = $params[$v];
            }
        }

        if(isset($params['home_name'])){
            $where["h.gb"] = $params['home_name'];
        }

        if(isset($params['away_name'])){
            $where["a.gb"] = $params['away_name'];
        }

        if(isset($params['status'])){
            $where["m.status"] = $params['status'];
        }

        if(isset($params['start_time'])){
            $start_time = strtotime($params['start_time']);
            $end_time = strtotime($params['start_time']. "+1 day");
            $where["m.start_time[>=]"] = $start_time;
            $where["m.start_time[<]"] = $end_time;
        }

        //计算符合筛选参数的行数
        $count = MatchModel::countMatch($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = MatchModel::fetchMatch($where,[
            "m.id",
            "l.gb_short(league_name)",
            "m.start_time(match_time)",
            "h.gb(home)",
            "a.gb(away)",
            "m.status",
            "m.home_score",
            "m.away_score",
            "m.is_recommend"
        ]);

        return ["list"=>$list, "meta" => $pager->getPager($count)];
    }

    public function getAction($params)
    {
        // TODO: Implement getAction() method.
    }

    public function deleteAction($params)
    {
        // TODO: Implement deleteAction() method.
    }

    public function addAction($params)
    {
        // TODO: Implement addAction() method.
    }

    public function updateAction($params)
    {
        // TODO: Implement updateAction() method.
    }
}