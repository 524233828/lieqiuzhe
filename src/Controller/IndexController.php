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

    public function adventure(ServerRequest $request)
    {
        return $this->response(IndexLogic::getInstance()->adventure());
    }

    public function ticketRank(ServerRequest $request)
    {
        $page = $request->getParam("page", 1);
        $size = $request->getParam("size", 20);
        return $this->response(IndexLogic::getInstance()->ticketRank($page, $size));
    }

    public function hitRateRank(ServerRequest $request)
    {
        $page = $request->getParam("page", 1);
        $size = $request->getParam("size", 20);
        $date = $request->getParam("date", null);
        return $this->response(IndexLogic::getInstance()->hitRateRank($page, $size, $date));
    }

    public function recommend(ServerRequest $request)
    {
        return $this->response(IndexLogic::getInstance()->recommend());
    }
}