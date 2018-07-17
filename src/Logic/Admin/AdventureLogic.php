<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/5
 * Time: 23:04
 */

namespace Logic\Admin;


use Exception\BaseException;
use Model\AdventureModel;
use Model\PageModel;
use Service\Pager;

class AdventureLogic extends AdminBaseLogic
{

    protected $list_filter = [
        "status",
        "page_id",
        "url",
        "params",
        "img_url",
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
                $where[AdventureModel::$table.".".$v] = $params[$v];
            }
        }

        //计算符合筛选参数的行数
        $count = AdventureModel::count($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = AdventureModel::fetchWithPage([
            AdventureModel::$table.".id",
            AdventureModel::$table.".update_time",
            AdventureModel::$table.".status",
            AdventureModel::$table.".page_id",
            PageModel::$table.".name",
            AdventureModel::$table.".url",
            AdventureModel::$table.".params",
            AdventureModel::$table.".img_url",
            AdventureModel::$table.".sort",
        ],$where);

        return ["list"=>$list, "meta" => $pager->getPager($count)];

    }

    public function deleteAction($params)
    {
        //软删除，只更新表里的状态，已冻结则解冻
        $id = $params['id'];

        if(empty($id))
        {
            BaseException::SystemError();
        }

        $item = AdventureModel::get($id, ["status"]);

        $status = 1 - $item['status'];

        $result = AdventureModel::update(["status" => $status],["id" => $id]);

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

        $result = AdventureModel::add($data);
        
        if($result){
            return [];
        }
        
        BaseException::SystemError();
    }

    public function getAction($params)
    {
        $id = $params['id'];

        if(empty($id)){
            BaseException::SystemError();
        }

        return AdventureModel::get($id);
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

        $result = AdventureModel::update($data, ["id" => $id]);

        if($result){
            return [];
        }

        BaseException::SystemError();
    }

    public function fetchPage()
    {
        $list = PageModel::fetch(["id", "name"],["status" => 1]);

        return ["list" => $list];
    }
}