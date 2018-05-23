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

//        validator($request,[
//            "rec_id" => "required|integer"
//        ]);
        $rec_id = $request->getAttribute('rec_id', false);
        $res = RecommendModel::fetchOne($rec_id);

        $options = OptionModel::getOptionByOddId($res['odd_id'],['id','option','odds_rate']);
        $res['option'] = $options;
        $res['win_streak'] = FuntionHelper::continuityWin($res['record']);
        $res['hit_rate'] = FuntionHelper::winRate($res['record']);
        $res['rec_time'] =date('m/d H:i', $res['rec_time']);
        $res['is_read'] = 1;
        $res['extra'] = json_decode($res['extra'], true);
        unset($res['record']);
        unset($res['odd_id']);

        return $this->response($res);
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

}