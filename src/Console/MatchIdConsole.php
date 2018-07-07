<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/7
 * Time: 16:02
 */

namespace Console;

use Model\MatchCollectionModel;
use Model\MatchModel;
use Model\UserModel;
use Qiutan\Match;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wxapp\Wxapp;

class MatchIdConsole extends Command
{

    public function configure()
    {
        $this->setName('fetch_match_id')
            ->setDescription('获取比赛信息');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $match = MatchModel::fetchMatch(["m.status" => [1, 2, 3, 4]],["m.id"]);

        $ids = [];
        foreach ($match as $m)
        {
            $ids[] = $m['id'];
        }

        $ids = implode(",",$ids);

        Match::$redis = redis();

        $res = Match::getById($ids);

        if(!isset($res['match'])){
            return false;
        }

        if(!isset($res['match'][0])){
            $res['match'][0] = $res['match'];
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
                list($league_gb,$league_big,$league_en,$league_id) = explode(",",$match['c']);
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

            $match_data = [
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
            if(count($match_data) > 0) {
                database()->update("match", $match_data, ["id"=>$match["a"]]);
            }

            //比赛完结的话，做推送
            if($match["f"] == -1){
                $collect = MatchCollectionModel::fetch("*", ["match_id"=>$match["a"]]);

                $conf = config()->get("wxapp");
                $wxapp = new Wxapp($conf['app_id'], $conf['app_secret']);
                $template_id = "cfUmrby1lPoMSrwvhrxTrOO-8aKY9MlJWg_J-gF9ri4";

                foreach ($collect as $v){
                    $user = UserModel::getUserInfo($v['user_id'], ["openid"]);

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
                    if(!empty($v['from_id'])){

                        $data = [
                            "keyword1"=>[
                                "value"=> "{$home_gb} vs {$away_gb}",
                                "color" => "#FF0000"
                            ],
                            "keyword2"=>[
                                "value"=> $match['d'],
                                "color" => "#173177"
                            ],
                            "keyword3"=>[
                                "value"=> "{$match['j']}:{$match['k']}",
                                "color" => $match['color']
                            ]
                        ];

                        $wxapp->bindRedis(redis())
                            ->sendTemplateMsg(
                                $user['openid'],
                                $template_id,
                                $v['from_id'],
                                $data,
                                "pages/index"
                            );
                    }
                }
            }
        }
    }

}