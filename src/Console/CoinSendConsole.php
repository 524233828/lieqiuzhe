<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/25
 * Time: 22:15
 */

namespace Console;


use Service\CoinService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CoinSendConsole extends Command
{

    protected function configure()
    {
        $this->setName('send_coin')
            ->setDescription('发放金币')
            ->addOption(
                'num',
                'n',
                InputOption::VALUE_REQUIRED,
                '发放数量'
            )
            ->addOption(
                'uid',
                'u',
                InputOption::VALUE_REQUIRED,
                '发放给哪个用户'
            )
            ->addOption('reason', 'r', InputOption::VALUE_OPTIONAL, '发放原因');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $num = $input->getOption('num');
        $uid = $input->getOption('uid');
        $reason = $input->getOption('reason') ? $input->getOption('reason') : '系统发放金币';

        CoinService::sendCoin($num, $uid, $reason);
    }
}