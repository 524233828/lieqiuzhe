<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/14
 * Time: 9:56
 */

namespace Controller\Admin;


use Logic\Admin\SystemNoticeLogic;

class SystemNoticeController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = SystemNoticeLogic::getInstance();

        $this->add_valid = [
            "content" => "required"
        ];
    }

}