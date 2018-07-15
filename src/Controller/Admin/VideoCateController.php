<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/15
 * Time: 11:40
 */

namespace Controller\Admin;


use Logic\Admin\VideoCateLogic;

class VideoCateController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = VideoCateLogic::getInstance();

        $this->add_valid = [
            "cate" => "required|range_ch:1,4",
            "status" => "in:0,1",
        ];
    }

}