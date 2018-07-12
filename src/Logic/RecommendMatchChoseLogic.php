<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/19
 * Time: 14:24
 */

namespace Logic;


use Model\LeagueModel;
use Model\MatchModel;
use Model\OddModel;
use Service\Pager;

class RecommendMatchChoseLogic extends BaseLogic
{

    public function matchList($date, $league_id = null, $odd_type = 1, $page = 1, $size = 20)
    {

        //TODO: 假数据
        $json = "{\"date_list\":[],\"league_list\":[{\"id\":4,\"league_name\":\"巴西甲\"},{\"id\":21,\"league_name\":\"美职业\"},{\"id\":64,\"league_name\":\"挪威杯\"},{\"id\":73,\"league_name\":\"瑞典杯\"},{\"id\":133,\"league_name\":\"克亚甲\"},{\"id\":142,\"league_name\":\"意丙1\"},{\"id\":208,\"league_name\":\"斯亚杯\"},{\"id\":222,\"league_name\":\"土伦杯\"},{\"id\":240,\"league_name\":\"乌拉甲\"},{\"id\":242,\"league_name\":\"秘鲁甲\"},{\"id\":246,\"league_name\":\"冰岛杯\"},{\"id\":250,\"league_name\":\"哥伦甲\"},{\"id\":317,\"league_name\":\"马来甲\"},{\"id\":332,\"league_name\":\"阿曼联\"},{\"id\":354,\"league_name\":\"巴拉甲\"},{\"id\":391,\"league_name\":\"委內超\"},{\"id\":448,\"league_name\":\"美乙\"},{\"id\":466,\"league_name\":\"哈萨超\"},{\"id\":475,\"league_name\":\"芬女超\"},{\"id\":508,\"league_name\":\"女挪威杯\"},{\"id\":593,\"league_name\":\"玻利甲\"},{\"id\":596,\"league_name\":\"厄瓜甲\"},{\"id\":639,\"league_name\":\"黑山杯\"},{\"id\":677,\"league_name\":\"意青联\"},{\"id\":802,\"league_name\":\"美女职\"},{\"id\":816,\"league_name\":\"丹麦U19\"},{\"id\":846,\"league_name\":\"瑞U19\"},{\"id\":1047,\"league_name\":\"冰女超\"},{\"id\":1061,\"league_name\":\"塞尔甲\"},{\"id\":1092,\"league_name\":\"苏女超\"},{\"id\":1122,\"league_name\":\"印尼超\"},{\"id\":1212,\"league_name\":\"南运U17\"},{\"id\":1222,\"league_name\":\"突尼乙\"},{\"id\":1281,\"league_name\":\"意室內足\"},{\"id\":1311,\"league_name\":\"南非洲杯\"},{\"id\":1366,\"league_name\":\"国际友谊\"},{\"id\":1389,\"league_name\":\"巴圣青联\"},{\"id\":1391,\"league_name\":\"巴圣女联\"},{\"id\":1417,\"league_name\":\"芬乙\"},{\"id\":1419,\"league_name\":\"葡青U17\"},{\"id\":1457,\"league_name\":\"美超\"},{\"id\":1461,\"league_name\":\"阿丙曼特\"},{\"id\":1462,\"league_name\":\"阿丁曼特\"},{\"id\":1477,\"league_name\":\"美女超\"},{\"id\":1501,\"league_name\":\"委內乙\"},{\"id\":1534,\"league_name\":\"波兰丁\"},{\"id\":1559,\"league_name\":\"德萨州联\"},{\"id\":1620,\"league_name\":\"爱尔高联\"},{\"id\":1701,\"league_name\":\"波女杯\"},{\"id\":1763,\"league_name\":\"巴女甲\"}],\"match_list\":[{\"match_id\":1546706,\"league_name\":\"克亚甲\",\"league_color\":\"#CC6600\",\"match_time\":1527699600,\"home\":\"依斯特拉\",\"away\":\"瓦拉日丁\",\"odd_id\":2044},{\"match_id\":1547040,\"league_name\":\"德萨州联\",\"league_color\":\"#7D4365\",\"match_time\":1527701400,\"home\":\"FC条顿05\",\"away\":\"基尔B队\",\"odd_id\":2130},{\"match_id\":1532390,\"league_name\":\"阿曼联\",\"league_color\":\"#88DD00\",\"match_time\":1527703200,\"home\":\"萨汉姆\",\"away\":\"沙巴柏OMA\",\"odd_id\":2159},{\"match_id\":1546884,\"league_name\":\"意丙1\",\"league_color\":\"#A4DFFF\",\"match_time\":1527705000,\"home\":\"科森扎\",\"away\":\"萨比尼迪特斯\",\"odd_id\":1889},{\"match_id\":1546886,\"league_name\":\"意丙1\",\"league_color\":\"#A4DFFF\",\"match_time\":1527705000,\"home\":\"维特比斯\",\"away\":\"苏迪路\",\"odd_id\":1887},{\"match_id\":1546885,\"league_name\":\"意丙1\",\"league_color\":\"#A4DFFF\",\"match_time\":1527705000,\"home\":\"雷真亚拿\",\"away\":\"若布斯恩纳\",\"odd_id\":1890},{\"match_id\":1546697,\"league_name\":\"冰岛杯\",\"league_color\":\"#CC68FF\",\"match_time\":1527707700,\"home\":\"斯塔尔南\",\"away\":\"特罗图尔\",\"odd_id\":2017},{\"match_id\":1546918,\"league_name\":\"委內超\",\"league_color\":\"#2A7F10\",\"match_time\":1527714000,\"home\":\"卡拉波波\",\"away\":\"阿拉瓜\",\"odd_id\":2126},{\"match_id\":1509849,\"league_name\":\"巴西甲\",\"league_color\":\"#996600\",\"match_time\":1527719400,\"home\":\"累西腓体育\",\"away\":\"米内罗竞技\",\"odd_id\":1906},{\"match_id\":1518548,\"league_name\":\"美乙\",\"league_color\":\"#660000\",\"match_time\":1527721200,\"home\":\"印地十一\",\"away\":\"查尔斯顿电池\",\"odd_id\":2140},{\"match_id\":1518544,\"league_name\":\"美乙\",\"league_color\":\"#660000\",\"match_time\":1527721200,\"home\":\"渥太华复仇者\",\"away\":\"多伦多FCB队\",\"odd_id\":2141},{\"match_id\":1542023,\"league_name\":\"乌拉甲\",\"league_color\":\"#CC6666\",\"match_time\":1527721200,\"home\":\"乌拉圭民族\",\"away\":\"蒙特维多竞技\",\"odd_id\":2133},{\"match_id\":1545742,\"league_name\":\"南运U17\",\"league_color\":\"#003308\",\"match_time\":1527721200,\"home\":\"玻利维亚U19\",\"away\":\"智利U19\",\"odd_id\":2209},{\"match_id\":1488506,\"league_name\":\"巴拉甲\",\"league_color\":\"#a00800\",\"match_time\":1527722100,\"home\":\"亚松森奥林匹亚\",\"away\":\"自由队\",\"odd_id\":2071},{\"match_id\":1500153,\"league_name\":\"美职业\",\"league_color\":\"#660033\",\"match_time\":1527723600,\"home\":\"费城联合\",\"away\":\"芝加哥火焰\",\"odd_id\":1875},{\"match_id\":1509847,\"league_name\":\"巴西甲\",\"league_color\":\"#996600\",\"match_time\":1527724800,\"home\":\"圣保罗\",\"away\":\"博塔弗戈\",\"odd_id\":1911},{\"match_id\":1547170,\"league_name\":\"玻利甲\",\"league_color\":\"#B5A150\",\"match_time\":1527724800,\"home\":\"史庄格斯\",\"away\":\"威斯特曼\",\"odd_id\":2137},{\"match_id\":1515366,\"league_name\":\"哈萨超\",\"league_color\":\"#8C8CFF\",\"match_time\":1527771600,\"home\":\"阿拉木图凯拉特\",\"away\":\"阿克托比\",\"odd_id\":2363},{\"match_id\":1515365,\"league_name\":\"哈萨超\",\"league_color\":\"#8C8CFF\",\"match_time\":1527771600,\"home\":\"伊特什\",\"away\":\"安察利克\",\"odd_id\":2367},{\"match_id\":1543809,\"league_name\":\"国际友谊\",\"league_color\":\"#4666bb\",\"match_time\":1527775200,\"home\":\"罗马尼亚\",\"away\":\"智利\",\"odd_id\":1749},{\"match_id\":1498639,\"league_name\":\"马来甲\",\"league_color\":\"#FF874D\",\"match_time\":1527775200,\"home\":\"皇家警察\",\"away\":\"马印足协体育理事会\",\"odd_id\":2457},{\"match_id\":1536439,\"league_name\":\"芬乙\",\"league_color\":\"#66CCFF\",\"match_time\":1527782340,\"home\":\"PEPO拉宾兰塔\",\"away\":\"格尼斯坦\",\"odd_id\":2489}],\"meta\":{\"total\":22,\"count\":20,\"per_page\":20,\"current_page\":1,\"total_pages\":2,\"prev_page\":1,\"next_page\":2}}";
        return json_decode($json, true);
        //计算最近七天每天有亚盘的比赛数

        //今日
        $today_time = time();

        //七天后
        $week_time = strtotime(date("Ymd", $today_time + 7 * 86400));

        $date_list = OddModel::countDateMatch($today_time, $week_time);

        $first_index =  $size * ($page-1);

        $start_time = strtotime($date);

        $end_time = strtotime($date ."+1 day");

        $league_list = OddModel::getOddLeague($start_time, $end_time);

        $where = [
            MatchModel::$table.".start_time[>=]" => $start_time,
            MatchModel::$table.".start_time[<]" => $end_time,
            OddModel::$table.".type" => $odd_type
        ];
        if(!empty($league_id))
        {
            $where[MatchModel::$table.".league_id"] = $league_id;
        }

        $res = OddModel::fetchOddMatchList([OddModel::$table.".match_id(mid)", OddModel::$table.".id"],$where);

        $match_ids = [];
        $match_index = [];
        foreach ($res as $v){
            $match_ids[] = $v['mid'];
            $match_index[$v['mid']] = $v['id'];
        }

        $where = [];
        $where["m.status"] = [0];
        $where["m.id"] = $match_ids;
        $where["ORDER"] = ["start_time" => "ASC"];
        $count = MatchModel::countMatch($where);
        $where["LIMIT"] = [$first_index, $size];
        $list = MatchModel::fetchMatch(
            $where,
            [
                "m.id(match_id)",
                "l.gb_short(league_name)",
                "l.color(league_color)",
                "m.start_time(match_time)",
                "h.gb(home)",
                "a.gb(away)",
            ]
        );

        foreach ($list as $k => $v)
        {
            $list[$k]['odd_id'] = $match_index[$v['match_id']];
        }

        $page = new Pager($page, $size);

        return [
            "date_list" => $date_list,
            "league_list" => $league_list,
            "match_list" => $list,
            "meta" => $page->getPager($count)
        ];

    }
}