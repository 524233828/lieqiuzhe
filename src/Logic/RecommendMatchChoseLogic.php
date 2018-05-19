<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/19
 * Time: 14:24
 */

namespace Logic;


use Model\MatchModel;
use Model\OddModel;
use Service\Pager;

class RecommendMatchChoseLogic extends BaseLogic
{

    public function matchList($date, $league_id = null, $odd_type = 1, $page = 1, $size = 20)
    {
        //计算最近七天每天比赛数

        //今日
        $today_time = time();

        //七天后
        $week_time = strtotime(date("Ymd", $today_time + 7 * 86400));



        $first_index =  $size * ($page-1);

        $start_time = strtotime($date);

        $end_time = strtotime($date ."+1 day");

        $where = [
            MatchModel::$table.".start_time[>=]" => $start_time,
            MatchModel::$table.".start_time[<]" => $end_time,
            OddModel::$table.".odd_type" => $odd_type
        ];
        if(!empty($league_id))
        {
            $where[MatchModel::$table.".league_id"] = $league_id;
        }

        $res = OddModel::fetchOddMatchList([OddModel::$table.".match_id(mid)"],$where);

        $match_ids = [];
        foreach ($res as $v){
            $match_ids[] = $v['mid'];
        }

        $where = [];
        $where["m.status"] = [0];
        $where["m.id"] = $match_ids;
        $where["ORDER"] = ["start_time" => "ASC"];
        $count = MatchModel::count($where);
        $where["LIMIT"] = [$first_index, $size];
        $list = MatchModel::fetch(
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
                "m.current_minutes",
            ]
        );

        $page = new Pager($page, $size);

        $page->getPager($count);

    }
}