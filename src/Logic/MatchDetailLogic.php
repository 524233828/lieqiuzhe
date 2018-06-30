<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/27
 * Time: 10:32
 */

namespace Logic;

use Constant\CacheKey;
use Exception\MatchException;
use Exception\BaseException;
use Helper\FuntionHelper;
use Model\AnalystLevelOrderModel;
use Model\IconsModel;
use Model\LeagueModel;
use Model\MatchCollectionModel;
use Model\MatchInfoModel;
use Model\MatchModel;
use Model\ReadHistoryModel;
use Model\RecommendModel;
use Qiutan\Lottery;
use Qiutan\RedisHelper;
use Service\MatchChangeService;
use Service\Pager;

class MatchDetailLogic extends BaseLogic
{
    public function fetchMatchDetail($match_id)
    {
        $uid = UserLogic::$user['id'];
        if(empty($match_id)){
            BaseException::ParamsMissing();
        }

        $res = MatchModel::detail(
            $match_id
        );
        if(!$res) {
            MatchException::matchNotExist();
        }

        $res['is_collection'] = 0;
        if($uid) {
            $is_collection = MatchCollectionModel::fetch(
                ['id'],
                [
                    'match_id' => $match_id,
                    'user_id' => $uid,
                ]
            );
            $res['is_collection'] = $is_collection ? 1 : 0;
        }

        return $res;
    }

    public function fetchMatchAdvices($match_id)
    {

        $result = [
            'home' => [
                'info' => [],
                'suspend' => []
            ],
            'away' => [
                'info' => [],
                'suspend' => []
            ],
        ];
        if(empty($match_id)){
            BaseException::ParamsMissing();
        }

        $res = MatchInfoModel::detail(
            $match_id
        );

        if(!$res) {
            return $result;
        }

        foreach ($res as $v) {
            if(1 == $v['team_type']) {
                $result['home']['info'][]['desc'] = $v['desc'];
//                $result['home']['suspend']['name'] = $v['name'];  //缺数据
//                $result['home']['suspend']['reason'] = $v['reason'];
            }else{
                $result['away']['info'][]['desc'] = $v['desc'];
//                $result['away']['suspend']['name'] = $v['name'];
//                $result['away']['suspend']['reason'] = $v['reason'];
            }
        }
        return $result;
    }

    public function fetchRecomendList($match_id, $page, $size)
    {
        $result = [];
        $result['list'] = [];
        if(empty($match_id)){
            BaseException::ParamsMissing();
        }

        $start = ($page - 1) * $size;
        $res = RecommendModel::getRecommendByMatchId(
            $match_id,
            $start,
            $size
        );

        $count = RecommendModel::countRecommendByMatchId(
            $match_id
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