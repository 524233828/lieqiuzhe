<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/28
 * Time: 14:28
 */

namespace Console;


use Model\OddModel;
use Model\OptionModel;
use Qiutan\League;
use Qiutan\Lottery;
use Qiutan\Odds;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestConsole extends Command
{

    public function configure()
    {
        $this->setName('test')
            ->setDescription('测试专用');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        Odds::$redis = redis();

        $res = Odds::odd();

//        if(!isset($res['i']) || !is_array($res['i'])){
//            return false;
//        }
//        if(!isset($res['i'][0])){
//            $res['i'] = [0=> $res['i']];
//        }
//
//        $match_ids = [];
//
//        foreach ($res['i'] as $lottery)
//        {
//            if(strpos($lottery['LotteryName'],"胜负彩")){
//                $match_ids[] = $lottery['ID_bet007'];
//            }
//        }

        $res = explode("$",$res, 4);

        $odds = explode(";",$res[2]);

        $time = time();



        $count = 0;

        foreach ($odds as $odd)
        {
            list(
                $match_id,
                $company_id,
                $pankou,
                $home_chupan,
                $away_chupan,
                $jishi_pankou,
                $home_jishi,
                $away_jishi,
                $is_fengpan,
                $is_zoudi
                ) = explode(",", $odd);

            if($company_id == 3){
                $count++;
                $odd_data = [
                    "match_id" => $match_id,
                    "extra" => json_encode(
                        [
                            "first_handicap"=>$pankou,
                            "first_home" => $home_chupan,
                            "first_away" => $away_chupan,
                            "current_handicap" => $jishi_pankou,
                            "current_home" => $home_jishi,
                            "current_away" => $away_jishi
                        ]
                    ),
                    "type" => 1,
                    "status" => ($is_fengpan=="True")? 0 : 1,
                    "create_time" => $time
                ];

                $odd = OddModel::getOddByMatchId($match_id, ['id']);
                database()->pdo->beginTransaction();
                if(!$odd)
                {

                    $odd_id = OddModel::add($odd_data);

                    $option_data = [
                        [
                            "odd_id" => $odd_id,
                            "option" => "主胜",
                            "odds_rate" => $home_jishi,
                        ],
                        [
                            "odd_id" => $odd_id,
                            "option" => "客胜",
                            "odds_rate" => $away_jishi,
                        ]
                    ];

                    $option = OptionModel::add($option_data);

                    if($odd_id && $option)
                    {
                        database()->pdo->commit();
                    }else{
                        database()->pdo->rollBack();
                    }

                }else{
                    $odd_id = $odd['id'];

                    $option = OptionModel::fetch(['id'],['odd_id' => $odd_id, 'option' => "主胜"]);

                    $home_id = $option[0]['id'];

                    $home = OptionModel::update(["odds_rate" => $home_jishi], ["id" => $home_id]);

                    $option = OptionModel::fetch(['id'],['odd_id' => $odd_id, 'option' => "客胜"]);

                    $away_id = $option[0]['id'];

                    $away = OptionModel::update(["odds_rate" => $away_jishi], ["id" => $away_id]);

                    if($home && $away)
                    {
                        database()->pdo->commit();
                    }else{
                        database()->pdo->rollBack();
                    }
                }


            }
        }

        echo $count;
    }
}