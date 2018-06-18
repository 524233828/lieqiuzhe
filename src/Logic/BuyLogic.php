<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/25
 * Time: 15:45
 */

namespace Logic;


use Exception\AnalystException;
use Exception\OrderException;
use Exception\UserException;
use Model\AnalystLevelModel;
use Model\AnalystLevelOrderModel;
use Model\OrderModel;
use Model\UserLevelModel;
use Model\UserLevelOrderModel;
use Pay\Pay;

class BuyLogic extends BaseLogic
{

    public function userLevel($level, $month, $pay_type)
    {
        $current_level = UserLevelOrderModel::getUserCurrentLevel(UserLogic::$user['id']);

        if($current_level['level'] >= $level)
        {
            UserException::userLevelExists();
        }

        $level_info = UserLevelModel::getUserLevelByInfo($level, $month);

        $info = "球稳-用户等级lv{$level}-{$month}月有效期";

        $order_id = OrderModel::getOrderId();

        if($pay_type == "wechat")
        {
            $order = [
                "out_trade_no" => $order_id,
                "total_fee" => $level_info['price'] * 100,
                "spbill_create_ip" => "",
                "body" => $info,
            ];
        }else{
            $order = [
                "out_trade_no" => $order_id,
                "total_amount" => $level_info['price'],
                "subject" => $info,
            ];
        }

        database()->pdo->beginTransaction();
        $order_data = [
            "order_id" => $order_id,
            "info" => $info,
            "total_fee" => $level_info['price'],
            "create_time" => time(),
            "product_id" => 1,
            "user_id" => UserLogic::$user['id'],
            "pay_type" => $pay_type
        ];

        $level_data = [
            "order_id" => $order_id,
            "create_time" => time(),
            "uid" => UserLogic::$user['id'],
            "level" => $level_info['level']
        ];

        $order_res = OrderModel::add($order_data);

        $level_res = UserLevelOrderModel::add($level_data);

        if($order_res&&$level_res)
        {
            database()->pdo->commit();

        }else{
            database()->pdo->rollBack();
        }

        $res = $this->pay($pay_type, $order);

        if($res)
        {
            return $res;
        }else{
            OrderException::createOrderFail();
        }


    }

    public function analystLevel($level, $month, $pay_type)
    {

        if(UserLogic::$user['user_type'] != 1)
        {
            AnalystException::userNotAnalyst();
        }

        $current_level = AnalystLevelOrderModel::getAnalystCurrentLevel(UserLogic::$user['id']);

        if($current_level >= $level)
        {
            UserException::userLevelExists();
        }

        $level_info = AnalystLevelModel::getAnalystLevelByInfo($level, $month);

        $info = "球稳-分析师等级lv{$level}-{$month}月有效期";

        $order_id = OrderModel::getOrderId();

        if($pay_type == "wechat")
        {
            $order = [
                "out_trade_no" => $order_id,
                "total_fee" => $level_info['price'] * 100,
                "spbill_create_ip" => "",
                "body" => $info,
            ];
        }else{
            $order = [
                "out_trade_no" => $order_id,
                "total_amount" => $level_info['price'],
                "subject" => $info,
            ];
        }

        database()->pdo->beginTransaction();
        $order_data = [
            "order_id" => $order_id,
            "info" => $info,
            "total_fee" => $level_info['price'],
            "create_time" => time(),
            "product_id" => 2,
            "user_id" => UserLogic::$user['id'],
            "pay_type" => $pay_type
        ];

        $level_data = [
            "order_id" => $order_id,
            "create_time" => time(),
            "uid" => UserLogic::$user['id'],
            "level" => $level_info['level']
        ];

        $order_res = OrderModel::add($order_data);

        $level_res = AnalystLevelOrderModel::add($level_data);

        if($order_res&&$level_res)
        {
            database()->pdo->commit();

        }else{
            database()->pdo->rollBack();
        }

        $res = $this->pay($pay_type, $order);

        if($res)
        {
            return $res;
        }else{
            OrderException::createOrderFail();
        }

    }

    public function coin($num, $pay_type)
    {
        $order_id = OrderModel::getOrderId();

        $info = "球稳-球币充值-{$num}球币";

        if($pay_type == "wechat")
        {
            $order = [
                "out_trade_no" => $order_id,
                "total_fee" => $num * 100,
                "spbill_create_ip" => "",
                "body" => $info,
            ];
        }else{
            $order = [
                "out_trade_no" => $order_id,
                "total_amount" => $num,
                "subject" => $info,
            ];
        }

        $order_data = [
            "order_id" => $order_id,
            "info" => $info,
            "total_fee" => $num,
            "create_time" => time(),
            "product_id" => 3,
            "user_id" => UserLogic::$user['id'],
            "pay_type" => $pay_type
        ];

        $order_res = OrderModel::add($order_data);

        if($order_res)
        {
            database()->pdo->commit();

        }else{
            database()->pdo->rollBack();
        }

        $res = $this->pay($pay_type, $order);

        if($res)
        {
            return $res;
        }else{
            OrderException::createOrderFail();
        }

    }

    private function pay($pay_type = "wechat", $order)
    {
        $config = config()->get("pay");
        $pay = new Pay($config);

        return $pay->driver($pay_type)->gateway("app")->apply($order);
    }
}