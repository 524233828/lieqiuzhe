<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2018/5/20
 * Time: 16:33
 */
namespace Controller\Admin;;


use FastD\Http\ServerRequest;
use Logic\RecommendMatchChoseLogic;
use Model\OptionModel;
use Model\RecommendModel;
use Model\TeamModel;
use Helper\FuntionHelper;
use Logic\Admin\RecommendLogic;

class RecommendController extends AdminBaseController
{

    public function matchInfo(ServerRequest $request)
    {
        validator($request, [
            "odd_id" => "required",
            "analyst_id" => "required"
        ]);

        $odd_id = $request->getParam("odd_id");
        $uid = $request->getParam("analyst_id");
        return $this->response(RecommendLogic::getInstance()->matchInfo($odd_id, $uid));
    }


    public function addRecommend(ServerRequest $request)
    {
        $validate = validator($request, [
            "odd_id" => "required",
            "option_id" => "required",
            "analyst_id" => "required",
            "rec_title" => "required",
            "rec_desc" => "required",
        ]);

        $params = $validate->data();

        $info_id = $request->getParam("info_id", null);

        $params['info_id'] = $info_id;

        return $this->response(RecommendLogic::getInstance()->addAction($params));
    }

    public function RecommendList(ServerRequest $request)
    {
        $page = $request->getParam("page", 1);
        $size = $request->getParam("size", 20);

        return $this->response(RecommendLogic::getInstance()->listAction($page, $size));
    }
}