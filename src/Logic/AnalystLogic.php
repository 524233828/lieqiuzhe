<?php
/**
 * Created by PhpStorm.
 * User: zuoan
 * Date: 2018/5/23
 * Time: 10:32
 */

namespace Logic;

use Constant\CacheKey;
use Controller\AnalystController;
use Exception\AnalystException;
use Exception\BaseException;
use Helper\FuntionHelper;
use Model\AnalystInfoModel;
use Model\AnalystLevelOrderModel;
use Model\IconsModel;
use Model\LeagueModel;
use Model\FansModel;
use Model\MatchCollectionModel;
use Model\MatchModel;
use Model\OptionModel;
use Model\RecommendModel;
use Model\UserModel;
use Qiutan\Lottery;
use Qiutan\RedisHelper;
use Service\MatchChangeService;
use Service\Pager;

class AnalystLogic extends BaseLogic
{
    public function fetchAnalystInfoByUserId($user_id)
    {
        $uid = UserLogic::$user['id'];
        $analyst_info = AnalystInfoModel::getAnalystDetailByUserId(
            $user_id
        );

        if(!$analyst_info) {
            AnalystException::userNotAnalyst();
        }

        $analyst_info['is_fans'] = 0;
        if($uid) {
            $is_fans = FansModel::fetch(
                ['id'],
                [
                    'user_id' => $uid,
                    'analyst_id' => $analyst_info['user_id'],
                ]
            );
            $analyst_info['is_fans'] = $is_fans ? 1 : 0;
        }

        //统计
        $analyst_info['win_streak'] = FuntionHelper::continuityWin($analyst_info['win_str']);
        $analyst_info['win_week'] = FuntionHelper::continuityWin($analyst_info['win_str']);
        $analyst_info['hit_rate'] = FuntionHelper::winRate($analyst_info['hit_rate']);

        //粉丝
        $analyst_info['fans'] = FansModel::getCountFansByAnalystId($analyst_info['user_id']);
        unset($analyst_info['analyst_id']);
        //饭票
        $analyst_info['gifts'] = $analyst_info['ticket'];

        $analyst_info['medal'] = [
            [
                'name' => '10连中',
                'icon' => '',
                'value' => 4,
            ],
            [
                'name' => '风云榜冠军',
                'icon' => '',
                'value' => 3,
            ],
        ];

        $analyst_info['month_win'] = [
            [
                'league' => '西甲',
                'league_color' => 'ff0033',
                'value' => '4发3中1走',
            ],
            [
                'league' => '德甲',
                'league_color' => '#FF00FF',
                'value' => '9发3中1走',
            ],
            [
                'league' => '欧冠',
                'league_color' => '000033',
                'value' => '7发6中1走',
            ],
            [
                'league' => '意甲',
                'league_color' => '0033ff',
                'value' => '8发5中1走',
            ],
        ];
        unset($analyst_info['record']);
        unset($analyst_info['id']);

        return $analyst_info;

    }

    public function fetchAnalystMatchList($user_id, $page, $size)
    {
        $start =  $size * ($page-1);
        $rs = RecommendModel::getRecommendByUserId($user_id, $start, $size);
        foreach ($rs as &$v) {
            $result = RecommendModel::fetchOne($v['rec_id']);
            $options = OptionModel::getOptionByOddId($result['odd_id'],['id','option','odds_rate']);
            $v['option'] = $options;
            $v['extra'] = json_decode($v['extra'], true);
        }
        return $rs;
    }



    public function followAnalyst($params)
    {
        $uid = UserLogic::$user['id'];

        $analyst = AnalystInfoModel::getInfoByUserId($params['user_id'], ['id','user_id']);

        if(!$analyst)
        {
            AnalystException::analystNotExist();
        }

        if($uid == $analyst['user_id'])
        {
            AnalystException::userCanNotFollowSelf();
        }

        if(FansModel::getFansRecordByUidAndAnalystId($analyst['id'], $uid))
        {
            AnalystException::alreadyFollow();
        }

        $data = [
            "create_time"=>time(),
            "user_id" => $uid,
            "analyst_id" => $analyst['id'],
        ];

        $fans = FansModel::add($data);

        if($fans)
        {
            return true;
        }else{
            AnalystException::failFollow();
        }
    }


    public function unfollowAnalyst($params)
    {
        $uid = UserLogic::$user['id'];

        $analyst = AnalystInfoModel::getInfoByUserId($params['user_id'], ['id']);

        if(!$analyst)
        {
            AnalystException::alreadyUnfollow();
        }

        $record_id = FansModel::getFansRecordByUidAndAnalystId($analyst['id'], $uid);

        if(!$record_id)
        {
            AnalystException::alreadyUnfollow();
        }

        $res = FansModel::delete($record_id);

        if($res)
        {
            return true;
        }else{
            AnalystException::failUnfollow();
        }
    }

    public function myFollows()
    {
        $uid = UserLogic::$user['id'];
        $analyst = AnalystInfoModel::getFollowsByUserId($uid);

        if(!$analyst) {
            return [];
        }

        foreach ($analyst as &$v) {
            $info = AnalystInfoModel::getAnalystDetailByUserId($v['user_id']);
            $v['fans'] = FansModel::countFans($v['analyst_id']);
            $v['level'] = $info['level'];
            $v['level_icons'] = $info['level_icon'];
            $v['win_streak'] = FuntionHelper::continuityWin($info['win_str']);
            $v['hit_rate'] = FuntionHelper::winRate($info['result_str']);
            $v['hit'] = FuntionHelper::resultComputer($info['result_str']);
            //粉丝
            $v['fans'] = FansModel::getCountFansByAnalystId($info['user_id']);
            $v['gifts'] = $info['ticket'];

            //最新一场推荐
            $v['latest_rec'] = RecommendModel::getLastedRecommendByUserId($v['analyst_id']);
            $v['medal'] = [
                'https://a.ym8800.com/upload/2a04174a68ec72de357fd8f0bf2d4fd1.png',
                'https://a.ym8800.com/upload/bfcc12f3387be7f31fbff9b05c3e4cd7.png',
            ];
        }

        return $analyst;

    }


}