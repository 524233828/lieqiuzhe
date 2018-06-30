<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/20
 * Time: 15:31
 */

namespace Logic;


use Constant\CacheKey;
use Exception\AnalystException;
use Exception\BaseException;
use Exception\RecommendException;
use Helper\FuntionHelper;
use Model\AnalystInfoModel;
use Model\AnalystLevelOrderModel;
use Model\FansModel;
use Model\IconsModel;
use Model\LeagueModel;
use Model\MatchInfoModel;
use Model\MatchModel;
use Model\OddModel;
use Model\OptionModel;
use Model\ReadHistoryModel;
use Model\RecommendModel;
use Model\TeamModel;
use Model\UserLevelOrderModel;
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
            //校验是否能够看
            $key = CacheKey::READ_COUNT.(date('Ymd')).':'.$uid;
            if(redis()->exists($key)) {
                $count = redis()->get($key);
            }else{
                $count = ReadHistoryModel::getReadCountOneDayByUserId($uid);
                redis()->set($key, $count);
            }
            //获取等级和每天能看次数
            $current_level = UserLevelOrderModel::getUserCurrentLevel($uid);
            if($count > config()->get('user')[$current_level]){
                $res['rec_desc'] = '';
                $res['option'] = [];
                $res['extra'] = [];
                $res['is_read'] = 0;
                //如果不能看，填空部分内容
            }else{
                //如果能看，减去能看次数，追加查看记录
                if(ReadHistoryModel::findReadRecord($uid, $rec_id)){
                    ReadHistoryModel::updateReadRecord($uid, $rec_id);
                }else{
                    ReadHistoryModel::addReadRecord($uid, $rec_id);
                    $count  = $count + 1;
                    redis()->set($key, $count);
                }
            }
        }else{
            $res['rec_desc'] = '';
            $res['option'] = [];
            $res['extra'] = [];
            $res['is_read'] = 0;
            //如果不能看，填空部分内容
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

    public function fetchRecommendList($order, $filter, $page = 1, $size = 20)
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

        if(!empty($filter["win_rate_7"]))
        {
            $start_time = time()-604800;
            $end_time = time();
            $where2[] = "create_time>={$start_time}";
            $where2[] = "create_time<{$end_time}";
            $where[] = "hit_rate.hit_rate>={$filter["win_rate_7"]}";
        }
        if(!empty($filter["win_rate_30"]))
        {
            $start_time = time()-2592000;
            $end_time = time();
            $where2[] = "create_time>=$start_time";
            $where2[] = "create_time<$end_time";
            $where[] = "hit_rate.hit_rate>={$filter["win_rate_30"]}";
        }

        if(!empty($filter["win_rate"]))
        {
            $where[] = "hit_rate.hit_rate>={$filter["win_rate"]}";
        }

        if(!empty($filter["ticket"]))
        {
            $where[] = "a.ticket>={$filter["ticket"]}";
        }

        if(!empty($filter['league_id']))
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

    public function fetchReadHistoryList($page, $size)
    {
        $result = [];
        $result['list'] = [];
        $uid = UserLogic::$user['id'];

        $start = ($page - 1) * $size;
        $res = ReadHistoryModel::getReadHistoryByUserId(
            $uid,
            $start,
            $size
        );

        $count = ReadHistoryModel::getReadCountByUserId(
            $uid
        );

        if($res){
            foreach ($res as &$v) {
                if(!$v['win_str'] && 0 != $v['win_str']){
                    $res = [];
                    break;
                }
                $v['win_streak'] = FuntionHelper::continuityWin($v['win_str']);
                $v['hit_rate'] = FuntionHelper::winRate($v['result_str']);
                $v['rec_time'] = date('m/d H:i:s',$v['rec_time']);
                $v['hit'] = FuntionHelper::resultComputer($v['result_str']);;
                $v['gifts'] = $v['ticket'];
                $current_level = AnalystLevelOrderModel::getAnalystCurrentLevel($v['user_id']);
//                $v['level'] = $current_level;
                $v['level'] = 2;//写死先
                $v['icon'] = IconsModel::getAnalystIcon(2);
//                $v['icon'] = IconsModel::getAnalystIcon($current_level);
                unset($v['record']);
                unset($v['ticket']);
                unset($v['result_str']);
                unset($v['win_str']);
            }
        }


        $page = new Pager($page,$size);
        $result['meta'] = $page->getPager($count);
        $result['list'] = $res;
        return $result;
    }
}