<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/27
 * Time: 18:24
 */

namespace Service;


use Model\AnalystInfoModel;
use Model\RecommendModel;
use Model\UserMedalModel;

class MedalService
{

    const TEN_HIT = 1;      //十连中
    const TICKET_RANK = 2;  //风云榜
    const HIT_MONTH = 3;    //命中帮月榜
    const HIT_WEEK = 4;     //命中榜周榜
    const HIT_ALL = 5;      //命中榜总榜

    //风云榜，每周结算一次
    public function ticketRank()
    {
        //获取球票最多的分析师
        $analysts = AnalystInfoModel::fetch(["id"],["ORDER"=>["ticket" => "DESC"], "LIMIT"=>[0, 1]]);

        $analyst = $analysts[0];

        return $this->sendMedal($analyst['id'], self::TICKET_RANK);
    }

    //命中榜总榜
    public function hitAllRank()
    {
        //获取总命中率
        $analysts = RecommendModel::Rank(0, 1);

        $analyst = $analysts[0];

        return $this->sendMedal($analyst['analyst_id'], self::HIT_ALL);
    }

    //命中榜月榜
    public function hitMonthRank()
    {

        $end_time = time();

        $start_time = time()-2592000;//一个月前

        $where = "r.create_time>={$start_time} AND r.create_time<$end_time";
        //获取总命中率
        $analysts = RecommendModel::Rank(0, 1, "hit_rate", $where);

        $analyst = $analysts[0];

        return $this->sendMedal($analyst['analyst_id'], self::HIT_MONTH);
    }

    //命中榜周榜
    public function hitWeekRank()
    {

        $end_time = time();

        $start_time = time()-604800;//一周前

        $where = "r.create_time>={$start_time} AND r.create_time<$end_time";
        //获取总命中率
        $analysts = RecommendModel::Rank(0, 1, "hit_rate", $where);

        $analyst = $analysts[0];

        return $this->sendMedal($analyst['analyst_id'], self::HIT_WEEK);
    }

    //发放奖牌
    public function sendMedal($uid, $medal_id)
    {
        $data = [
            "user_id" => $uid,
            "medal_id" => $medal_id
        ];

        $user_medal = UserMedalModel::fetch("*", $data);
        if(count($user_medal)>0)
        {
            //已经有了，更新
            $result = UserMedalModel::update(["num[+]" => 1],$data);
        }else{
            $data["num"] = 1;
            //发放奖牌
            $result = UserMedalModel::add($data);
        }
        return $result !== false;
    }
}