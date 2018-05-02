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

    /**
     * 获取比赛列表
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function fetchMatchList(ServerRequest $request)
    {
        $type = $request->getParam("type");
        $page = $request->getParam("page", 1);

        return $this->response(MatchListLogic::getInstance()->fetchMatchList($type, $page));
    }

    /**
     * 收藏比赛
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function collectionMatch(ServerRequest $request)
    {
        $match_id = $request->getParam("match_id");

        return $this->response(MatchListLogic::getInstance()->collect($match_id));
    }

    /**
     * 取消比赛收藏
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function collectionMatchCancel(ServerRequest $request)
    {
        $match_id = $request->getParam("match_id");

        return $this->response(MatchListLogic::getInstance()->collectCancel($match_id));
    }

}