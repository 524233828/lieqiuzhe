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
            'a.user_id as user_id',
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
            'h.`status` as status',
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
WHERE t.type = 1
SQL;

        return database()->query($sql)->fetch(\PDO::FETCH_ASSOC);

    }

    public static function countRecommend($where)
    {
        return database()->count(
            self::$table."(m)",
            ["id"],
            $where
        );
    }

    public static function getLastedRecommendByUserId($user_id)
    {
        $match_table = MatchModel::$table;
        $analyst_table = AnalystInfoModel::$table;
        $odd_table = OddModel::$table;
        $league_table = LeagueModel::$table;
        $team_table = TeamModel::$table;
        $columns = [
            'r.gb as home',
            'f.gb as away',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $sql = <<<SQL
SELECT {$column}
FROM `recommend` as m
LEFT JOIN `{$odd_table}` as g ON m.odd_id = g.id
LEFT JOIN `{$match_table}` as h ON g.match_id = h.id
LEFT JOIN `{$league_table}` as d ON d.id = h.league_id
LEFT JOIN `{$team_table}` as r ON h.home_id = r.id
LEFT JOIN `{$team_table}` as f ON h.away_id = f.id
LEFT JOIN `{$analyst_table}` as k ON k.id = m.analyst_id
WHERE k.user_id = {$user_id}
ORDER BY m.create_time
SQL;

        return database()->query($sql)->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getRecommendByUserId($user_id, $start, $count = 5)
    {
        $match_table = MatchModel::$table;
        $analyst_table = AnalystInfoModel::$table;
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
            'm.`option_id` as option_id',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $sql = <<<SQL
SELECT {$column}
FROM `recommend` as m
LEFT JOIN `{$odd_table}` as g ON m.odd_id = g.id
LEFT JOIN `{$match_table}` as h ON g.match_id = h.id
LEFT JOIN `{$league_table}` as d ON d.id = h.league_id
LEFT JOIN `{$team_table}` as r ON h.home_id = r.id
LEFT JOIN `{$team_table}` as f ON h.away_id = f.id
LEFT JOIN `{$analyst_table}` as k ON k.id = m.analyst_id
WHERE k.user_id = {$user_id}
LIMIT {$start}, {$count}
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getRecommendByMatchId($match_id, $start, $count = 5)
    {
        $match_table = MatchModel::$table;
        $analyst_table = AnalystInfoModel::$table;
        $odd_table = OddModel::$table;
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
            'm.id as rec_id',
            'm.create_time as rec_time',
            'm.title as rec_title',
            'm.`desc` as rec_desc',
            'k.`record` as record',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $sql = <<<SQL
SELECT {$column}
FROM `recommend` as m
LEFT JOIN `{$odd_table}` as g ON m.odd_id = g.id
LEFT JOIN `{$match_table}` as h ON g.match_id = h.id
LEFT JOIN `{$league_table}` as d ON d.id = h.league_id
LEFT JOIN `{$team_table}` as r ON h.home_id = r.id
LEFT JOIN `{$team_table}` as f ON h.away_id = f.id
LEFT JOIN `{$analyst_table}` as k ON k.id = m.analyst_id
LEFT JOIN `{$user_table}` as u ON u.id = k.user_id
LEFT JOIN `{$icon_table}` as t ON t.level = k.level
WHERE h.id = {$match_id} AND t.type = 1
LIMIT {$start}, {$count}
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }


    public static function countRecommendByMatchId($match_id)
    {
        return database()->count(
            self::$table."(m)",
            [
                "[>]".OddModel::$table."(h)" => ["m.odd_id" => "id"],
                "[>]".MatchModel::$table."(a)" => ["h.match_id" => "id"],
                "[>]".AnalystInfoModel::$table."(b)" => ["m.analyst_id" => "id"],
            ],
            ['m.id'],
            [
                'a.id' => $match_id
            ]
        );
    }

    /**
     * @param $first_row
     * @param $size
     * @param string $order_by  hit_rate|gifts
     * @return array
     */
    public static function Rank($first_row, $size, $order_by = "hit_rate", $where = "1=1")
    {

        $columns = [
            UserModel::$table.".avatar",
            UserModel::$table.".nickname",
            AnalystInfoModel::$table.".ticket as gifts",
            AnalystInfoModel::$table.".tag",
            AnalystInfoModel::$table.".record",
            AnalystInfoModel::$table.".level",
        ];

        $columns = implode(",", $columns);
        $sql = <<<SQL
SELECT 
    r.id as recommend_id, 
    r.analyst_id, 
    AVG(is_win) as hit_rate, 
    GROUP_CONCAT(is_win SEPARATOR '') as win_str, 
    GROUP_CONCAT(result SEPARATOR '') as result_str, 
    h.gb as home, 
    a.gb as away, 
    l.gb_short as league_name, 
    {$columns}
FROM recommend r
LEFT JOIN `user` ON `user`.id=r.analyst_id
LEFT JOIN `analyst_info` ON `analyst_info`.user_id=r.analyst_id
LEFT JOIN `odd` ON odd.id=r.odd_id
LEFT JOIN `match` ON odd.match_id=`match`.id
LEFT JOIN `team` as h ON `match`.home_id = h.id
LEFT JOIN `team` as a ON `match`.away_id = a.id
LEFT JOIN `league` as l ON `match`.league_id = l.id
WHERE {$where}
GROUP BY r.analyst_id
ORDER BY {$order_by} DESC, r.create_time DESC
LIMIT {$first_row}, {$size}
SQL;

        return database()->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }


    public static function getUserId($rec_id)
    {

        $columns = [
            UserModel::$table.".id",
        ];

        $columns = implode(",", $columns);
        $sql = <<<SQL
SELECT 
    $columns
FROM recommend r
LEFT JOIN `analyst_info` ON `analyst_info`.id = r.analyst_id
LEFT JOIN `user` ON `user`.id = analyst_info.user_id
WHERE r.id = {$rec_id}
SQL;

        return database()->pdo->query($sql)->fetch(\PDO::FETCH_ASSOC);
    }


    public static function RecommendList($where = null ,$where2 = null, $order = null, $having = null, $limit = "LIMIT 1,20")
    {

        $orderby = "";
        if(!empty($order))
        {
            $orderby = "ORDER BY {$order}";
        }

        $havings = "";
        if(!empty($having))
        {
            $havings = "HAVING $having";
        }

        $out_where = "";
        if(!empty($where))
        {
            $out_where = "WHERE {$where}";
        }

        $in_where = "";
        if(!empty($where2))
        {
            $in_where = "WHERE {$where2}";
        }

        $sql = <<<SQL
SELECT
	u.nickname,
	u.avatar,
	a.tag,
	l.gb_short as league_name,
	l.id as league_id,
	home.gb as home,
	away.gb as away,
	a.intro,
	a.ticket,
	hit_rate.hit_rate,
	hit_rate.win_str
FROM
	recommend r
LEFT JOIN odd o ON r.odd_id = o.id
LEFT JOIN `match` m ON o.match_id = m.id
LEFT JOIN league l ON l.id = m.league_id
LEFT JOIN team home ON m.home_id = home.id
LEFT JOIN team away ON m.away_id = away.id
LEFT JOIN `user` u ON r.analyst_id = u.id
LEFT JOIN `analyst_info` a ON a.user_id = u.id
LEFT JOIN (
	SELECT
		analyst_id,
		AVG(is_win) AS hit_rate,
		GROUP_CONCAT(is_win SEPARATOR '') AS win_str
	FROM
		recommend
	{$in_where}
	GROUP BY
		analyst_id
) hit_rate ON hit_rate.analyst_id = a.id
{$out_where}
{$havings}
{$orderby}
{$limit}
SQL;

        return database()->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }


    public static function countRecommendList($where = null ,$where2 = null, $order = null, $having = null, $limit = "LIMIT 1,20")
    {

        $orderby = "";
        if(!empty($order))
        {
            $orderby = "ORDER BY {$order}";
        }

        $havings = "";
        if(!empty($having))
        {
            $havings = "HAVING $having";
        }

        $out_where = "";
        if(!empty($where))
        {
            $out_where = "WHERE {$where}";
        }

        $in_where = "";
        if(!empty($where2))
        {
            $in_where = "WHERE {$where2}";
        }

        $sql = <<<SQL
SELECT
	count(*) as num
FROM
	recommend r
LEFT JOIN odd o ON r.odd_id = o.id
LEFT JOIN `match` m ON o.match_id = m.id
LEFT JOIN league l ON l.id = m.league_id
LEFT JOIN team home ON m.home_id = home.id
LEFT JOIN team away ON m.away_id = away.id
LEFT JOIN `user` u ON r.analyst_id = u.id
LEFT JOIN `analyst_info` a ON a.user_id = u.id
LEFT JOIN (
	SELECT
		analyst_id,
		AVG(is_win) AS hit_rate,
		GROUP_CONCAT(is_win SEPARATOR '') AS win_str
	FROM
		recommend
	{$in_where}
	GROUP BY
		analyst_id
) hit_rate ON hit_rate.analyst_id = a.id
{$out_where}
{$havings}
SQL;

        $result =  database()->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0]['num'];
    }



}