<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/19
 * Time: 16:17
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\RecommendMatchChoseLogic;

class RecommendMatchChoseController extends BaseController
{

    public function matchList(ServerRequest $request)
    {
        validator($request,[
           "date" => "required|date"
        ]);

        $date = $request->getParam("date");
        $league_id = $request->getParam("league_id", null);
        $odd_type = $request->getParam("odd_type", 1);

        $page = $request->getParam("page", 1);
        $size = $request->getParam("size", 20);

        return $this->response(
            RecommendMatchChoseLogic::getInstance()->matchList($date, $league_id, $odd_type, $page, $size)
        );
    }
}