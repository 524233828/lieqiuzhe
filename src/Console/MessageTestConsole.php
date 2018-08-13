<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/8/13
 * Time: 18:16
 */

namespace Console;


use Service\MessageService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MessageTestConsole extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $match_id = $input->getOption('match_id');
        if(empty($match_id)){
            return false;
        }

        $msg_sdk = new MessageService();

        $result = $msg_sdk->concernStartPush($match_id);

        var_dump($result);

    }

    protected function configure()
    {
        $this->setName('match_push')
            ->setDescription('生成对应控制器的markdown文档')
            ->addOption(
                'match_id',
                'm',
                InputOption::VALUE_REQUIRED,
                '要生成文档的控制器'
            );
    }

}