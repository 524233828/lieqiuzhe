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
use PHPHtmlParser\Dom;
use Qiutan\League;
use Qiutan\Lottery;
use Qiutan\Match;
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

        $match_id = 1395350;
        Match::$redis = redis();
        $res = Match::matchInfo($match_id);

        if(!isset($res['match']) || !is_array($res['match']))
        {
            return false;
        }

        if(!isset($res['match'][0])){
            $res['match'] = [ 0 => $res['match'] ];
        }

        foreach ($res['match'] as $info)
        {
            $match_id = $info['ID'];

            $html = $info['Briefing'];

//            $player_suspend = $info['PlayerSuspend'];

            $dom = new Dom();

            $dom->load($html);

            $red_t1 = $dom->find('.red_t1')[0]->getParent()->find('tr');

            unset($red_t1[0]);

            $home_info = [];

            foreach ($red_t1 as $red)
            {

                $home_info[] = [
                    "match_id" => $match_id,
                    "team_type" => 0,
                    "desc" => $red->find('td')[0]->innerhtml,
                    "create_time" => time(),
                ];
//                var_dump($red->find('td')[0]->innerhtml);
            }

            $blue_t1 = $dom->find('.blue_t1')[0]->getParent()->find('tr');

            unset($blue_t1[0]);

            foreach ($blue_t1 as $blue)
            {

                $away_info[] = [
                    "match_id" => $match_id,
                    "team_type" => 1,
                    "desc" => $blue->find('td')[0]->innerhtml,
                    "create_time" => time(),
                ];
            }

            unset($dom);

            $info = array_merge($home_info, $away_info);

//            var_dump($info);
//            var_dump($html);
//            var_dump($player_suspend);
        }

    }
}