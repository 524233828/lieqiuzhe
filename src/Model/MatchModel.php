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
    const MATCH_TABLE = "match";
    public static function fetch($where = [], $columns = "*")
    {
        if(is_array($where))
        {
            return database()->select(
                self::MATCH_TABLE."(m)",
                [
                    "[>]".TeamModel::TEAM_TABLE."(h)" => ["m.home_id" => "id"],
                    "[>]".TeamModel::TEAM_TABLE."(a)" => ["m.away_id" => "id"],
                    "[>]".LeagueModel::LEAGUE_TABLE."(l)" => ["m.league_id" => "id"],
                    "[>]".WeatherModel::WEATHER_TABLE."(w)" => ["m.weather_id" => "id"],
                ],
                $columns,
                $where
            );
        }

        $team_table = TeamModel::TEAM_TABLE;
        $league_table = LeagueModel::LEAGUE_TABLE;
        $weather_table = WeatherModel::WEATHER_TABLE;
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

    public static function count($where)
    {
        return database()->count(
            self::MATCH_TABLE."(m)",
            $where
        );
    }
}