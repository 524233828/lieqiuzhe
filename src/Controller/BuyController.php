<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/26
 * Time: 22:23
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\BuyLogic;

class BuyController extends BaseController
{

    public function userLevel(ServerRequest $request){

        validator($request, [
            "level" => "required|in:1,2,3,4,5",
            "month" => "required|in:1,3,12",
            "pay_type" => "required|in:wechat,alipay"
        ]);

        $level = $request->getParam("level");
        $month = $request->getParam("month");
        $pay_type = $request->getParam("pay_type");

        return $this->response(BuyLogic::getInstance()->userLevel($level, $month, $pay_type));
    }

    public function analystLevel(ServerRequest $request){

        validator($request, [
            "level" => "required|in:1,2,3,4,5",
            "month" => "required|in:1,3,12",
            "pay_type" => "required|in:wechat,alipay"
        ]);

        $level = $request->getParam("level");
        $month = $request->getParam("month");
        $pay_type = $request->getParam("pay_type");

        return $this->response(BuyLogic::getInstance()->analystLevel($level, $month, $pay_type));
    }

    public function coin(ServerRequest $request)
    {
        validator($request, [
            "num" => "required|integer",
            "pay_type" => "required|in:wechat,alipay"
        ]);

        $num = $request->getParam("num");
        $pay_type = $request->getParam("pay_type");

        return $this->response(BuyLogic::getInstance()->coin($num, $pay_type));
    }

    public function fetchOrderList(ServerRequest $request)
    {
        return $this->response(BuyLogic::getInstance()->fetchOrderList());
    }

    public function fetchBillList(ServerRequest $request)
    {
        return $this->response(BuyLogic::getInstance()->fetchBillList());
    }

    /**
     * 分析师等级价格列表
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function analystLevelPriceList(ServerRequest $request)
    {
        $level = $request->getParam("level", null);
        $month = $request->getParam("month", null);

        return $this->response(BuyLogic::getInstance()->analystLevelPriceList($level, $month));
    }

    /**
     * 用户等级价格列表
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function userLevelPriceList(ServerRequest $request)
    {
        $level = $request->getParam("level", null);
        $month = $request->getParam("month", null);

        return $this->response(BuyLogic::getInstance()->userLevelPriceList($level, $month));
    }

}