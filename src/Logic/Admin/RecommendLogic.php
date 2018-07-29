<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/20
 * Time: 15:31
 */

namespace Logic\Admin;


use Constant\CacheKey;
use Exception\AnalystException;
use Exception\BaseException;
use Exception\RecommendException;
use Exception\UserException;
use Helper\FuntionHelper;
use Logic\Admin\AdminBaseLogic;
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

class RecommendLogic extends AdminBaseLogic
{

    public function addAction($params)
    {
        if(!UserModel::get($params['analyst_id'])) {
            UserException::UserNotFound();
        }

        $uid = $params['analyst_id'];

        $user = UserModel::getUserInfo($uid,['user_type']);

        if($user['user_type'] != 1)
        {
            AnalystException::userNotAnalyst();
        }

        if(!OptionModel::fetch('id',['odd_id'=>$params['odd_id'],'id'=>$params['option_id']])) {
            BaseException::ParamsError();
        }

        if(empty($params['rec_title']) || empty($params['rec_desc'])) {
            BaseException::ParamsError();
        }

//        $analyst = AnalystInfoModel::getInfoByUserId($uid,['level']);
        $analyst_level = AnalystLevelOrderModel::getAnalystCurrentLevel($uid);

        $today = date("Y-m-d");

        $start_time = strtotime($today);
        $end_time = strtotime($today."+1 day");
        $recommend_count = RecommendModel::countRecommend([
            "create_time[>=]" => $start_time,
            "create_time[<]" => $end_time,
            "analyst_id" => $uid
        ]);


        if(!$this->isWrite($recommend_count))
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

        $recommend = RecommendModel::addAndLastId($data);

        if($recommend)
        {
            return ['rec_id'=>$recommend];
        }else{
            RecommendException::recommendFail();
        }
    }

    public function listAction($page = 1, $size = 20)
    {
        $pager = new Pager($page, $size);
        $start =  $size * ($page-1);
        $rs = RecommendModel::getRecommends($start, $size);
        $count = RecommendModel::countRecommend([]);
        foreach ($rs as &$v) {
            $result = RecommendModel::fetchOne($v['rec_id']);
            $options = OptionModel::getOptionByOddId($result['odd_id'],['id','option','odds_rate']);
            $v['option'] = $options;
            $v['extra'] = json_decode($v['extra'], true);
        }
        return ['list'=> $rs, 'meta'=>$pager->getPager($count)];
    }

    public function getAction($params)
    {
        // TODO: Implement getAction() method.
    }

    public function deleteAction($params)
    {
        // TODO: Implement deleteAction() method.
    }

    public function updateAction($params)
    {
        // TODO: Implement updateAction() method.
    }

    public function isWrite($recommend_count)
    {

        //TODO: 更换成根据分析师等级变动
        $can_add = 5;

        if($recommend_count >= $can_add)
        {
            return false;
        }

        return true;
    }

    public function matchInfo($odd_id, $uid)
    {
        $today = date("Y-m-d");

        $start_time = strtotime($today);
        $end_time = strtotime($today."+1 day");
        $recommend_count = RecommendModel::countRecommend([
            "create_time[>=]" => $start_time,
            "create_time[<]" => $end_time,
            "analyst_id" => $uid
        ]);
        $is_write = $this->isWrite($recommend_count)? 1 : 0;
        //TODO: 假数据
        $json = "{\"league_name\":\"西甲\",\"match_time\":1526825700,\"home\":\"毕尔巴鄂竞技\",\"home_flag\":\"http:\/\/zq.win007.com\/Image\/team\/images\/2013121171136.jpg\",\"away\":\"西班牙人\",\"away_flag\":\"http:\/\/zq.win007.com\/Image\/team\/images\/20140818190012.png\",\"option\":[{\"option_id\":4087,\"option_name\":\"主胜\",\"odds_rate\":\"1.00\"},{\"option_id\":4088,\"option_name\":\"客胜\",\"odds_rate\":\"0.89\"}],\"extra\":{\"first_handicap\":\"0.5\",\"first_home\":\"0.90\",\"first_away\":\"0.98\",\"current_handicap\":\"0\",\"current_home\":\"1.00\",\"current_away\":\"0.89\"},\"home_info\":[{\"id\":1,\"desc\":\"【阵容】毕尔巴鄂竞技俱乐部已证实主帅C.兹干达不会留任。另外球队目前3名后卫米克尔-多明戈斯、伊尼戈-马丁内斯及萨沃里特均因黄牌累积停赛，对防线影响极大。\"},{\"id\":2,\"desc\":\"【状态】毕尔巴鄂竞技的主场成绩位列西甲倒数第4，赛季18个主场虽然保持超过7成胜率，但以8个平局成为西甲主场战平最多的队伍，缺乏足够的主场气势。\"}],\"away_info\":[{\"id\":3,\"desc\":\"【阵容】至今仍未与球队续约的老将前锋塞尔吉奥-加西亚上战贡献1球1助攻，而伤愈复出的中场皮亚蒂更于尾段操刀12码入球，球队攻力不容小觑。\"},{\"id\":4,\"desc\":\"【主帅】西班牙人主帅戴维-加莱戈因在对阵马德里竞技的比赛投诉过激而被逐，赛后被追加停赛两场，本场仍然未能在场边指挥。\"}]}";

        $result = json_decode($json, true);

        $result['is_write'] = $is_write;

        return $result;

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

        $response['is_write'] = $is_write;

        return $response;
    }
}