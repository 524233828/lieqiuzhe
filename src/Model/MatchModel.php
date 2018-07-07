<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 9:39
 */

namespace Model;

class MatchModel extends BaseModel
{
    public static $table = "match";
    public static function fetch($where = [], $columns = "*")
    {
        if(is_array($where))
        {
            return database()->select(
                self::$table."(m)",
                [
                    "[>]".TeamModel::$table."(h)" => ["m.home_id" => "id"],
                    "[>]".TeamModel::$table."(a)" => ["m.away_id" => "id"],
                    "[>]".LeagueModel::$table."(l)" => ["m.league_id" => "id"],
                    "[>]".WeatherModel::$table."(w)" => ["m.weather_id" => "id"],
                ],
                $columns,
                $where
            );
        }

        $team_table = TeamModel::$table;
        $league_table = LeagueModel::$table;
        $weather_table = WeatherModel::$table;
        $column = implode(",", $columns);
        $sql = <<<SQL
SELECT {$column}
FROM (
  SELECT * 
  FROM `match`
  WHERE {$where}
) as m
LEFT JOIN {$team_table} as h ON m.home_id = h.id
LEFT JOIN {$team_table} as a ON m.home_id = a.id
LEFT JOIN {$league_table} as l ON m.league_id = l.id
LEFT JOIN {$weather_table} as w ON m.weather_id = w.id
SQL;

        return database()->query($sql);

    }

    public static function detail($match_id)
    {
        $match_table = MatchModel::$table;
        $league_table = LeagueModel::$table;
        $team_table = TeamModel::$table;
        $weather_table = WeatherModel::$table;
        $columns = [
            'd.gb as league_name',
            "FROM_UNIXTIME(m.start_time,'%m/%d %H:%i') as match_time",
            'r.gb as home',
            'r.flag as home_flag',
            'f.gb as away',
            'f.flag as away_flag',
            'm.home_score as home_score',
            'm.away_score as away_score',
            't.`weather` as weather',
            't.`icon` as weather_icon',
            'm.`temperature` as temperature',
            'm.`weather_id` as weather_id',
            'm.`status` as status',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $sql = <<<SQL
SELECT {$column}
FROM (
  SELECT * 
  FROM `{$match_table}`
  WHERE id = {$match_id}
) as m
LEFT JOIN `{$league_table}` as d ON d.id = m.league_id
LEFT JOIN `{$team_table}` as r ON m.home_id = r.id
LEFT JOIN `{$team_table}` as f ON m.away_id = f.id
LEFT JOIN `{$weather_table}` as t ON m.weather_id = t.id
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

    public static function fetchByTeamId($id, $columns = "*")
    {
        $match_table = MatchModel::$table;
        $league_table = LeagueModel::$table;
        $team_table = TeamModel::$table;
        $weather_table = WeatherModel::$table;
        $columns = [
            'm.id as match_id',
            'd.gb as league_name',
            "FROM_UNIXTIME(m.start_time,'%m/%d %H:%i') as match_time",
            'r.gb as home',
            'f.gb as away',
            'm.status as status',
            'm.home_score as home_score',
            'm.away_score as away_score',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $time = time() - 24 * 3600 * 2;
        $etime = time() + 24 * 3600 * 20;
        $sql = <<<SQL
SELECT {$column}
FROM (
    SELECT * 
    FROM `{$match_table}`
    WHERE 
    ("home_id" = {$id} OR "away_id" = {$id}) 
    AND "start_time" > {$time}
    AND "start_time" < {$etime}
) as m
LEFT JOIN `{$league_table}` as d ON d.id = m.league_id
LEFT JOIN `{$team_table}` as r ON m.home_id = r.id
LEFT JOIN `{$team_table}` as f ON m.away_id = f.id
SQL;

        return database()->query($sql)->fetch(\PDO::FETCH_ASSOC);
    }
}