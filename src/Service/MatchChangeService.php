<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/5
 * Time: 14:59
 */

namespace Service;


use Model\MatchModel;
use Qiutan\Constant;
use Qiutan\Match;

class MatchChangeService
{

    public static function change()
    {
        date_default_timezone_set("PRC");
        Match::$redis = redis();

        if(Match::$redis->exists(Constant::MATCH_CHANGE_CACHE)){

            return true;
        }

        $res = Match::matchChange();

        if(!isset($res['h']) || !is_array($res['h'])){
            return false;
        }
        foreach ($res['h'] as $v){
            list(
                $match_id,
                $status,
                $home_score,
                $away_score,
                $home_half_score,
                $away_half_score,
                $home_red,
                $away_red,
                $start_time,
                $real_start_time,
                $desc,
                $zhenrong,
                $home_yellow,
                $away_yellow,
                $match_date,
                $desc2,
                $home_corner,
                $away_corner
                ) = explode("^",$v);

            $current_minutes = floor((time()-strtotime($real_start_time))/60);
            $where = ["id" => $match_id];

            $data = [
                "status" => $status,
                "home_score" => $home_score,
                "away_score" => $away_score,
                "home_half_score" => $home_half_score,
                "away_half_score" => $away_half_score,
                "home_red" => $home_red,
                "away_red" => $away_red,
                "home_yellow" => $home_yellow,
                "away_yellow" => $away_yellow,
                "home_corner" => $home_corner,
                "away_corner" => $away_corner,
                "current_minutes" => $current_minutes,
            ];

            try {
                database()->update(MatchModel::MATCH_TABLE, $data, $where);
            }catch (\Exception $e){

            }
        }

        return false;
    }

    public static function change2()
    {
        date_default_timezone_set("PRC");
        Match::$redis = redis();

        if(Match::$redis->exists(Constant::MATCH_CHANGE_LONG_CACHE)){

            return true;
        }

        $res = Match::matchChangeLong();

        if(!isset($res['h']) || !is_array($res['h'])){
            return false;
        }
        foreach ($res['h'] as $v){
            list(
                $match_id,
                $status,
                $home_score,
                $away_score,
                $home_half_score,
                $away_half_score,
                $home_red,
                $away_red,
                $start_time,
                $real_start_time,
                $desc,
                $zhenrong,
                $home_yellow,
                $away_yellow,
                $match_date,
                $desc2,
                $home_corner,
                $away_corner
                ) = explode("^",$v);

            $current_minutes = floor((time()-strtotime($real_start_time))/60);
            $where = ["id" => $match_id];

            $data = [
                "status" => $status,
                "home_score" => $home_score,
                "away_score" => $away_score,
                "home_half_score" => $home_half_score,
                "away_half_score" => $away_half_score,
                "home_red" => $home_red,
                "away_red" => $away_red,
                "home_yellow" => $home_yellow,
                "away_yellow" => $away_yellow,
                "home_corner" => $home_corner,
                "away_corner" => $away_corner,
                "current_minutes" => $current_minutes,
            ];

            try {
                database()->update(MatchModel::MATCH_TABLE, $data, $where);
            }catch (\Exception $e){

            }
        }

        return false;
    }
}