<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/25
 * Time: 15:26
 */

namespace Service;


use Model\OrderModel;
use Service\Goods\Goods;

class OrderService
{

    public static function updateOrder($order, $data)
    {
        $log = myLog("OrderService_updateOrder");
        //更新订单状态
        $result = OrderModel::update($data,["order_id" => $order['order_id']]);

        $log->addDebug("result:".$result);

        //更新商品状态
        $gateway = Goods::$gateway[$order['product_id']];

        call_user_func(["\Service\Goods\Goods", $gateway], $order['order_id']);
    }
}