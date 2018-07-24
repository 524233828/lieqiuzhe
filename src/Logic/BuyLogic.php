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
            $aop->rsaPrivateKey = "MIIEowIBAAKCAQEAxM/OIshZ4OnmUu4v98gpU9g8HPg56DqWMaDvcgzVsFn3zgMIo29Tm6bTQvjKWBG67ZG+atTGgJ+hd1p7zI4HZOqmRIvFFWEQ4KdwJrBClO/+Eazv+bGJSILgxu/p8DfwzsqSWqucJtU6nU9ViQS6LurvId3ue2p/UWJQP7ViewuMRzGbXL1PR45ggtOGJZYMu+34i7uJuHHpb/2SQsfDL0ONNQce3TTcL9rb2T0xtNSC54cFOphppnLyJIyOryJkUnHfL4gpNoHUTnwLdIKMY0bpPqqJm13/PN7HxmnnUMr+kr4KnrHk7S+3QQdk8AII+5RhRsK5KxSVZEJOxyoqRQIDAQABAoIBABKOUS4gW7ED/I5HHMis25CnK4vDr5oApBaLyOek5CTbZqzKxm66WVSslvCSimlhSpGJkz15UDniUxPwuQlhPrs6EHEYCH3qh+/WeZF8PtrSAc9i0cFmBr6KcGzxQ8o9S/wDR2c7FN7obb1VNIhVpMQ1rFQyG4ERWm2u6kgHbwCQvMwdBKUz3vuD74wCSx+W8EVAqfDI4ij5CxCgj9QwAT+qRVIFPFBpAg3OpR1nLqhJUhHWIQQmn5+myJ4Ac8lp4am0KbFc+/ZHei19jYTi8yvC3Kb6JSXm9SS/2JDreqCaA5Y70zwNeGM/AfnFP6fBjZzUxGzDRDDn+ubymhn5xIUCgYEA6oHvkrfNgS+3LHRlLWSRTFizulmrdOTuyI0ugqCDKtnY9IaT8vvSEBKrpLe5jN4PTAD4v1oeW89kKn6Iy87cFTP8GXYN38kMkptP2lhBSjsuijTcXhpbou57p0wJ38F+IEKgUcG2KIeK75FUvPXxaWjUt7cq2LxvYe6wAOVPtyMCgYEA1tlxO7gZBVh50OpEyUjTXzmNDgjYu3dcreNc5pYiumdAYRHHDftutcpghWQ0jN9jjDMnHeJ6+ZGvmj/YBOwOAGr9W8O5dIEpK8jPgtW4c7g7J+aZAlyBIu/XyFLD+qFLyI01kRhIZ+PobVXTU8oe/AfQ4wtxOUs3C3tPOdjo43cCgYEAvL7QIHqngO7ys2kLdjmXaKeMINTDV1Zbijd309N1PywPnuAifFOKgz1DwVPOmD6yeS3fB8R04thNepZVbBSWtsocgjGugQvEfstavhaClkiD8OES7Pqx/rWL+N8Oo3WNGlIFz0fmYUCW5rNGTMB3CaxCaYuXhNJFo8EFD/OA8ZkCgYA8QvUduP9bnntce7kbdA/Fb9D+lMClpE8cft851fabrgZCs8fPRizBVKhKAdczhBzZ4CcinLm9cn18mFew2bz7pQa3TGiiIvA3VbXOjr+TxaLiCC32mZenAvrVN1G85Kzq7aCOt+7nJOe2cxI5OEIEkvSmGjmBxnUEBWwtX4fC9QKBgHliVeqT39Pe2KVNpJJBrn1rwApz8/7Mq1GLb+eLQ+EvyISvQd7XaDqwFHeCn/7QUq3R/HKKMTY7kwDfPPGRwAtpVSDNMBTKlLl92DI/s5ZzIwnAJLPfbZJUDY5kGsvZQAugspFjtJen6LHu2Hufy77bvC1Ind9MOLe8XDK2uGpd";
            $aop->alipayrsaPublicKey= $config['alipay']['public_key'];
            $aop->apiVersion = '1.0';
            $aop->postCharset='utf-8';
            $aop->format='json';
            $aop->signType = 'RSA2';
            $request = new \AlipayTradeAppPayRequest();
            //异步地址传值方式

            $request->setNotifyUrl("https://www.alipay.com");
            $request->setBizContent("{\"out_trade_no\":\"".$order['out_trade_no']."\",\"total_amount\":0.01,\"product_code\":\"QUICK_MSECURITY_PAY\",\"subject\":\"app测试\"}");
//            $result = $aop->sign(json_encode(["a"=>"123"]),"RSA2");
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