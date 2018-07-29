<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/5
 * Time: 22:11
 */

namespace Controller\Admin;

use FastD\Http\ServerRequest;
use Logic\Admin\AdventureLogic;
use Logic\Admin\UserLevelLogic;

class UserLevelController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = UserLevelLogic::getInstance();

        $this->add_valid = [
            "price" => "required",
            "intro" => "null",
//            "recommend_num" => "required|integer",
        ];
    }

}