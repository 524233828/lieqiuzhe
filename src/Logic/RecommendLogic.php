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
        $uid = UserLogic::$user['id'];
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


    public function addRecommend($params)
    {
        $uid = UserLogic::$user['id'];

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

    public function getRecommendDetail($rec_id)
    {
        $res = RecommendModel::fetchOne($rec_id);
        if(!$res) {
            RecommendException::recommendEmpty();
        }
        $uid = UserLogic::$user['id'];

        $options = OptionModel::getOptionByOddId($res['odd_id'],['id','option','odds_rate']);
        $res['option'] = $options;
        $res['win_streak'] = $res['record'] == '' ? 0 : FuntionHelper::continuityWin($res['record']);
        $res['hit_rate'] = $res['record'] == '' ? 0 : FuntionHelper::winRate($res['record']);
        $res['rec_time'] =date('m/d H:i', $res['rec_time']);
        $res['is_read'] = 1;
        $extra = json_decode($res['extra'], true);
        $res['extra'] = count($extra) == 0 ? '' : $extra;

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
            if(!$current_level){
                $current_level = 0;
            }else{
                $current_level = $current_level['level'];
            }
            if($count > config()->get('user')[$current_level]){
                $res['rec_desc'] = '';
                $res['option'] = null;
                $res['extra'] = null;
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

    /**
     * 获取
     * @param $order
     * @param $filter
     * @param int $page
     * @param int $size
     * @return array
     */
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
            $where[] = "l.id={$filter['league_id']}";
        }

        if(!empty($filter['match_id']))
        {
            $where[] = "m.id={$filter['match_id']}";
        }

        $where = implode(" AND ",$where);
        $where2 = implode(" AND ",$where2);

        $count = RecommendModel::countRecommendList($where,$where2);

        $limit = "LIMIT {$pager->getFirstIndex()},{$size}";


        $list = RecommendModel::RecommendList($where,$where2,$order,null,$limit);

        //获取当前等级图标，NMSL
        $analyst_index_list = [];
        $analyst_ids = [];
        foreach ($list as $k => $v)
        {
            $analyst_index_list[$v['analyst_id']] = $v;
            $analyst_ids[] = $v['analyst_id'];
        }

        $levels = AnalystLevelOrderModel::fetchAnalystCurrentLevel($analyst_ids);

        $analyst_index_level = [];
        foreach ($levels as $level){
            $analyst_index_level[$level['uid']] = $level;
        }

        $icons = IconsModel::fetch("*", ["type" => 1]);

        //["1" => "url"]
        $level_icons = [];
        foreach ($icons as $icon)
        {
            $level_icons[$icon['level']] = $icon['icon'];
        }

        foreach ($analyst_index_list as $analyst_id => $value)
        {
            $analyst_index_list[$analyst_id]['level'] = $analyst_index_level[$analyst_id]['level'];
            $analyst_index_list[$analyst_id]['icon'] = $level_icons[$analyst_index_level[$analyst_id]['level']];
        }

        $list = array_values($analyst_index_list);

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
//var_dump($count);exit;
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

    public function filterLeagueAndMatch($odd_type = 1)
    {
        $start_time = time();

        //七天后
        $end_time = strtotime(date("Ymd", $start_time + 7 * 86400));

        $league_list = OddModel::getOddLeague($start_time, $end_time);

        //TODO:假数据
        $league_list = [
            [
                "id" => 1366,
                "league_name" => "国际友谊"
            ],
            [
                "id" => 128,
                "league_name" => "奥乙"
            ]
        ];

        $where = [
            MatchModel::$table.".start_time[>=]" => $start_time,
            MatchModel::$table.".start_time[<]" => $end_time,
            OddModel::$table.".type" => $odd_type
        ];

        $res = OddModel::fetchOddMatchList([OddModel::$table.".match_id(mid)", OddModel::$table.".id"],$where);

        $match_ids = [];
        $match_index = [];
        foreach ($res as $v){
            $match_ids[] = $v['mid'];
            $match_index[$v['mid']] = $v['id'];
        }

        $where = [];
        $where["m.status"] = [0];
        if(!empty($match_ids)){
            $where["m.id"] = $match_ids;
            $where["ORDER"] = ["m.start_time" => "ASC"];

            $list = MatchModel::fetchMatch(
                $where,
                [
                    "m.id(match_id)",
                    "l.gb_short(league_name)",
                    "l.color(league_color)",
                    "h.gb(home)",
                    "a.gb(away)",
                ]
            );
        }else{
            $list = [];
        }

        //TODO:假数据
        $list = [
            [
                "match_id" => 1552951,
                "league_name" => "国际友谊",
                "league_color" => "#4666bb",
                "home" => "塞内加尔U17",
                "away" => "巴拉圭U16",
            ],
            [
                "match_id" => 1404577,
                "league_name" => "奥乙",
                "league_color" => "#4756D8",
                "home" => "BW林茨",
                "away" => "华顿斯",
            ],
            [
                "match_id" => 1404580,
                "league_name" => "奥乙",
                "league_color" => "#4756D8",
                "home" => "里德",
                "away" => "卡芬堡",
            ],
        ];



        return ["league_list" => $league_list, "match_list" => $list];

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
}