<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/28
 * Time: 14:28
 */

namespace Console;


use Model\TeamModel;
use Qiutan\Team;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TeamConsole extends Command
{

    public function configure()
    {
        $this->setName('fetch_team')
            ->setDescription('获取球队信息');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        Team::$redis = redis();
        $res = Team::get();

        $time = time();

        foreach ($res['i'] as $v)
        {

            $team_data = [
                "id" => $v['id'],
                "create_time" => $time,
                "league_id" => $v['lsID'],
                "gb" => $v['g'],
                "big" => $v['b'],
                "en" => $v['e'],
                "found" => $v['Found'],
                "area" => $v['Area'],
                "gym" => $v['gym'],
                "capacity" => $v['Capacity'],
                "flag" => is_string($v['Flag'])?"http://zq.win007.com/Image/team/".$v['Flag']:"",
                "addr" => $v['addr'],
                "url" => $v['URL'],
                "master" => $v['master'],
            ];
            $team = TeamModel::get($v['id'],["id"]);
            if(!isset($team['id'])){
                echo "新增球队【{$v['id']}】",$v['g'];
                database()->insert("team", $team_data);
            }

        }
    }
}