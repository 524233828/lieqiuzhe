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
        return round($win/$all,3);
    }
}
