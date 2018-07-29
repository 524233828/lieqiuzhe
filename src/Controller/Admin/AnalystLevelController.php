<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/5
 * Time: 22:11
 */

namespace Controller\Admin;

use Logic\Admin\AnalystLevelLogic;

class AnalystLevelController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = AnalystLevelLogic::getInstance();

        $this->add_valid = [
            "price" => "required",
            "intro" => "null",
            "recommend_num" => "required|integer",
        ];
    }

}