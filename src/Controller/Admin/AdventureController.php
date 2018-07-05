<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/5
 * Time: 22:11
 */

namespace Controller\Admin;

use Logic\Admin\AdventureLogic;

class AdventureController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = AdventureLogic::getInstance();

        $this->add_valid = [
            "page_id" => "required|in:1,2",
            "img_url" => "required|string"
        ];
    }

}