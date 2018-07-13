<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/13
 * Time: 18:44
 */

namespace Controller\Admin;


use Logic\Admin\OrderLogic;

class OrderController extends AdminBaseController
{
    public function __construct()
    {
        $this->logic = OrderLogic::getInstance();

        $this->add_valid = [

        ];
    }
}