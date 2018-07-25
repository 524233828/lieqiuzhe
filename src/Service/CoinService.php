<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/25
 * Time: 22:18
 */

namespace Service;


use Model\OrderModel;
use Model\UserBillModel;

class CoinService
{
    public static function sendCoin($num, $uid, $reason)
    {
        $order_id = OrderModel::getOrderId();
        $current_bill = UserBillModel::getCurrentBill($uid);

        $new_bill = $current_bill + $num;

        //新增用户余额变更记录
        $data = [
            "explain" => $reason,
            "order_id" => $order_id,
            "status" => 1,
            "current_bill" => $new_bill,
            "inc_bill" => $num,
            "last_bill" => $current_bill,
            "create_at" => time()
        ];

        $result = UserBillModel::add($data);
    }
}