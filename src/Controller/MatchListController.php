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

        $league_id = $request->getParam("league_id", null);
        $date = $request->getParam("date", null);

        return $this->response(MatchListLogic::getInstance()->fetchMatchList($type, $league_id, $date, $page));
    }

    /**
     * 收藏比赛
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function collectionMatch(ServerRequest $request)
    {
        $match_id = $request->getParam("match_id");
        $form_id = $request->getParam("form_id", null);

        return $this->response(MatchListLogic::getInstance()->collect($match_id, $form_id));
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


    /**
     * 获取联赛列表
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function fetchLeague(ServerRequest $request)
    {
        $type = $request->getParam("type", 0);
        $date = $request->getParam("date");
        return $this->response(MatchListLogic::getInstance()->fetchLeague($type, $date));
    }

}