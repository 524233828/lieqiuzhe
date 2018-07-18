<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/14
 * Time: 10:19
 */

namespace Controller\Admin;


use Logic\Admin\TopLineLogic;

class TopLineController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = TopLineLogic::getInstance();

        $this->add_valid = [
            "page_id" => "required|in:1,2,3,4,5,6,7,8",
            "status" => "integer",
            "url" => "null",
            "params" => "null",
            "sort" => "integer",
            "content" => "required"
        ];
    }

}