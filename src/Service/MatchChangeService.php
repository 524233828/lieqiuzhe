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
use Model\UserModel;
use Qiutan\Constant;
use Qiutan\Match;
use Wxapp\Wxapp;

class MatchChangeService
{

    public static function change($is_long = false)
    {
        date_default_timezone_set("PRC");
        Match::$redis = redis();
        $log = myLog("start_push");

        if($is_long){//使用球探150秒更新一次接口

            if(Match::$redis->exists(Constant::MATCH_CHANGE_LONG_CACHE)){

//                return true;
            }
            $res = Match::matchChangeLong();
        }else{//20秒更新接口

            if(Match::$redis->exists(Constant::MATCH_CHANGE_CACHE)){

                return true;
            }
            $res = Match::matchChange();
        }


        $log->addDebug("res:".json_encode($res));
        if(!isset($res['h'])){
            return false;
        }
        if(!is_array($res['h'])){
            $res['h'] = [0=> $res['h']];
        }
        $ids = [];
        $log->addDebug("res.h:".json_encode($res['h']));
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

            $log->addDebug("match_id".$match_id);
            $log->addDebug("real_start_time".$real_start_time);
            list($y, $m, $d, $h, $i, $s) = explode(",",$real_start_time);
            //球探的月份从0开始排序
            $m++;
            $log->addDebug("y".$y);
            $log->addDebug("m".$m);
            $log->addDebug("d".$d);
            $log->addDebug("h".$h);
            $log->addDebug("i".$i);
            $log->addDebug("s".$s);

            $current_minutes = floor((time()-strtotime("{$y}-{$m}-{$d} {$h}:{$i}:{$s}"))/60);
            $log->addDebug("current_minutes".$current_minutes);
            $where = ["id" => $match_id];

            //增加开始比赛状态改变，做推送
//            $count = MatchModel::count(["id" => $match_id, "status" => 0]);
//            if($count){
//                $ids[] = $match_id;
//            }

            $data = [
//                "status" => $status,
//                "home_score" => $home_score,
//                "away_score" => $away_score,
//                "home_half_score" => $home_half_score,
//                "away_half_score" => $away_half_score,
//                "home_red" => $home_red,
//                "away_red" => $away_red,
//                "home_yellow" => $home_yellow,
//                "away_yellow" => $away_yellow,
                "home_corner" => $home_corner,
                "away_corner" => $away_corner,
                "current_minutes" => $current_minutes,
            ];

            try {
                database()->update(MatchModel::$table, $data, $where);
            }catch (\Exception $e){

            }
        }
//        $log->addDebug("match_ids:".json_encode($ids));
//        //增加开始比赛状态改变，做推送
//        if(empty($ids)||count($ids)<1)
//        {
//            return false;
//        }
//
//        $collect = MatchCollectionModel::fetch("*", ["match_id"=>$ids]);
//
//        $where2['m.id'] = $ids;
//        $res = MatchModel::fetch(
//            $where2,
//            [
//                "m.id(match_id)",
//                "l.gb_short(league_name)",
//                "l.color(league_color)",
//                "m.start_time(match_time)",
//                "h.gb(home)",
//                "h.flag(home_flag)",
//                "a.gb(away)",
//                "a.flag(away_flag)",
//                "m.home_score",
//                "m.away_score",
//                "m.home_red",
//                "m.away_red",
//                "m.home_yellow",
//                "m.away_yellow",
//                "m.status",
//                "m.current_minutes",
//            ]
//        );
//
//        $match_index = [];
//        foreach ($res as $k => $v){
//            $res[$k]['match_time'] = date("Y-m-d H:i:s", $v['match_time']);
//            $res[$k]['is_collect'] = 0;
//            $match_index[$v['match_id']] = $res[$k];
//        }
//
//        $conf = config()->get("wxapp");
//        $wxapp = new Wxapp($conf['app_id'], $conf['app_secret']);
//        $template_id = "CqlL_4sZ3Axfcth7vFd0bREMM0ayWt4loscCk8hhnBQ";
//        foreach ($collect as $v){
//            $log->addDebug("用户：{$v['user_id']}，关注了{$v['match_id']}");
//            $user = UserModel::getUserInfo($v['user_id'], ["openid"]);
//            if(!empty($v['form_id'])){
//
//                $match = $match_index[$v['match_id']];
//                $data = [
//                    "keyword1"=>[
//                        "value"=> "{$match["home"]} vs {$match["away"]}",
//                        "color" => "#FF0000"
//                    ],
//                    "keyword2"=>[
//                        "value"=> $match['match_time'],
//                        "color" => "#173177"
//                    ],
//                    "keyword3"=>[
//                        "value"=> $match['league_name'],
//                        "color" => $match['color']
//                    ]
//                ];
//
//                $log->addDebug("open_id:".json_encode($user['openid']));
//                $log->addDebug("template_id:".$template_id);
//                $log->addDebug("form_id:".$v['form_id']);
//                $log->addDebug("data:".json_encode($data));
//
//                $res = $wxapp->bindRedis(redis())
//                    ->sendTemplateMsg(
//                    $user['openid'],
//                    $template_id,
//                    $v['form_id'],
//                    $data,
//                    "pages/index"
//                );
//                $log->addDebug("res:".$res);
//            }
//        }

        return false;
    }

    public function matchStart($match_id)
    {

    }

    public function matchFinish($match_id)
    {

    }
}