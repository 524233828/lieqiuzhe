<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/15
 * Time: 11:00
 */

namespace Controller\Admin;


use Logic\Admin\UserLogic;

class UserController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = UserLogic::getInstance();

        $this->add_valid = [];
    }

}