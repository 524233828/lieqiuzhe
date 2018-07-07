<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/7
 * Time: 19:50
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\SystemNoticeLogic;

class SystemNoticeController extends BaseController
{

    public function fetchAction(ServerRequest $request)
    {
        $page = $request->getParam("page", 1);

        $size = $request->getParam("size", 20);

        return $this->response(SystemNoticeLogic::getInstance()->fetchAction($page, $size));
    }
}