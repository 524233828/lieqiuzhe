<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 18:48
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\IndexLogic;

class IndexController extends BaseController
{
    public function userInfo(ServerRequest $request)
    {
        return $this->response(IndexLogic::getInstance()->userInfo());
    }

    public function banner(ServerRequest $request)
    {
        return $this->response(IndexLogic::getInstance()->banner());
    }

    public function topLine(ServerRequest $request)
    {
        return $this->response(IndexLogic::getInstance()->topLine());
    }
}