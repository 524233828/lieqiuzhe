<?php
namespace Helper;

/**
 * Funtion 公共计算函数
 * Class FuntionHelper
 * @package Helper
 */
class FuntionHelper
{

    /**
     * 计算最长连胜
     */
    public static function continuityWin($record)
    {
        preg_match_all('/([1])\1{1,}/',$record,$res);
        $count = [];
        foreach($res[0] as $v){
            $count[] = strlen($v);
        }
        return max($count);
    }

    /**
     * 计算胜率
     */
    public static function winRate($record)
    {

        $win = substr_count($record,'1');
        $all = strlen($record);
        return sprintf("%01.2f", $win/$all*100).'%';
    }


    public static function resultComputer($result_str)
    {
        $str = str_replace(["0", "4"],["", ""],$result_str);

        $all = strlen($str);

        $win = substr_count($str,'1');

        $lose = substr_count($str, '3');

        return $all."发".$win."中".$lose."走";
    }
}
