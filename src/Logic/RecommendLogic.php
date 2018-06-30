<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/20
 * Time: 15:31
 */

namespace Logic;


use Exception\AnalystException;
use Exception\RecommendException;
use Helper\FuntionHelper;
use Model\AnalystInfoModel;
use Model\AnalystLevelOrderModel;
use Model\FansModel;
use Model\LeagueModel;
use Model\MatchInfoModel;
use Model\MatchModel;
use Model\OddModel;
use Model\OptionModel;
use Model\RecommendModel;
use Model\TeamModel;
use Model\UserModel;
use Qiutan\Match;
use Service\Pager;

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


    public function addRecommend($params)
    {
        $uid = UserLogic::$user['id'];

        $user = UserModel::getUserInfo($uid,['user_type']);

        if($user['user_type'] != 1)
        {
            AnalystException::userNotAnalyst();
        }

//        $analyst = AnalystInfoModel::getInfoByUserId($uid,['level']);
        $analyst_level = AnalystLevelOrderModel::getAnalystCurrentLevel($uid);

        $today = date("Y-m-d");

        $start_time = strtotime($today);
        $end_time = strtotime($today."+1 day");
        $recommend_count = RecommendModel::count([
            "create_time[>=]" => $start_time,
            "create_time[<]" => $end_time,
            "analyst_id" => $uid
        ]);

        //TODO: 更换成根据分析师等级变动
        $can_add = 5;

        if($recommend_count >= $can_add)
        {
            AnalystException::analystLevelTooLow();
        }

        $info_ids = explode(",", $params['info_id']);

        $info = MatchInfoModel::fetch("desc", ["id" => $info_ids]);

        $desc = "";
        foreach ($info as $v)
        {
            $desc .= $v."\r\n";
        }

        $data = [
            "create_time"=>time(),
            "status" => 1,
            "title" => $params['rec_title'],
            "desc" => $params['rec_desc'],
            "odd_id" => $params['odd_id'],
            "option_id" => $params['option_id'],
            "analyst_id" => $uid,
            "info" => $desc
        ];

        $recommend = RecommendModel::add($data);

        if($recommend)
        {
            return [];
        }else{
            RecommendException::recommendFail();
        }
    }

    public function getRecommendDetail($rec_id)
    {
        $res = RecommendModel::fetchOne($rec_id);
        if(!$res) {
            RecommendException::recommendEmpty();
        }
        $uid = UserLogic::$user['id'];

        $options = OptionModel::getOptionByOddId($res['odd_id'],['id','option','odds_rate']);
        $res['option'] = $options;
        $res['win_streak'] = FuntionHelper::continuityWin($res['record']);
        $res['hit_rate'] = FuntionHelper::winRate($res['record']);
        $res['rec_time'] =date('m/d H:i', $res['rec_time']);
        $res['is_read'] = 1;
        $res['extra'] = json_decode($res['extra'], true);

        $res['is_fans'] = 0;
        if($uid) {
            $is_fans = FansModel::fetch(
                ['id'],
                [
                    'user_id' => $uid,
                    'analyst_id' => $res['user_id'],
                ]
            );
            $res['is_fans'] = $is_fans ? 1 : 0;
        }
        unset($res['record']);
        unset($res['odd_id']);

        return $res;
    }

    public function getRecommendUserIdById($rec_id)
    {
        $res = RecommendModel::fetchOne($rec_id);
        if(!$res) {
            RecommendException::recommendEmpty();
        }

        $options = OptionModel::getOptionByOddId($res['odd_id'],['id','option','odds_rate']);
        $res['option'] = $options;
        $res['win_streak'] = FuntionHelper::continuityWin($res['record']);
        $res['hit_rate'] = FuntionHelper::winRate($res['record']);
        $res['rec_time'] =date('m/d H:i', $res['rec_time']);
        $res['is_read'] = 1;
        $res['extra'] = json_decode($res['extra'], true);
        unset($res['record']);
        unset($res['odd_id']);

        return $res;
    }

    public function getRecommendList($order, $filter, $page = 1, $size = 20)
    {

        $pager = new Pager($page, $size);

        switch ($order)
        {
            case 1:
                $order = "r.create_time desc";
                break;
            case 2:
            case 3:
            case 4:
                $order = "hit_rate.hit_rate desc";
                break;
            case 5:
                $order = "a.ticket desc";
                break;
            default:
                $order = "r.create_time desc";
        }
        $where = [];
        $where2 = [];

        if(isset($filter["7win_rate"]))
        {
            $start_time = time()-604800;
            $end_time = time();
            $where2[] = "create_time>={$start_time}";
            $where2[] = "create_time<{$end_time}";
            $where[] = "hit_rate.hit_rate>={$filter["7win_rate"]}";
        }
        if(isset($filter["30win_rate"]))
        {
            $start_time = time()-2592000;
            $end_time = time();
            $where2[] = "create_time>=$start_time";
            $where2[] = "create_time<$end_time";
            $where[] = "hit_rate.hit_rate>={$filter["30win_rate"]}";
        }

        if(isset($filter["win_rate"]))
        {
            $where[] = "hit_rate.hit_rate>={$filter["win_rate"]}";
        }

        if(isset($filter["ticket"]))
        {
            $where[] = "a.ticket>={$filter["ticket"]}";
        }

        if(isset($filter['league']))
        {
            $where[] = "l.id={$filter['league']}";
        }

        $where = implode(" AND ",$where);
        $where2 = implode(" AND ",$where2);

        $count = RecommendModel::countRecommendList($where,$where2);

        $limit = "LIMIT {$pager->getFirstIndex()},{$size}";


        $list = RecommendModel::RecommendList($where,$where2,$order,null,$limit);

        return ["list" => $list, "meta" => $pager->getPager($count)];
    }
}