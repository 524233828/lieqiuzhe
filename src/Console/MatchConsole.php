<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/30
 * Time: 16:29
 */

namespace Console;

use Qiutan\Match;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchConsole extends Command
{

    public function configure()
    {
        $this->setName('fetch_match')
            ->setDescription('获取比赛信息');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $league = database()->select("league", ["id"]);

        Match::$redis = redis();

        $time = time();
        $match_data = [];
        foreach ($league as $v)
        {
            $res = Match::get(null, $v['id']);

            $match_data = [];
            if(!isset($res['match'])){
                continue;
            }
            foreach ($res['match'] as $match) {
                if(!isset($match['a']) || empty($match['a'])){
                    continue;
                }
                if(isset($match['h']) && is_string($match['h'])){
                    list($home_gb,$home_big,$home_en,$home_id) = explode(",",$match['h']);
                }else{
                    $home_id = 0;
                }

                if(isset($match['i']) && is_string($match['i'])){
                    list($away_gb,$away_big,$away_en,$away_id) = explode(",",$match['i']);
                }else{
                    $away_id = 0;
                }

                if(isset($match['c']) && is_string($match['c'])){
                    list($league_gb,$league_big,$league_en,$league_id,$is_simple) = explode(",",$match['c']);
                }else{
                    $league_id = 0;
                }
                $home_yellow = 0;
                $away_yellow = 0;
                if(isset($match['yellow']) && is_string($match['yellow'])){
                    list($home_yellow, $away_yellow) = explode("-",$match['yellow']);
                }else{
                    $league_id = 0;
                }


                $match_data[] = [
                    "id" => $match["a"],
                    "create_time" => $time,
                    "status" => $match["f"],
                    "start_time" => strtotime($match["d"]),
                    "home_id" => $home_id,
                    "away_id" => $away_id,
                    "league_id" => $league_id,
                    "home_score" => $match["j"],
                    "away_score" => $match["k"],
                    "home_half_score" => $match["l"],
                    "away_half_score" => $match["m"],
                    "home_red" => $match["n"],
                    "away_red" => $match["o"],
                    "home_yellow" => $home_yellow,
                    "away_yellow" => $away_yellow,
                    "home_rank" => $match["p"],
                    "away_rank" => $match["q"],
                    "match_desc" => is_array($match["r"]) ? "" : $match["r"],
                    "round" => $match["s"],
                    "area" => is_array($match["t"]) ? "" : $match["t"],
                    "weather_id" => $match["v"],
                    "temperature" => is_array($match["w"]) ? "" : $match["w"],
                    "season" => $match["x"],
                    "group" => $match["y"],
                    "is_neutral" => $match["z"],
                    "sub_id" => $match["subID"]
                ];
            }
            if(count($match_data) > 0) {
                database()->insert("match", $match_data);
            }
        }
    }
}