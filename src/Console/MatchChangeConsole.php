<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/5
 * Time: 17:56
 */

namespace Console;

use Service\MatchChangeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
class MatchChangeConsole extends Command
{

    public function configure()
    {
        $this->setName('change_match')
            ->setDescription('更新比赛信息');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        return MatchChangeService::change(true);
    }
}