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
        $validate = validator($request, [
            "user_id" => "required",
        ]);
        $params = $validate->data();

        return $this->response(AnalystLogic::getInstance()->fetchAnalystInfoByUserId($params['user_id']));
    }

    /**
     * 分析师相关推荐
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function fetchAnalystRecommendList(ServerRequest $request)
    {
        $validate = validator($request, [
            "user_id" => "required",
        ]);

        $params = $validate->data();

        $page = $request->getParam("page", 1);

        return $this->response(AnalystLogic::getInstance()->fetchAnalystMatchList($params['user_id'], $page, 5));
    }



    /**
     * 分析师关注
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function analystFollow(ServerRequest $request)
    {
        $validate = validator($request, [
            "user_id" => "required",
        ]);
        $params = $validate->data();

        if($result = AnalystLogic::getInstance()->followAnalyst($params))
        {
            return $this->response($result, false, "关注成功");
        }


    }


    /**
     * 分析师取关
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function analystUnfollow(ServerRequest $request)
    {
        $validate = validator($request, [
            "user_id" => "required",
        ]);

        $params = $validate->data();

        if($result = AnalystLogic::getInstance()->unfollowAnalyst($params)){
            return $this->response($result, false, "取消关注成功");
        }


    }
}
