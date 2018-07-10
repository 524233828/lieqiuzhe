<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/9
 * Time: 10:20
 */

namespace Logic\Admin;


use Exception\BaseException;
use Model\AnalystApplicationModel;
use Model\UserModel;
use Service\Pager;

class AnalystApplicationLogic extends AdminBaseLogic
{

    protected $list_filter = [
        "nickname",
        "status"
    ];

    public function updateAction($params)
    {
        // TODO: Implement updateAction() method.
    }

    public function addAction($params)
    {
        // TODO: Implement addAction() method.
    }

    public function getAction($params)
    {
        $id = $params['id'];

        if(empty($id)){
            BaseException::SystemError();
        }

        return AnalystApplicationModel::get($id);
    }

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
                $where[AnalystApplicationModel::$table.".".$v] = $params[$v];
            }
        }

        //计算符合筛选参数的行数
        $count = AnalystApplicationModel::count($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = AnalystApplicationModel::fetchWithUser([
            AnalystApplicationModel::$table.".id",
            AnalystApplicationModel::$table.".nickname",
            AnalystApplicationModel::$table.".sex",
            AnalystApplicationModel::$table.".tag",
            AnalystApplicationModel::$table.".intro",
            UserModel::$table.".avatar",
        ],$where);

        return ["list"=>$list, "meta" => $pager->getPager($count)];
    }

    public function deleteAction($params)
    {
        // TODO: Implement deleteAction() method.
    }

    //通过审核
    public function applicationPass($params)
    {
        if(!$params['id']){
            BaseException::SystemError();
        }


    }
}