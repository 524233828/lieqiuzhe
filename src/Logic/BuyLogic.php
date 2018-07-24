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
use Model\UserBillModel;
use Model\UserLevelModel;
use Model\UserLevelOrderModel;
use Pay\Pay;
use Service\Goods\Goods;
use Service\Pager;

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
            "product_id" => Goods::USER_LEVEL,
            "user_id" => UserLogic::$user['id'],
            "pay_type" => $pay_type
        ];

        $level_data = [
            "order_id" => $order_id,
            "create_time" => time(),
            "uid" => UserLogic::$user['id'],
            "level" => $level_info['level'],
            "month" => $month,
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
            "product_id" => Goods::ANALYST_LEVEL,
            "user_id" => UserLogic::$user['id'],
            "pay_type" => $pay_type
        ];

        $level_data = [
            "order_id" => $order_id,
            "create_time" => time(),
            "uid" => UserLogic::$user['id'],
            "level" => $level_info['level'],
            "month" => $month,
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

        database()->pdo->beginTransaction();
        $order_data = [
            "order_id" => $order_id,
            "info" => $info,
            "total_fee" => $num,
            "create_time" => time(),
            "product_id" => Goods::COIN,
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
        $config = config()->get("payment");
        $pay = new Pay($config);

        if($pay_type == "alipay"){

            //定义开发环境
            define("AOP_SDK_DEV_MODE", $config['alipay']['debug']);

            require app()->getPath().'/Alipay/AopSdk.php';
            $aop = new \AopClient ();
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
            $aop->appId = $config['alipay']['app_id'];
            $aop->rsaPrivateKey = $config['alipay']['private_key'];
            $aop->alipayrsaPublicKey= $config['alipay']['public_key'];
            $aop->apiVersion = '1.0';
            $aop->postCharset='utf-8';
            $aop->format='json';
            $aop->signType = 'RSA2';
            $request = new \AlipayTradeAppPayRequest();
            //异步地址传值方式

            $request->setNotifyUrl("https://www.alipay.com");
            $data = [
                "out_trade_no" => (string)$order['out_trade_no'],
                "total_amount" => (string)$order['total_amount'],
                "product_code" => "QUICK_MSECURITY_PAY",
                "subject" => $order['subject']
            ];

            $request->setBizContent(json_encode($data, JSON_UNESCAPED_UNICODE));
//            $unsign = "alipay_sdk=alipay-sdk-php-20161101&app_id=2018052160205069&biz_content={\"out_trade_no\":15324153875156,\"total_amount\":\"1000\",\"product_code\":\"QUICK_MSECURITY_PAY\",\"subject\":\"球稳-球币充值-1000球币\"}&charset=utf-8&format=json&method=alipay.trade.app.pay&notify_url=https://www.alipay.com&sign_type=RSA2&timestamp=2018-07-24 14:56:27&version=1.0";
//            $result = $aop->sign($unsign,"RSA2");
//            echo $result;exit;
            $result = $aop->sdkExecute($request);

            return $result;

        }else{
            return $pay->driver($pay_type)->gateway("app")->apply($order);
        }
    }

    public function fetchOrderList($page = 1, $size = 20)
    {

        $pager = new Pager($page, $size);
        $user_id = UserLogic::$user['id'];

        $where = ["user_id"=>$user_id, "status" => 1];

        $count = OrderModel::count($where);

        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $order_list = OrderModel::fetch(["order_id","total_fee","info","pay_time"], $where);

        foreach ($order_list as $k => $v)
        {
            $order_list[$k]['pay_time'] = date("Y-m-d H:i:s", $v['pay_time']);
        }

        return ["list" => $order_list, "meta" => $pager->getPager($count)];
    }

    public function fetchBillList($page = 1, $size = 20)
    {

        $pager = new Pager($page, $size);
        $user_id = UserLogic::$user['id'];

        $where = ["user_id"=>$user_id];

        $count = UserBillModel::count($where);

        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $order_list = UserBillModel::fetch(["order_id","inc_bill","explain","create_at"], $where);

        foreach ($order_list as $k => $v)
        {
            $order_list[$k]['create_at'] = date("Y-m-d H:i:s", $v['create_at']);
        }

        $current_bill = UserBillModel::getCurrentBill($user_id);

        return ["current_bill" => $current_bill,"list" => $order_list, "meta" => $pager->getPager($count)];
    }

    public function analystLevelPriceList($level = null, $month = null)
    {
        $list = AnalystLevelModel::fetchAnalystLevel($level, $month);

        return ["list" => $list];
    }

    public function userLevelPriceList($level = null, $month = null)
    {
        $list = AnalystLevelModel::fetchAnalystLevel($level, $month);

        return ["list" => $list];
    }
}