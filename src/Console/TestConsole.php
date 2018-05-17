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

        Lottery::$redis = redis();

        $res = Lottery::matchIdInterface();

        var_dump($res);
    }
}