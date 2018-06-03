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
use Model\SearchModel;
use Model\LeagueModel;
use Model\MatchInfoModel;
use Model\MatchModel;
use Model\OddModel;
use Model\OptionModel;
use Model\RecommendModel;
use Model\TeamModel;
use Model\UserModel;
use Qiutan\Match;

class SearchLogic extends BaseLogic
{

    public function getHotKeywords()
    {
        $hot_keywords = SearchModel::fetchByIsHot([], 'keyword');
        $normal_keywords = [];
        if(10 >= count($hot_keywords)){
            $normal_keywords = SearchModel::fetchBySearchTime(10 - count($hot_keywords), 'keyword');
        }

        return array_merge( array_column($hot_keywords, 'keyword'), array_column($normal_keywords, 'keyword'));

    }


    public function searchByKeywords($keywords)
    {
       //比赛
        $teamids = TeamModel::fetchByKeyWords($keywords,'id');
        $matchs = [];
        if($teamids) {
            foreach ($teamids as $v) {
                $match = MatchModel::fetchByTeamId($v);
                false !== $match && $matchs[] = $match;
            }

        }

        //分析师
        $analysts = AnalystInfoModel::fetchByKeyWords($keywords);
        if($analysts) {
            if(!is_null($analysts[0]['user_id'])) {
                $analysts = FuntionHelper::arrayUnion($analysts, 'user_id');
                foreach ($analysts as &$v) {
                    $v['win_streak'] = FuntionHelper::continuityWin($v['win_str']);
                    $v['hit_rate'] = FuntionHelper::changeBaifenbi($v['hit_rate']);
                    unset($v['win_str']);
                    unset($v['result_str']);
                }
            }else{
                $analysts = [];
            }
        }


        return [
            'keyword' => $keywords,
            'analyst' => $analysts,
            'match' => $matchs,
            'class' => [],
            'video' => [],
        ];

    }


}