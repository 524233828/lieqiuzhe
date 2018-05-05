<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/27
 * Time: 10:32
 */

namespace Logic;

use Exception\BaseException;
use Model\LeagueModel;
use Model\MatchCollectionModel;
use Model\MatchModel;
use Service\Pager;

class MatchListLogic extends BaseLogic
{
    public function fetchMatchList($type, $league_id = null, $date = null, $page = 1, $size = 20)
    {
        $first_index =  $size * ($page-1);

        $where = [];
        if(!empty($league_id)){
            $where["m.league_id"] = $league_id;
        }

        if(!empty($date))
        {
            $start_time = strtotime($date);
            $end_time = strtotime($date."+1 day");
            $where["m.start_time[>=]"] = $start_time;
            $where["m.start_time[<]"] = $end_time;
        }

        switch ($type){
            case 0://即时比分
                $where["m.status"] = [0, 1, 2, 3, 4];


                break;
            case 1://赛果
                $where["m.status"] = [-1];
                $where["ORDER"] = ["start_time" => "DESC"];
                break;
            case 2://赛程
                $where["m.status"] = [0];
                break;
            case 3://胜负彩
                break;
            default:
                break;
        }

        $count = MatchModel::count($where);
        $where["LIMIT"] = [$first_index, $size];

        $res = MatchModel::fetch(
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
                "m.home_score",
                "m.away_score",
                "m.home_red",
                "m.away_red",
                "m.home_yellow",
                "m.away_yellow",
                "m.status",
            ]
        );

        $match_index = [];
        foreach ($res as $k => $v){
            $res[$k]['match_time'] = date("Y-m-d H:i:s", $v['match_time']);
            $res[$k]['is_collect'] = 0;
            $match_index[$v['match_id']] = $res[$k];
        }

        //获取我关注的
        $my_collect = MatchCollectionModel::fetch(["user_id" => UserLogic::$user['id']]);

        foreach ($my_collect as $value){
            if(isset($match_index[$value['match_id']])){
                $match_index[$value['match_id']]["is_collect"] = 1;
            }
        }

        $page = new Pager($page,$size);

        return ["list" => array_values($match_index), "meta"=>$page->getPager($count)];

    }

    public function collect($match_id)
    {
        $user_id = UserLogic::$user["id"];

        if(empty($match_id)){
            BaseException::ParamsMissing();
        }

        $data = [
            "match_id" => $match_id,
            "user_id" => $user_id,
            "create_time" => time()
        ];

        return MatchCollectionModel::add($data);
    }

    public function collectCancel($match_id)
    {
        $user_id = UserLogic::$user["id"];

        if(empty($match_id)){
            BaseException::ParamsMissing();
        }

        $where = [
            "match_id" => $match_id,
            "user_id" => $user_id,
        ];

        return MatchCollectionModel::delete($where);
    }

    public function fetchLeague($type = 0, $date = null)
    {

        if(empty($date)){
            return ["list" => LeagueModel::fetch(null, ["id","gb_short(league_name)"])];
        }

        $start_time = strtotime($date);

        $end_time = strtotime($date . "+1 day");

        switch ($type){
            case 0://即时比分
                $where["m.status"] = [0, 1, 2, 3, 4];
                $status = "0,1,2,3,4";
                break;
            case 1://赛果
                $status = "-1";
                $where["ORDER"] = ["start_time" => "DESC"];
                break;
            case 2://赛程
                $status = "0";
                break;
            case 3://胜负彩
                break;
            default:
                break;
        }

        $sql = <<<SQL
SELECT
	count(*) as match_num,
	league_id as id,
	league.gb_short as league_name
FROM
	`match`
LEFT JOIN league on `match`.league_id=league.id
WHERE
	`match`.`status` IN ({$status})
AND `match`.start_time >= 1525449600
AND `match`.start_time < 1525536000
GROUP BY
	`match`.league_id
SQL;

        $res = database()->query($sql);

        return ["list" => $res];
    }
}