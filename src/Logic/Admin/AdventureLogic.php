<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/5
 * Time: 23:04
 */

namespace Logic\Admin;


use Model\AdventureModel;
use Service\Pager;

class AdventureLogic extends AdminBaseLogic
{


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
                $where[$v] = $params[$v];
            }
        }

        //计算符合筛选参数的行数
        $count = AdventureModel::countMatch($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = AdventureModel::fetch("*",$where);

        return ["list"=>$list, "meta" => $pager->getPager($count)];

    }

    public function deleteAction($params)
    {
        // TODO: Implement deleteAction() method.
    }

    public function addAction($params)
    {
        // TODO: Implement addAction() method.
    }

    public function getAction($params)
    {
        $id = $params['id'];

        return AdventureModel::get($id);
    }

    public function updateAction($params)
    {
        // TODO: Implement updateAction() method.
    }
}