<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/7
 * Time: 16:02
 */

namespace Console;

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

}