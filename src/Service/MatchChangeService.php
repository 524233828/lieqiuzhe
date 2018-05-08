<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/5
 * Time: 14:59
 */

namespace Service;


use Model\MatchCollectionModel;
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
        $ids = [];
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

            list($y, $m, $d, $h, $i, $s) = explode(",",$real_start_time);

            $m++;

            $current_minutes = floor((time()-strtotime("{$y}-{$m}-{$d} {$h}:{$i}:{$s}"))/60);
            $where = ["id" => $match_id];

            $count = MatchModel::count(["id" => $match_id, "status" => 0]);
            if($count){
                $ids[] = $match_id;
            }

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

        $collect = MatchCollectionModel::fetch(["match_id"=>$ids]);

        $where['m.id'] = $ids;
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
                "m.current_minutes",
            ]
        );

        $match_index = [];
        foreach ($res as $k => $v){
            $res[$k]['match_time'] = date("Y-m-d H:i:s", $v['match_time']);
            $res[$k]['is_collect'] = 0;
            $match_index[$v['match_id']] = $res[$k];
        }

        foreach ($collect as $v){

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

            list($y, $m, $d, $h, $i, $s) = explode(",",$real_start_time);

            $m++;

            $current_minutes = floor((time()-strtotime("{$y}-{$m}-{$d} {$h}:{$i}:{$s}"))/60);
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