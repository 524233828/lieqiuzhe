<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/19
 * Time: 14:24
 */

namespace Logic;


use Model\LeagueModel;
use Model\MatchModel;
use Model\OddModel;
use Service\Pager;

class RecommendMatchChoseLogic extends BaseLogic
{

    public function matchList($date, $league_id = null, $odd_type = 1, $page = 1, $size = 20)
    {
        //计算最近七天每天有亚盘的比赛数

        //今日
        $today_time = time();

        //七天后
        $week_time = strtotime(date("Ymd", $today_time + 7 * 86400));

        $date_list = OddModel::countDateMatch($today_time, $week_time);

        $first_index =  $size * ($page-1);

        $start_time = strtotime($date);

        $end_time = strtotime($date ."+1 day");

        $league_list = OddModel::getOddLeague($start_time, $end_time);

        $where = [
            MatchModel::$table.".start_time[>=]" => $start_time,
            MatchModel::$table.".start_time[<]" => $end_time,
            OddModel::$table.".type" => $odd_type
        ];
        if(!empty($league_id))
        {
            $where[MatchModel::$table.".league_id"] = $league_id;
        }

        $res = OddModel::fetchOddMatchList([OddModel::$table.".match_id(mid)", OddModel::$table.".id"],$where);

        $match_ids = [];
        $match_index = [];
        foreach ($res as $v){
            $match_ids[] = $v['mid'];
            $match_index[$v['mid']] = $v['id'];
        }

        $where = [];
        $where["m.status"] = [0];
        $where["m.id"] = $match_ids;
        $where["ORDER"] = ["start_time" => "ASC"];
        $count = MatchModel::countMatch($where);
        $where["LIMIT"] = [$first_index, $size];
        $list = MatchModel::fetchMatch(
            $where,
            [
                "m.id(match_id)",
                "l.gb_short(league_name)",
                "l.color(league_color)",
                "m.start_time(match_time)",
                "h.gb(home)",
                "a.gb(away)",
            ]
        );

        foreach ($list as $k => $v)
        {
            $list[$k]['odd_id'] = $match_index[$v['match_id']];
        }

        $page = new Pager($page, $size);

        return [
            "date_list" => $date_list,
            "league_list" => $league_list,
            "match_list" => $list,
            "meta" => $page->getPager($count)
        ];

    }
}