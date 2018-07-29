<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/29
 * Time: 16:43
 */

namespace Controller\Admin;


use Controller\BaseController;
use FastD\Http\ServerRequest;
use Logic\Admin\IncomeStaticLogic;

class IncomeStaticController extends BaseController
{

    /**
     * @name 获取最近一周的收入
     * @returnParam sum|float|平台总收入
     * @returnParam list[].pay_date|string|支付日期
     * @returnParam list[].income|float|当天收入
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function incomeStatic(ServerRequest $request)
    {
        $start_date = $request->getParam("start_date", null);
        $end_date = $request->getParam("end_date", null);
        $format = $request->getParam("format", "month");

        return $this->response(IncomeStaticLogic::getInstance()->incomeStatic($start_date, $end_date, $format));
    }

}