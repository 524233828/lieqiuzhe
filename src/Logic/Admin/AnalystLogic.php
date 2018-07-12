<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/12
 * Time: 17:02
 */

namespace Logic\Admin;


use Model\AnalystInfoModel;
use Model\AnalystLevelOrderModel;
use Model\UserModel;
use Service\Pager;

class AnalystLogic extends AdminBaseLogic
{

    protected $list_filter = [
        "nickname"
    ];

    public function listAction($params)
    {
        //列表分页参数
        $page = isset($params['page'])?$params['page']:1;
        $size = isset($params['size'])?$params['size']:20;

        $pager = new Pager($page, $size);

        $where = null;

        //检查请求参数中是否有筛选可用参数，有则按参数筛选
        foreach ($this->list_filter as $v){
            if(isset($params[$v]))
            {
                $where[UserModel::$table.".".$v] = $params[$v];
            }
        }

        //计算符合筛选参数的行数
        $count = UserModel::countUserWithAnalyst($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = UserModel::fetchUserWithAnalyst([
            UserModel::$table.".id",
            UserModel::$table.".avatar",
            UserModel::$table.".nickname",
            AnalystInfoModel::$table.".ticket",
        ],$where);

        $ids = [];
        $analyst_list = [];
        foreach ($list as $v)
        {
            $analyst_list[$v['id']] = $v;
            $analyst_list[$v['id']]['level'] = 0;
            $ids[] = $v['id'];
        }

        $level_list = AnalystLevelOrderModel::fetchAnalystCurrentLevel($ids);

        foreach ($level_list as $value){

            $analyst_list[$value['uid']]['level'] = $value['level'];
        }

        $list = array_values($analyst_list);

        return ["list"=>$list, "meta" => $pager->getPager($count)];
    }

    public function deleteAction($params)
    {
        // TODO: Implement deleteAction() method.
    }

    public function getAction($params)
    {
        // TODO: Implement getAction() method.
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