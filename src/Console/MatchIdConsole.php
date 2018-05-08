<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/7
 * Time: 16:02
 */

namespace Console;

use Model\MatchModel;
use Qiutan\Match;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchIdConsole extends Command
{

    public function configure()
    {
        $this->setName('fetch_match_id')
            ->setDescription('获取比赛信息');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $match = MatchModel::fetch(["m.status" => [1, 2, 3, 4]],["m.id"]);

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
        if(isset($res['match'][0])){
            foreach ($res['match'] as $match) {

                if(!isset($match['a']) || empty($match['a'])){
                    continue;
                }

                $match_data = [
                    "status" => $match["f"],
                ];
                if(count($match_data) > 0) {
                    database()->update("match", $match_data, ["id"=>$match["a"]]);
                }
            }
        }else{
            $match = $res['match'];

            if(!isset($match['a']) || empty($match['a'])){
                return false;
            }

            $match_data = [
                "status" => $match["f"],
            ];
            if(count($match_data) > 0) {
                database()->update("match", $match_data, ["id"=>$match["a"]]);
            }
        }

    }

}