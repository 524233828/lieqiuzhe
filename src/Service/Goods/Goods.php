<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/25
 * Time: 15:33
 */

namespace Service\Goods;


use Model\AnalystLevelOrderModel;
use Model\OrderModel;
use Model\UserBillModel;
use Model\UserLevelOrderModel;

class Goods
{
    const USER_LEVEL = 1;
    const ANALYST_LEVEL = 2;
    const COIN = 3;

    public static $gateway = [
        self::USER_LEVEL => "userLevel",
        self::ANALYST_LEVEL => "analystLevel",
        self::COIN => "coin",
    ];


    public static function userLevel($order_id)
    {
        $log = myLog("Goods_userLevel");
        $log->addDebug("order_id:".$order_id);
        //更新用户等级购买表
        $user_level_order = UserLevelOrderModel::getUserLevelOrderByOrderId($order_id);
        $end_time = time() + $user_level_order['month'] * 2592000;
        $result = UserLevelOrderModel::update(['status' => 1, 'end_time' => $end_time],["order_id" => $order_id]);
        $log->addDebug("result:".$result);
    }

    public static function analystLevel($order_id)
    {
        //更新分析师等级购买表
        $analyst_level_order = AnalystLevelOrderModel::getAnalystLevelOrderByOrderId($order_id);
        $end_time = time() + $analyst_level_order['month'] * 2592000;
        AnalystLevelOrderModel::update(['status' => 1, 'end_time' => $end_time],["order_id" => $order_id]);
    }

    public static function coin($order_id)
    {
        $log = myLog("Goods_coin");
        $log->addDebug("order_id:".$order_id);
        //取出用户当前余额
        $order = OrderModel::getOrderByOrderId($order_id);
        $current_bill = UserBillModel::getCurrentBill($order['user_id']);

        $new_bill = $current_bill + $order['total_fee'];

        //新增用户余额变更记录
        $data = [
            "explain" => "用户充值增加金币",
            "order_id" => $order_id,
            "user_id" => $order['user_id'],
            "status" => 1,
            "current_bill" => $new_bill,
            "inc_bill" => $order['total_fee'],
            "last_bill" => $current_bill,
            "create_at" => time()
        ];

        $result = UserBillModel::add($data);
        $log->addDebug("result:".$result);
    }

}