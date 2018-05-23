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
use Exception\BaseException;
use Helper\FuntionHelper;
use Model\AnalystInfoModel;
use Model\LeagueModel;
use Model\FansModel;
use Model\MatchCollectionModel;
use Model\MatchModel;
use Model\RecommendModel;
use Qiutan\Lottery;
use Qiutan\RedisHelper;
use Service\MatchChangeService;
use Service\Pager;

class AnalystLogic extends BaseLogic
{
    public function fetchAnalystInfo($analyst_id)
    {
        $analyst_info = AnalystInfoModel::getAnalystById(
            $analyst_id
        );

        //统计
        $analyst_info['win_streak'] = FuntionHelper::continuityWin($analyst_info['record']);
        $analyst_info['win_week'] = FuntionHelper::continuityWin($analyst_info['record']);
        $analyst_info['hit_rate'] = FuntionHelper::winRate($analyst_info['record']);

        //粉丝
        $analyst_info['fans'] = FansModel::getCountFansByAnalystId($analyst_id);
        //饭票
        $analyst_info['gifts'] = '3W';

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

        return $analyst_info;

    }

    public function fetchAnalystMatchList($analyst_id, $page, $size)
    {
        $start =  $size * ($page-1);
        $rs = RecommendModel::getRecommendByAnalystId($analyst_id, $start, $size);
        return $rs;
    }



}