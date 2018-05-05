<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/5
 * Time: 13:25
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\MatchCollectionLogic;
use Model\MatchCollectionModel;

class MatchCollectionController extends BaseController
{

    /**
     * 获取收藏列表
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function fetchAction(ServerRequest $request)
    {

        $page = $request->getParam("page", 1);
        return $this->response(MatchCollectionLogic::getInstance()->fetch($page));
    }
}