<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/15
 * Time: 11:40
 */

namespace Controller\Admin;


use Logic\Admin\VideoLogic;

class VideoController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = VideoLogic::getInstance();

        $this->add_valid = [
            "img_url" => "required|url",
            "url" => "required|url",
            "title" => "required|string",
            "viewer" => "integer",
            "times" => "required|integer",
            "status" => "in:0,1",
        ];
    }

}