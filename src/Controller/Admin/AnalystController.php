<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/12
 * Time: 17:01
 */

namespace Controller\Admin;


use Logic\Admin\AnalystLogic;

class AnalystController extends AdminBaseController
{
    public function __construct()
    {
        $this->logic = AnalystLogic::getInstance();

        $this->add_valid = [
            "nickname" => "null",
            "avatar" => "null",
            "ticket" => "null",
            "tag" => "required",
            "intro" => "required",
        ];
    }
}