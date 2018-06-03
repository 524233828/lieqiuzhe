<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2018/5/20
 * Time: 16:33
 */
namespace Controller;


use FastD\Http\ServerRequest;
use Logic\SearchLogic;

class SearchController extends BaseController
{
    public function index(ServerRequest $request)
    {

        return $this->response(SearchLogic::getInstance()->getHotKeywords());
    }

    public function matchInfo(ServerRequest $request)
    {
        validator($request, [
            "odd_id" => "required"
        ]);

        $odd_id = $request->getParam("odd_id");
        return $this->response(RecommendLogic::getInstance()->matchInfo($odd_id));
    }


    public function keywords(ServerRequest $request)
    {
        validator($request, [
            "keyword" => "required"
        ]);

        $keyword = $request->getParam("keyword");
        return $this->response(SearchLogic::getInstance()->searchByKeywords($keyword));
    }

}