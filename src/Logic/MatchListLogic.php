<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/27
 * Time: 10:32
 */

namespace Logic;


class MatchListLogic extends BaseLogic
{
    public function fetchMatchList($type, $page = 1, $size = 20)
    {
        $first_index =  $size * ($page-1);
    }
}