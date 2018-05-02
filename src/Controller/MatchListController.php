<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/27
 * Time: 10:27
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\MatchListLogic;

class MatchListController extends BaseController
{

    public function fetchMatchList(ServerRequest $request)
    {
        $type = $request->getParam("type");
        $page = $request->getParam("page", 1);

        return $this->response(MatchListLogic::getInstance()->fetchMatchList($type, $page));
    }

}