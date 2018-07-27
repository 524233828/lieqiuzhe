<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/26
 * Time: 22:49
 */

namespace Service;


use Model\MatchModel;
use Model\OddModel;
use Model\OptionModel;
use Model\RecommendModel;

class RecommendService
{

    public function settleRecommend($match_id)
    {
        //取出比赛信息
        $match = MatchModel::get($match_id);

        //取出博彩信息
        $odd = OddModel::getOddByMatchId($match_id, ["id","extra"]);

        if(!$odd)
        {
            return false;
        }

        $extra = json_decode($odd['extra'],true);

        //

        //计算胜负
        $score_gap = $match['home_score'] - $match['away_score'];

        $home_win = ($score_gap >= $extra['first_handicap']);

        //取出菠菜的选项
        $options = OptionModel::getOptionByOddId($odd['id'], ["id", "option"]);

        $win_id = 0;
        foreach ($options as $option)
        {
            if($option['option'] == "主胜" && $home_win)
            {
                $win_id = $option['id'];
            }

            if($option['option'] == "客胜" && !$home_win)
            {
                $win_id = $option['id'];
            }
        }

        //取出所有该菠菜的推荐单
        $recommends = RecommendModel::fetchRecommendByOddId($odd['id']);

        foreach ($recommends as $key => $recommend)
        {
            if($recommend['option_id'] == $win_id)
            {

            }
        }

    }
}