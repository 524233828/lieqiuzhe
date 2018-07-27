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

        //计算胜负

        $status = 0;//状态 1-主胜 2-客胜 3-走水
        if(($match['home_score'] + $extra['first_handicap']) > $match['away_score'])
        {
            $status = 1;
        }elseif (($match['home_score'] + $extra['first_handicap']) == $match['away_score']){
            $status = 3;
        }else{
            $status = 2;
        }
        $score_gap = $match['home_score'] - $match['away_score'];

        $home_win = ($score_gap >= $extra['first_handicap']);

        //取出菠菜的选项
        $options = OptionModel::getOptionByOddId($odd['id'], ["id", "option"]);

        $win_id = 0;
        foreach ($options as $option)
        {
            if($option['option'] == "主胜" && $status == 1)
            {
                $win_id = $option['id'];
            }

            if($option['option'] == "客胜" && $status == 2)
            {
                $win_id = $option['id'];
            }
        }

        if($status == 3)
        {
            //走水，更新所有推荐状态
            $recommend_data = [
                "result" => 3,
                "is_win" => 0,
            ];

            RecommendModel::update($recommend_data,["odd_id" => $odd['id']]);
        }

        //取出所有该菠菜的推荐单
        $recommends = RecommendModel::fetchRecommendByOddId($odd['id']);

        $recommends_data = [];
        foreach ($recommends as $key => $recommend)
        {
            if($recommend['option_id'] == $win_id)
            {
                $recommends_data[] = [
                    "id" => $recommend['id'],
                    "result" => 1,
                    "is_win" => 1,
                ];
            }else{
                $recommends_data[] = [
                    "id" => $recommend['id'],
                    "result" => 2,
                    "is_win" => 0,
                ];
            }
        }

        //批量更新
        $row = batch_update(RecommendModel::$table, $recommends_data, "id");
    }
}