<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/6/25
 * Time: 10:49
 */

namespace Logic;


use FastD\Http\ServerRequest;
use Model\OrderModel;
use Service\Goods\Goods;
use Service\OrderService;

class NotifyLogic extends BaseLogic
{
    public function payNotify(ServerRequest $request)
    {
        $out_trade_no = $request->getParam("out_trade_no");

        $order = OrderModel::getOrderByOrderId($out_trade_no);

        call_user_func([$this,$order['pay_type']], $request, $order);
    }

    private function alipay(ServerRequest $request, $order)
    {
        //TODO: 验签

        //更新订单
        $coupon = $request->getParam('voucher_detail_list',0);
        $coupon_list = empty($coupon)?[]:json_decode($coupon, true);
        $coupon_fee = 0;
        foreach ($coupon_list as $v){
            $coupon_fee += $v['amount'];
        }
        $order_data = [
            "settlement_total_fee" => $request->getParam('receipt_amount'),
            "fee_type" => "CNY",
            "coupon_fee" => $coupon_fee,
            "transaction_id" => $request->getParam('trade_no'),
            "bank_type" => "",
            "pay_time" => $request->getParam('gmt_payment'),
            "status" => 1
        ];
        OrderService::updateOrder($order, $order_data);

    }

    private function wechat(ServerRequest $request, $order)
    {
        //TODO: 验签

        $payment = wechat()->payment;
        $response = $payment->handleNotify("\Logic\NotifyLogic::wechatOrderNotify");
        $response->send();
        //更新订单

    }

    public static function wechatOrderNotify($notify,$successful)
    {
        if($successful){
            $order = OrderModel::getOrderByOrderId($notify['out_trade_no']);
            $order_data = [
                "settlement_total_fee" => $notify['total_fee']/100,
                "fee_type" => $notify['fee_type'],
                "coupon_fee" => isset($notify['coupon_fee'])?$notify['coupon_fee']/100:0,
                "transaction_id" => $notify['transaction_id'],
                "bank_type" => $notify['bank_type'],
                "pay_time" => strtotime($notify['time_end']),
                "status" => 1
            ];
            OrderService::updateOrder($order, $order_data);
        }

    }
}