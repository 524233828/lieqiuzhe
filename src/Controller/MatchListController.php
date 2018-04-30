<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/27
 * Time: 10:27
 */

namespace Controller;


use FastD\Http\ServerRequest;

class MatchListController extends BaseController
{

    public function fetchMatchList(ServerRequest $request)
    {
        $request->getParam("type");
        $request->getParam("page", 1);

        return $this->response([]);
    }

}