<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/29
 * Time: 20:43
 */

namespace Controller\Admin;


use Logic\Admin\IconLogic;

class IconController extends AdminBaseController
{
    public function __construct()
    {
        $this->logic = IconLogic::getInstance();

        $this->add_valid = [
            "num" => "required|integer",
            "intro" => "null",
        ];
    }

}