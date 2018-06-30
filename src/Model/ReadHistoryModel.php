<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 9:39
 */

namespace Model;

class ReadHistoryModel extends BaseModel
{
    public static $table = "rec_read_history";

    public static function getReadCountOneDayByUserId($userid, $date = '')
    {
        if($date == '') {
            $date = strtotime(date('Ymd'));
        }
        $where = [
            'user_id' => $userid,
            "read_time[>]" => $date
        ];
        return database()->count(
            self::$table."(m)",
            ["id"],
            $where
        );
    }

    public static function getReadCountByUserId($userid)
    {

        $where = [
            'user_id' => $userid,
        ];
        return database()->count(
            self::$table."(m)",
            ["id"],
            $where
        );
    }

    //第一次阅读记录
    public static function addReadRecord($userid, $rec_id)
    {
        $data = [
            'rec_id' => $rec_id,
            'user_id' => $userid,
            'read_time' => time(),
            'update_time' => time(),
            'read_num' => 0,
        ];
        return database()->insert(self::$table, $data);
    }

    //第一次阅读后更新记录
    public static function updateReadRecord($userid, $rec_id)
    {
        $where = [
            'user_id' => $userid,
            'rec_id' => $rec_id,
        ];
        $data = [
            'read_num[+]' => 1,
            'update_time' => time(),
        ];
        return database()->update(self::$table, $data, $where);
    }

    //查找阅读记录
    public static function findReadRecord($userid, $rec_id)
    {
        $where = [
            'user_id' => $userid,
            'rec_id' => $rec_id,
        ];

        return database()->get(self::$table, '*', $where);
    }

    public static function getReadHistoryByUserId($user_id, $start, $count = 5)
    {
        $match_table = MatchModel::$table;
        $analyst_table = AnalystInfoModel::$table;
        $odd_table = OddModel::$table;
        $recommend_table = RecommendModel::$table;
        $league_table = LeagueModel::$table;
        $team_table = TeamModel::$table;
        $user_table = UserModel::$table;
        $icon_table = IconsModel::$table;
        $columns = [
            'k.user_id as user_id',
            'u.nickname',
            'u.avatar',
            'u.ticket',
            'k.tag',
            'k.level',
            't.icon as level_icon',
            'GROUP_CONCAT(is_win SEPARATOR \'\') as win_str',
            'GROUP_CONCAT(result SEPARATOR \'\') as result_str',
            'z.id as rec_id',
            'z.create_time as rec_time',
            'z.title as rec_title',
            'z.`desc` as rec_desc',
            'k.`record` as record',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $sql = <<<SQL
SELECT {$column}
FROM `rec_read_history` as m
LEFT JOIN `{$recommend_table}` as z ON z.id = m.rec_id
LEFT JOIN `{$odd_table}` as g ON z.odd_id = g.id
LEFT JOIN `{$match_table}` as h ON g.match_id = h.id
LEFT JOIN `{$league_table}` as d ON d.id = h.league_id
LEFT JOIN `{$team_table}` as r ON h.home_id = r.id
LEFT JOIN `{$team_table}` as f ON h.away_id = f.id
LEFT JOIN `{$analyst_table}` as k ON k.id = z.analyst_id
LEFT JOIN `{$user_table}` as u ON u.id = k.user_id
LEFT JOIN `{$icon_table}` as t ON t.level = k.level
WHERE m.user_id = {$user_id} AND t.type = 1
LIMIT {$start}, {$count}
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}