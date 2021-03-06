<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 9:51
 */

namespace Model;


use Logic\UserLogic;

class AnalystInfoModel extends BaseModel
{
    const ANALYST_TABLE = "analyst_info";

    public static $table = "analyst_info";

    public static function getAnalystDetailByUserId($id)
    {
        $analyst_table = AnalystInfoModel::$table;
        $user_table = UserModel::$table;
        $recommend_table = RecommendModel::$table;
        $icon_table = IconsModel::$table;
        $columns = [
            'm.user_id as user_id',
            'm.id as analyst_id',
            'c.nickname as nickname',
            'c.avatar as avatar',
            'm.ticket as ticket',
            'm.tag as tag',
            'm.intro as intro',
            'AVG(is_win) as hit_rate',
            'GROUP_CONCAT(d.is_win SEPARATOR \'\') as win_str',
            'GROUP_CONCAT(d.result SEPARATOR \'\') as result_str',
            'm.level as level',
            't.icon as level_icon',
            'm.record as record',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $sql = <<<SQL
SELECT {$column}
FROM (
  SELECT * 
  FROM `{$analyst_table}`
  WHERE id = {$id}
) as m
LEFT JOIN `{$user_table}` as c ON c.id = m.user_id
LEFT JOIN `{$recommend_table}` as d ON d.analyst_id = m.id
LEFT JOIN `{$icon_table}` as t ON t.level = m.level
WHERE t.type = 1
SQL;

        return database()->query($sql)->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getInfoByUserId($user_id, $columns = "*")
    {
        return database()->get(self::$table, $columns, ["user_id"=>$user_id]);
    }

    public static function getAnalystRecordById($id, $columns = "*")
    {
        return database()->get(self::$table, $columns, ["id"=>$id]);
    }

    public static function fetchTicketRank($first_row, $size)
    {
        return database()->select(
            UserModel::$table,
            [
                "[>]".self::$table => ["id" => "user_id"],
                "[>]".RecommendModel::$table => ["id" => "analyst_id"],
                "[>]".OddModel::$table => [RecommendModel::$table.'.odd_id' => "id"],
                "[>]".MatchModel::$table => [OddModel::$table.'.match_id' => "id"],
                "[>]".TeamModel::$table."(h)" => [MatchModel::$table.'.home_id' => "id"],
                "[>]".TeamModel::$table."(a)" => [MatchModel::$table.'.away_id' => "id"],
                "[>]".LeagueModel::$table => [MatchModel::$table.'.league_id' => "id"],
            ],
            [
                UserModel::$table.".id(analyst_id)",
                UserModel::$table.".avatar",
                UserModel::$table.".nickname",
                self::$table.".ticket(gifts)",
                self::$table.".tag",
                self::$table.".record",
                self::$table.".level",
                RecommendModel::$table.".id(recommend_id)",
                "h.gb(home)",
                "a.gb(away)",
                LeagueModel::$table.".gb_short(league_name)",
            ],
            [
                UserModel::$table.".user_type" => 1,

                "ORDER" => [ self::$table.".ticket" => "DESC" ],

                "LIMIT" => [$first_row, $size]
            ]
        );
    }

    public static function fetchByKeyWords($keyword)
    {
        $analyst_table = AnalystInfoModel::$table;
        $user_table = UserModel::$table;
        $recommend_table = RecommendModel::$table;
        $icon_table = IconsModel::$table;

        $sql = <<<SQL
SELECT 
            m.user_id as user_id,
            m.id as analyst_id,
            c.nickname as nickname,
            c.avatar as avatar,
            c.ticket as ticket,
            AVG(is_win) as hit_rate,
            GROUP_CONCAT(d.is_win SEPARATOR '') as win_str,
            GROUP_CONCAT(d.result SEPARATOR '') as result_str,
            m.tag as tag,
            m.level as level,
            t.icon as level_icon
FROM `{$analyst_table}` as m
LEFT JOIN `{$user_table}` as c ON c.id = m.user_id
LEFT JOIN `{$recommend_table}` as d ON d.analyst_id = m.id
LEFT JOIN `{$icon_table}` as t ON t.level = m.level
WHERE t.type = 1
AND c.nickname LIKE '%{$keyword}%'
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }


    public static function getFollowsByUserId($user_id)
    {
        $fans_table = FansModel::$table;
        $user_table = UserModel::$table;
        $columns = [
            'c.id as user_id',
            'c.id as analyst_id',
            'c.nickname as nickname',
            'c.avatar as avatar',
        ];
        $column = is_array($columns) ? implode(",", $columns) : $columns;
        $sql = <<<SQL
SELECT {$column}
FROM (
  SELECT * 
  FROM `{$fans_table}`
  WHERE user_id = {$user_id}
) as m
LEFT JOIN `{$user_table}` as c ON c.id = m.analyst_id
SQL;

        return database()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
