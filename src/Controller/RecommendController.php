<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2018/5/20
 * Time: 16:33
 */
namespace Controller;


use FastD\Http\ServerRequest;
use Logic\RecommendMatchChoseLogic;
use Model\OptionModel;
use Model\RecommendModel;
use Model\TeamModel;
use Helper\FuntionHelper;
use Logic\RecommendLogic;

class RecommendController extends BaseController
{
    public function RecommendDetail(ServerRequest $request)
    {

        $validate = validator($request, [
            "rec_id" => "required",
        ]);

        $params = $validate->data();

        return $this->response(RecommendLogic::getInstance()->getRecommendDetail($params['rec_id']));
    }

    public function matchInfo(ServerRequest $request)
    {
        validator($request, [
            "odd_id" => "required"
        ]);

        $odd_id = $request->getParam("odd_id");
        return $this->response(RecommendLogic::getInstance()->matchInfo($odd_id));
    }


    public function addRecommend(ServerRequest $request)
    {
        $validate = validator($request, [
            "odd_id" => "required",
            "option_id" => "required",
            "rec_title" => "required",
            "rec_desc" => "required",
        ]);

        $params = $validate->data();

        $info_id = $request->getParam("info_id", null);

        $params['info_id'] = $info_id;

        return $this->response(RecommendLogic::getInstance()->addRecommend($params));
    }

    public function fetchRecommendList(ServerRequest $request)
    {
        $page = $request->getParam("page", 1);
        $size = $request->getParam("size", 20);
        $order = $request->getParam("order", null);
        $filter["win_rate_7"] = $request->getParam("win_rate_7", null);
        $filter["win_rate_30"] = $request->getParam("win_rate_30", null);
        $filter["win_rate"] = $request->getParam("win_rate", null);
        $filter["ticket"] = $request->getParam("ticket", null);
        $filter["league_id"] = $request->getParam("league_id", null);
        $filter["match_id"] = $request->getParam("match_id", null);

        return $this->response(RecommendLogic::getInstance()->fetchRecommendList($order,$filter,$page,$size));
    }

    public function filterLeagueAndMatch(ServerRequest $request)
    {
        $odd_type = $request->getParam("odd_type", 1);
        return $this->response(RecommendLogic::getInstance()->filterLeagueAndMatch($odd_type));
    }

}