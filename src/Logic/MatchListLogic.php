<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/27
 * Time: 10:32
 */

namespace Logic;

use Model\MatchCollectionModel;
use Model\MatchModel;
use Service\Pager;

class MatchListLogic extends BaseLogic
{
    public function fetchMatchList($type, $page = 1, $size = 20)
    {

        $first_index =  $size * ($page-1);
        $res = [];
        $count = 0;
        switch ($type){
            case 0://即时比分
                $res = MatchModel::fetch(
                    [
                        "m.status" => [0, 1, 2, 3, 4],
                        "LIMIT" => [$first_index, $size]
                    ],
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
                    ]
                );
                $count = MatchModel::count([
                    "status" => [0, 1, 2, 3, 4],
                ]);
                break;
            case 1://赛果
                $res = MatchModel::fetch(
                    [
                        "m.status" => [-1],
                        "ORDER" => ["start_time" => "DESC"],
                        "LIMIT" => [$first_index, $size]
                    ],
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
                    ]
                );
                $count = MatchModel::count([
                    "status" => [-1],
                ]);
                break;
            case 2://赛程
                $res = MatchModel::fetch(
                    [
                        "m.status" => [0],
                        "LIMIT" => [$first_index, $size]
                    ],
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
                    ]
                );
                $count = MatchModel::count([
                    "status" => [0],
                ]);
                break;
            case 3://胜负彩
                break;
            default:
                break;
        }

        foreach ($res as $k => $v){
            $res[$k]['match_time'] = date("Y-m-d H:i:s", $v['match_time']);
        }

        $page = new Pager($page,$size);

        return ["list" => $res, "meta"=>$page->getPager($count)];

    }

    public function collect($match_id)
    {
        $user_id = UserLogic::$user["id"];

        $data = [
            "match_id" => $match_id,
            "user_id" => $user_id,
            "create_time" => time()
        ];

        return MatchCollectionModel::add($data);
    }

    public function collectCancel($match_id)
    {
        $user_id = UserLogic::$user["id"];

        $where = [
            "match_id" => $match_id,
            "user_id" => $user_id,
        ];

        return MatchCollectionModel::delete($where);
    }
}