<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/28
 * Time: 14:28
 */

namespace Console;


use Model\MatchInfoModel;
use Model\MatchModel;
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

class OddConsole extends Command
{

    public function configure()
    {
        $this->setName('fetch_odd')
            ->setDescription('赔率更新');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        Odds::$redis = redis();

        $res = Odds::odd();

        $res = explode("$", $res, 4);

        $odds = explode(";", $res[2]);

        $time = time();

        $count = 0;

        foreach ($odds as $odd) {
            list(
                $match_id,
                $company_id,
                $pankou,
                $home_chupan,
                $away_chupan,
                $jishi_pankou,
                $home_jishi,
                $away_jishi,
                $is_fengpan,
                $is_zoudi
                ) = explode(",", $odd);

            if ($company_id == 3) {
                $count++;
                $odd_data = [
                    "match_id" => $match_id,
                    "extra" => json_encode(
                        [
                            "first_handicap" => $pankou,
                            "first_home" => $home_chupan,
                            "first_away" => $away_chupan,
                            "current_handicap" => $jishi_pankou,
                            "current_home" => $home_jishi,
                            "current_away" => $away_jishi
                        ]
                    ),
                    "type" => 1,
                    "status" => ($is_fengpan == "True") ? 0 : 1,
                    "create_time" => $time
                ];

                $odd = OddModel::getOddByMatchId($match_id, ['id']);
                database()->pdo->beginTransaction();
                if (!$odd) {

                    $odd_id = OddModel::add($odd_data);

                    if ($odd_id) {
                        $odd_id = database()->pdo->lastInsertId();
                    }

                    $option_data = [
                        [
                            "odd_id" => $odd_id,
                            "option" => "主胜",
                            "odds_rate" => $home_jishi,
                        ],
                        [
                            "odd_id" => $odd_id,
                            "option" => "客胜",
                            "odds_rate" => $away_jishi,
                        ]
                    ];

                    $option = OptionModel::add($option_data);

                    //增加赛前情报
                    $this->fetchMatchInfo($match_id);

                    if ($odd_id && $option) {
                        database()->pdo->commit();
                    } else {
                        database()->pdo->rollBack();
                    }

                } else {
                    $odd_id = $odd['id'];

                    $odd_data = [
                        "status" => ($is_fengpan == "True") ? 0 : 1,
                        "extra" => json_encode(
                            [
                                "first_handicap" => $pankou,
                                "first_home" => $home_chupan,
                                "first_away" => $away_chupan,
                                "current_handicap" => $jishi_pankou,
                                "current_home" => $home_jishi,
                                "current_away" => $away_jishi
                            ]
                        ),
                    ];

                    OddModel::update($odd_data, ["id" => $odd_id]);

                    $option = OptionModel::fetch(['id'], ['odd_id' => $odd_id, 'option' => "主胜"]);

                    $home_id = $option[0]['id'];

                    $home = OptionModel::update(["odds_rate" => $home_jishi], ["id" => $home_id]);

                    $option = OptionModel::fetch(['id'], ['odd_id' => $odd_id, 'option' => "客胜"]);

                    $away_id = $option[0]['id'];

                    $away = OptionModel::update(["odds_rate" => $away_jishi], ["id" => $away_id]);

                    if ($home && $away) {
                        database()->pdo->commit();
                    } else {
                        database()->pdo->rollBack();
                    }
                }
            }
        }
    }

    public function fetchMatchInfo($match_id)
    {
        Match::$redis = redis();
        $res = Match::matchInfo($match_id);

        if (!isset($res['match']) || !is_array($res['match'])) {
            return false;
        }

        if (!isset($res['match'][0])) {
            $res['match'] = [0 => $res['match']];
        }

        foreach ($res['match'] as $info) {
            $match_id = $info['ID'];
            $match = MatchModel::get($match_id,["home_id","away_id"]);

            $html = $info['Briefing'];

            if(!is_string($html)){
                continue;
            }

            $dom = new Dom();

            $dom->load($html);

            //主队赛前情报
            $red_t1 = $dom->find('.red_t1')[0]->getParent()->find('tr');

            unset($red_t1[0]);

            $home_info = [];

            foreach ($red_t1 as $red) {

                $home_info[] = [
                    "match_id" => $match_id,
                    "team_type" => 0,
                    "desc" => $red->find('td')[0]->innerhtml,
                    "create_time" => time(),
                    "team_id" => $match['home_id'],
                ];
            }

            //客队赛前情报
            $blue_t1 = $dom->find('.blue_t1')[0]->getParent()->find('tr');

            unset($blue_t1[0]);

            $away_info = [];

            foreach ($blue_t1 as $blue) {

                $away_info[] = [
                    "match_id" => $match_id,
                    "team_type" => 1,
                    "desc" => $blue->find('td')[0]->innerhtml,
                    "create_time" => time(),
                    "team_id" => $match['away_id'],
                ];
            }

            unset($dom);

            //伤停信息
            if (isset($info['PlayerSuspend']) && is_array($info['PlayerSuspend'])) {
                if (!isset($info['PlayerSuspend'][0])) {
                    $info['PlayerSuspend'] = [0 => $info['PlayerSuspend']];
                }

                foreach ($info['PlayerSuspend'] as $suspend)
                {
                    if($suspend['team_id'] == $match['home_id']){
                        $home_info[] = [
                            "match_id" => $match_id,
                            "team_type" => 0,
                            "desc" => "【伤停】{$suspend['Name2']}{$suspend['Reason']}",
                            "create_time" => time(),
                            "team_id" => $match['home_id'],
                            "type" => 1
                        ];
                    }else{
                        $away_info[] = [
                            "match_id" => $match_id,
                            "team_type" => 0,
                            "desc" => "【伤停】{$suspend['Name2']}{$suspend['Reason']}",
                            "create_time" => time(),
                            "team_id" => $match['away_id'],
                            "type" => 1
                        ];
                    }
                }
            }

            $info = array_merge($home_info, $away_info);

            MatchInfoModel::add($info);

        }
    }
}