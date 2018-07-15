<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/14
 * Time: 10:13
 */

namespace Logic\Admin;


use Exception\BaseException;
use Model\TopLineModel;
use Service\Pager;

class TopLineLogic extends AdminBaseLogic
{

    protected $list_filter = [
        "status",
        "page_id",
        "url",
        "params",
        "content",
        "sort"
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
                $where[TopLineModel::$table.".".$v] = $params[$v];
            }
        }

        //计算符合筛选参数的行数
        $count = TopLineModel::count($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = TopLineModel::fetchWithPage([
            TopLineModel::$table.".id",
            TopLineModel::$table.".update_time",
            TopLineModel::$table.".status",
            TopLineModel::$table.".url",
            TopLineModel::$table.".params",
            TopLineModel::$table.".content",
            TopLineModel::$table.".sort",
        ],$where);

        return ["list"=>$list, "meta" => $pager->getPager($count)];
    }

    public function getAction($params)
    {
        $id = $params['id'];

        if(empty($id)){
            BaseException::SystemError();
        }

        return TopLineModel::get($id);
    }

    public function deleteAction($params)
    {
        //软删除，只更新表里的状态，已冻结则解冻
        $id = $params['id'];

        if(empty($id))
        {
            BaseException::SystemError();
        }

        $item = TopLineModel::get($id, ["status"]);

        $status = 1 - $item['status'];

        $result = TopLineModel::update(["status" => $status],["id" => $id]);

        if($result)
        {
            return [];
        }else{
            BaseException::SystemError();
        }

    }

    public function addAction($params)
    {
        $data = [];

        foreach ($this->list_filter as $v){
            if(isset($params[$v])){
                $data[$v] = $params[$v];
            }
        }

        $data['create_time'] = time();
        $result = TopLineModel::add($data);

        if($result){
            return [];
        }

        BaseException::SystemError();
    }

    public function updateAction($params)
    {
        $id = $params['id'];

        if(empty($id)){
            BaseException::SystemError();
        }

        $data = [];

        foreach ($this->list_filter as $v){
            if(isset($params[$v])){
                $data[$v] = $params[$v];
            }
        }

        $result = TopLineModel::update($data, ["id" => $id]);

        if($result){
            return [];
        }

        BaseException::SystemError();
    }
}