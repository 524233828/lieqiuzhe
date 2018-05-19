<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/18
 * Time: 10:40
 */

namespace Model;


class OddModel extends BaseModel
{

    public static $table = "odd";

    public static function getOddByMatchId($match_id, $columns = "*")
    {
        $where = ["match_id" => $match_id];

        return database()->get(self::$table, $columns, $where);
    }

    public static function fetchOddMatchList($columns = "*",$where = null)
    {
        return database()->select(
            self::$table,
            [
                "[>]".MatchModel::$table => [ "match_id" => "id" ]
            ],
            $columns,
            $where
        );
    }

    public static function countDateMatch($start_time, $end_time)
    {
        $sql = <<<SQL
SELECT
	count(*) as match_num,
	FROM_UNIXTIME(start_time, "%Y-%m-%d") as date
FROM
	`match`
LEFT JOIN odd ON `match`.id = odd.match_id
WHERE
	start_time >= $start_time
AND start_time < $end_time
AND odd.`status` = 1
GROUP BY
	FROM_UNIXTIME(start_time, "%Y-%m-%d");
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

    }

    public static function getOddLeague($start_time, $end_time)
    {
        $sql = <<<SQL
SELECT
	league_id as id,
	league.gb_short as league_name
FROM
	`match`
LEFT JOIN league on `match`.league_id=league.id
LEFT JOIN odd ON `match`.id = odd.match_id
WHERE
    odd.`status` = 1
AND `match`.start_time >= {$start_time}
AND `match`.start_time < {$end_time}
GROUP BY
	`match`.league_id
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}