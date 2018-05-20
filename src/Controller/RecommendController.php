<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/20
 * Time: 15:29
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\RecommendLogic;

class RecommendController extends BaseController
{

    public function matchInfo(ServerRequest $request)
    {
        validator($request,[
            "odd_id" => "required"
        ]);

        $odd_id = $request->getParam("odd_id");
        return $this->response(RecommendLogic::getInstance()->matchInfo($odd_id));
    }


    public function addRecommend(ServerRequest $request)
    {

    }

}