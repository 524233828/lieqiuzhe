<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/4/27
 * Time: 10:27
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\MatchDetailLogic;
use Logic\UserLogic;

class MatchDetailController extends BaseController
{

    /**
     * 获取比赛详情
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function fetchMatchDetail(ServerRequest $request)
    {
        $validate = validator($request, [
            "match_id" => "required",
        ]);
        $params = $validate->data();

        return $this->response(MatchDetailLogic::getInstance()->fetchMatchDetail($params['match_id']));
    }

    /**
     * 比赛情报
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function fetchMatchAdvices(ServerRequest $request)
    {
        $validate = validator($request, [
            "match_id" => "required",
        ]);
        $params = $validate->data();

        return $this->response(MatchDetailLogic::getInstance()->fetchMatchAdvices($params['match_id']));
    }

    /**
     * 比赛的推荐列表
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function fetchRecommendList(ServerRequest $request)
    {
        $validate = validator($request, [
            "match_id" => "required",
        ]);
        $params = $validate->data();
        $page = $request->getParam("page", 1);
        $size = $request->getParam("size", 5);

        return $this->response(MatchDetailLogic::getInstance()->fetchRecomendList($params['match_id'], $page, $size));
    }

    /**
     * 送球票
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function giveTicket(ServerRequest $request)
    {
        $validate = validator($request, [
            "user_id" => "required",
        ]);

        $params = $validate->data();

        return $this->response(UserLogic::getInstance()->usedTickets($params['user_id']));
    }

}