<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/12
 * Time: 23:37
 */

namespace Controller\Admin;


use Logic\Admin\MatchLogic;

class MatchController extends AdminBaseController
{
    public function __construct()
    {
        $this->logic = MatchLogic::getInstance();

        $this->add_valid = [

        ];
    }
}