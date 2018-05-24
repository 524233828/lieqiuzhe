<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 9:39
 */

namespace Model;

class RecommendModel extends BaseModel
{
    public static $table = "recommend";
    public static function fetchOne($id, $columns = "*")
    {
        $match_table = MatchModel::$table;
        $analyst_table = AnalystInfoModel::$table;
        $odd_table = OddModel::$table;
        $option_table = OptionModel::$table;
        $user_table = UserModel::$table;
        $league_table = LeagueModel::$table;
        $team_table = TeamModel::$table;
        $icon_table = IconsModel::$table;
        $columns = [
            'a.id as analyst_id',
            'c.nickname',
            'c.avatar',
            'a.tag',
            'a.level',
            't.icon as level_icon',
//            'win_streak',
//            'hit_rate',
            'm.id as rec_id',
            'd.gb as league_name',
           "FROM_UNIXTIME(h.start_time,'%m/%d %H:%i') as match_time",
            'r.gb as home',
            'r.flag as home_flag',
            'f.gb as away',
            'f.flag as away_flag',
            'g.type as odd_type',
            'm.option_id as option_id',
            'g.extra as extra',
            'm.create_time as rec_time',
            'm.title as rec_title',
            'm.`desc` as rec_desc',
            'b.`odd_id` as odd_id',
            'm.`result` as result',
            'a.`record` as record',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $sql = <<<SQL
SELECT {$column}
FROM (
  SELECT * 
  FROM `recommend`
  WHERE id = {$id}
) as m
LEFT JOIN `{$odd_table}` as g ON m.odd_id = g.id
LEFT JOIN `{$match_table}` as h ON g.match_id = h.id
LEFT JOIN `{$analyst_table}` as a ON m.analyst_id = a.id
LEFT JOIN `{$option_table}` as b ON m.option_id = b.id
LEFT JOIN `{$user_table}` as c ON c.id = a.user_id
LEFT JOIN `{$league_table}` as d ON d.id = h.league_id
LEFT JOIN `{$team_table}` as r ON h.home_id = r.id
LEFT JOIN `{$team_table}` as f ON h.away_id = f.id
LEFT JOIN `{$icon_table}` as t ON t.level = a.level
WHERE t.type = 2
SQL;

        return database()->query($sql)->fetch(\PDO::FETCH_ASSOC);

    }

    public static function count($where)
    {
        return database()->count(
            self::$table."(m)",
            ["id"],
            $where
        );
    }

    public static function getRecommendByAnalystId($analyst_id, $start, $count = 5)
    {
        $match_table = MatchModel::$table;
        $odd_table = OddModel::$table;
        $league_table = LeagueModel::$table;
        $team_table = TeamModel::$table;
        $columns = [
            'm.id as rec_id',
            'd.gb as league_name',
            'r.gb as home',
            'f.gb as away',
            'm.title as rec_title',
            'm.`desc` as rec_desc',
            'm.`result` as result',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $sql = <<<SQL
SELECT {$column}
FROM `recommend` as m
LEFT JOIN {$odd_table} as g ON m.odd_id = g.id
LEFT JOIN {$match_table} as h ON g.match_id = h.id
LEFT JOIN {$league_table} as d ON d.id = h.league_id
LEFT JOIN {$team_table} as r ON h.home_id = r.id
LEFT JOIN {$team_table} as f ON h.away_id = f.id
WHERE m.analyst_id = {$analyst_id}
LIMIT {$start}, {$count}
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}