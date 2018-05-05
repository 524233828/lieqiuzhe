<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/5
 * Time: 14:59
 */

namespace Service;


use Qiutan\Constant;
use Qiutan\Match;

class MatchChangeService
{

    public static function change()
    {
        Match::$redis = redis();

        if(Match::$redis->exists(Constant::MATCH_CHANGE_CACHE)){

            return true;
        }

        $res = Match::matchChange();
        var_dump($res);

        return false;
    }
}