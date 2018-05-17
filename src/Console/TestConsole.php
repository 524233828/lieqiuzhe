<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/28
 * Time: 14:28
 */

namespace Console;


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

        var_dump($odds);
    }
}