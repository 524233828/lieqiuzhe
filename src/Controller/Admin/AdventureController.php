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

class AdventureController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = AdventureLogic::getInstance();

        $this->add_valid = [
            "page_id" => "required|in:1,2,3,4,5,6,7,8",
            "img_url" => "required|url",
            "status" => "integer",
            "url" => "",
            "params" => "",
            "sort" => "integer",
        ];
    }

    public function fetchPage(ServerRequest $request)
    {
        return $this->response(AdventureLogic::getInstance()->fetchPage());
    }

}