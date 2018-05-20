<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/20
 * Time: 9:17
 */

namespace Console;


use Qiutan\Match;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchInfoConsole extends Command
{
    public function configure()
    {
        $this->setName('fetch_match_info')
            ->setDescription('获取比赛情报');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        Match::$redis = redis();



        $res = Match::matchInfo();
    }
}