<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/28
 * Time: 14:28
 */

namespace Console;


use Qiutan\League;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LeagueConsole extends Command
{

    public function configure()
    {
        $this->setName('fetch_league')
            ->setDescription('获取联赛信息');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $res = League::get();

        $league_data = [];
        $country_data = [];
        $time = time();
        foreach ($res['match'] as $v)
        {

            $league_data[] = [
                "id" => "",
                "create_time" => $time,
                "type" => $v['type'],
                "color" => $v['color'],
                "gb" => $v['gb'],
                "big" => $v['big'],
                "en" => $v['en'],
                "gb_short" => $v['gb_short'],
                "big_short" => $v['big_short'],
                "en_short" => $v['en_short'],
                "current_season" => $v['Curr_matchSeason'],
                "current_round" => $v['curr_round'],
                "sum_round" => $v['sum_round'],
                "country_id" => $v['countryID'],
                "area_id" => $v['areaID'],
                "sub_sclass" => isset($v['subSclass'])?$v['subSclass']:"",
                "logo" => $v['logo'],
            ];

            $country_data[$v['countryID']] = [
                "id" => $v['countryID'],
                "country" => $v['country'],
                "en" => $v['countryEn'],
                "logo" => $v['countryLogo']
            ];
        }

        database()->insert("league", $league_data);
        database()->insert("country", $country_data);
    }
}