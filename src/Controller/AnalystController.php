<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/9
 * Time: 19:05
 */

namespace Controller;

use FastD\Http\ServerRequest;
use Logic\AnalystLogic;
use Logic\MatchListLogic;

class AnalystController extends BaseController
{

    /**
     * 分析师详情
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function fetchAnalystInfo(ServerRequest $request)
    {
        $analyst_id = $request->getParam("analyst_id", 1);

        return $this->response(AnalystLogic::getInstance()->fetchAnalystInfo($analyst_id));
    }

    /**
     * 分析师相关推荐
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function fetchAnalystRecommendList(ServerRequest $request)
    {
        $analyst_id = $request->getParam("analyst_id", 1);
        $page = $request->getParam("page", 1);

        return $this->response(AnalystLogic::getInstance()->fetchAnalystMatchList($analyst_id, $page, 5));
    }
}
