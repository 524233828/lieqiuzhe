<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/27
 * Time: 10:32
 */

namespace Logic;


use Model\MatchModel;

class MatchListLogic extends BaseLogic
{
    public function fetchMatchList($type, $page = 1, $size = 20)
    {

        $first_index =  $size * ($page-1);
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
        }

    }
}