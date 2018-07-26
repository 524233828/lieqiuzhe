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

class UserLevelController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = AdventureLogic::getInstance();

        $this->add_valid = [
            "price" => "required|url",
        ];
    }

}