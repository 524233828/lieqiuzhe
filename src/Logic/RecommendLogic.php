<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/20
 * Time: 15:31
 */

namespace Logic;


use Model\LeagueModel;
use Model\MatchInfoModel;
use Model\MatchModel;
use Model\OddModel;
use Model\OptionModel;
use Model\TeamModel;
use Qiutan\Match;

class RecommendLogic extends BaseLogic
{

    public function matchInfo($odd_id)
    {
        $odd = OddModel::get($odd_id);

        $match_id = $odd['match_id'];

        $match = MatchModel::get($match_id,
            [
                "home_id",
                "away_id",
                "league_id",
                "start_time(match_time)"
            ]
        );

        $home = TeamModel::get($match['home_id'],
            [
                "gb(home)",
                "flag(home_flag)",
            ]
        );

        $away = TeamModel::get($match['away_id'],
            [
                "gb(away)",
                "flag(away_flag)",
            ]
        );

        $league = LeagueModel::get($match['league_id'],
            ['gb_short(league_name)']
        );

        $home_info = MatchInfoModel::fetch(
            ["id", "desc"],
            [
                "match_id"=>$match_id,
                "team_type" => 0
            ]
        );

        $away_info = MatchInfoModel::fetch(
            ["id", "desc"],
            [
                "match_id"=>$match_id,
                "team_type" => 1
            ]
        );

        $option = OptionModel::fetch(
            [
                "id(option_id)",
                "option(option_name)",
                "odds_rate"
            ],
            ["odd_id" => $odd_id]
        );

        $response = [
            "league_name" => $league['league_name'],
            "match_time" => $match['match_time'],
            "home" => $home['home'],
            "home_flag" => $home['home_flag'],
            "away" => $away['away'],
            "away_flag" => $away['away_flag'],
            "option" => $option,
            "extra" => json_decode($odd['extra'],true),
            "home_info" => $home_info,
            "away_info" => $away_info
        ];

        return $response;
    }
}