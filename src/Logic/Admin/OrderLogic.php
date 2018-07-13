<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/13
 * Time: 18:44
 */

namespace Logic\Admin;


use Model\OrderModel;
use Service\Pager;

class OrderLogic extends AdminBaseLogic
{

    protected $list_filter = [
        "order_id",
        "status",
        "user_id",
    ];

    public function listAction($params)
    {
        ///列表分页参数
        $page = isset($params['page'])?$params['page']:1;
        $size = isset($params['size'])?$params['size']:20;

        $pager = new Pager($page, $size);

        $where = null;

        //检查请求参数中是否有筛选可用参数，有则按参数筛选
        foreach ($this->list_filter as $v){
            if(isset($params[$v]))
            {
                $where[$v] = $params[$v];
            }
        }

        if(isset($params["pay_time"])){
            $start_time = strtotime($params['pay_time']);
            $end_time = strtotime($params['pay_time']. "+1 day");
            $where["pay_time[>=]"] = $start_time;
            $where["pay_time[<]"] = $end_time;
        }

        //计算符合筛选参数的行数
        $count = OrderModel::count($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];
        $where["ORDER"] = ["start_time" => "DESC"];

        $list = OrderModel::fetch([
            "order_id",
            "total_fee",
            "create_time",
            "pay_time",
            "status",
            "user_id",
            "info",
            "pay_type",
        ],$where);

        foreach ($list as $k=>$v)
        {
            $list[$k]['create_time'] = date("Y-m-d H:i:s", $v['create_time']);
            if($v['pay_time']){
                $list[$k]['pay_time'] = date("Y-m-d H:i:s", $v['pay_time']);
            }else{
                $list[$k]['pay_time'] = '';
            }
        }

        return ["list"=>$list, "meta" => $pager->getPager($count)];
    }

    public function getAction($params)
    {
        // TODO: Implement getAction() method.
    }

    public function deleteAction($params)
    {
        // TODO: Implement deleteAction() method.
    }

    public function addAction($params)
    {
        // TODO: Implement addAction() method.
    }

    public function updateAction($params)
    {
        // TODO: Implement updateAction() method.
    }
}