<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/5
 * Time: 13:26
 */

namespace Logic;


use Model\MatchCollectionModel;
use Model\MatchModel;
use Service\Pager;

class MatchCollectionLogic extends BaseLogic
{

    public function fetch($page = 1, $size = 20)
    {

        $first_index =  $size * ($page-1);
        $collect = MatchCollectionModel::fetch([
            "user_id" => UserLogic::$user['id']
        ]);

        $match_ids = [];
        foreach ($collect as $v){
            $match_ids[] = $v['match_id'];
        }

        if(count($match_ids) === 0){
            return [];
        }

        $where['m.id'] = $match_ids;
        $where["ORDER"] = ["start_time" => "DESC"];
        $count = MatchModel::count($where);
        $where["LIMIT"] = [$first_index, $size];
        $res = MatchModel::fetch(
            $where,
            [
                "m.id(match_id)",
                "l.gb_short(league_name)",
                "l.color(league_color)",
                "m.start_time(match_time)",
                "h.gb(home)",
                "h.flag(home_flag)",
                "a.gb(away)",
                "a.flag(away_flag)",
                "m.home_score",
                "m.away_score",
                "m.home_red",
                "m.away_red",
                "m.home_yellow",
                "m.away_yellow",
                "m.status",
            ]
        );

        $match_index = [];
        foreach ($res as $k => $v){
            $res[$k]['match_time'] = date("Y-m-d H:i:s", $v['match_time']);
            $res[$k]['is_collect'] = 0;
            $match_index[$v['match_id']] = $res[$k];
        }

        foreach ($collect as $value){
            if(isset($match_index[$value['match_id']])){
                $match_index[$value['match_id']]["is_collect"] = 1;
            }
        }

        $page = new Pager($page,$size);

        return ["list" => array_values($match_index), "meta"=>$page->getPager($count)];
    }
}