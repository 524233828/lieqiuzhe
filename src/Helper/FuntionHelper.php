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
        if(!$res[0]) {
            return 0;
        }
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

    public static function changeBaifenbi($record)
    {
        return sprintf("%01.0f", $record*100).'%';
    }


    public static function resultComputer($result_str)
    {
        $str = str_replace(["0", "4"],["", ""],$result_str);

        $all = strlen($str);

        $win = substr_count($str,'1');

        $lose = substr_count($str, '3');

        return $all."发".$win."中".$lose."走";
    }

    public static function arrayUnion($arr, $key)
    {
        //建立一个目标数组
        $res = array();
        foreach ($arr as $value) {
            //查看有没有重复项
            if(isset($res[$value[$key]])){
                unset($value[$key]);  //有：销毁
            }else{
                $res[$value[$key]] = $value;
            }
        }
        return $res;
    }
}
