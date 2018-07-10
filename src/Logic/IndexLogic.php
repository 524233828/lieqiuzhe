<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 18:49
 */

namespace Logic;


use Helper\FuntionHelper;
use Model\AdventureModel;
use Model\AnalystInfoModel;
use Model\BannerModel;
use Model\MatchModel;
use Model\RecommendModel;
use Model\TopLineModel;
use Model\UserModel;
use Service\Pager;

class IndexLogic extends BaseLogic
{

    public function userInfo()
    {
        $uid = UserLogic::$user['id'];

        return UserModel::getUserInfo($uid,["avatar"]);
    }

    public function banner()
    {
        $list =  BannerModel::fetch(
            [
                "img_url",
                "page_id",
                "url",
                "params",
            ],
            [
                "status" => 1,
                "ORDER" => ["sort" => "DESC"]
            ]
        );
        return ["list" => $list];
    }

    public function topLine()
    {
        $list =  TopLineModel::fetch(
            [
                "content",
                "page_id",
                "url",
                "params",
            ],
            [
                "status" => 1,
                "ORDER" => ["sort" => "DESC"]
            ]
        );
        return ["list" => $list];
    }

    public function adventure()
    {
        $list =  AdventureModel::fetch(
            [
                "img_url",
                "page_id",
                "url",
                "params",
            ],
            [
                "status" => 1,
                "ORDER" => ["sort" => "DESC"]
            ]
        );
        return ["list" => $list];
    }

    /**
     * 风云榜
     * @param int $page
     * @param int $size
     * @return array
     */
    public function ticketRank($page = 1, $size = 20)
    {
        $page = new Pager($page, $size);

        $list = RecommendModel::Rank($page->getFirstIndex(), $size, "gifts");

        foreach ($list as $k => $v)
        {
            $list[$k]['win_streak'] = FuntionHelper::continuityWin($v['win_str']);
            $list[$k]['hit'] = FuntionHelper::resultComputer($v['result_str']);
            $list[$k]['hit_rate'] = FuntionHelper::winRate($v['win_str']);
        }
//        $list = AnalystInfoModel::fetchTicketRank($page->getFirstIndex(), $size);
//
//        foreach ($list as $k => $v)
//        {
//            $list[$k]['win_streak'] = FuntionHelper::continuityWin($v['record']);
//        }

        $analyst_count = RecommendModel::RankCount();

        return ["list" => $list, "meta" => $page->getPager($analyst_count)];
    }

    /**
     * 命中榜
     * @param int $page
     * @param int $size
     * @param int $date
     * @return array
     */
    public function hitRateRank($page = 1, $size = 20, $date = null)
    {
        $page = new Pager($page, $size);

        $where = "1=1";
        if(!empty($date))
        {
            if($date == 7 )
            {
                $start_time = time()-604800;
                $end_time = time();
                $where .= " AND r.create_time>=$start_time AND r.create_time<$end_time";
            }else if ($date == 20){
                $start_time = time()-1728000;
                $end_time = time();
                $where .= " AND r.create_time>=$start_time AND r.create_time<$end_time";
            }
        }

        $list = RecommendModel::Rank($page->getFirstIndex(), $size, "hit_rate", $where);

        foreach ($list as $k => $v)
        {
            $list[$k]['win_streak'] = FuntionHelper::continuityWin($v['win_str']);
            $list[$k]['hit'] = FuntionHelper::resultComputer($v['result_str']);
            $list[$k]['hit_rate'] = FuntionHelper::winRate($v['win_str']);
        }

        $analyst_count = RecommendModel::RankCount($where);

        return ["list" => $list, "meta" => $page->getPager($analyst_count)];
    }

    public function myConcern($page = 1, $size = 20)
    {
        $page = new Pager($page, $size);

        
    }

    public function recommend()
    {
        $where["m.status"] = [0];
        $where["m.is_recommend"] = 1;
        $where["ORDER"] = ["m.update_time" => "DESC"];

        $res = MatchModel::fetchMatch(
            $where,
            [
                "m.id(match_id)",
                "l.gb_short(league_name)",
                "l.color(league_color)",
                "m.start_time(match_time)",
                "h.gb(home)",
                "h.flag(home_flag)",
                "a.gb(away)",
                "a.flag(away_flag)",
            ]
        );

        return ["list" => array_values($res)];
    }
}