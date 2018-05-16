<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/16
 * Time: 17:36
 */

namespace Console;


use Qiutan\Match;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchModifyConsole extends Command
{
    public function configure()
    {
        $this->setName('match_modify')
            ->setDescription('获取比赛更新信息');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        Match::$redis = redis();

        $res = Match::modifyRecord();

        if(!isset($res['match']))
        {
            return false;
        }

        if(!isset($res['match'][0])){
            $res['match'] = [ 0 => $res['match'] ];
        }

        var_dump($res['match'][0]);
    }
}