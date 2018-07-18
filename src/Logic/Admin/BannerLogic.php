<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/5
 * Time: 23:04
 */

namespace Logic\Admin;


use Exception\BaseException;
use Model\BannerModel;
use Model\PageModel;
use Service\Pager;

class BannerLogic extends AdminBaseLogic
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
                $where[BannerModel::$table.".".$v] = $params[$v];
            }
        }

        //计算符合筛选参数的行数
        $count = BannerModel::count($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = BannerModel::fetchWithPage([
            BannerModel::$table.".id",
            BannerModel::$table.".update_time",
            BannerModel::$table.".status",
            PageModel::$table.".name",
            BannerModel::$table.".url",
            BannerModel::$table.".page_id",
            BannerModel::$table.".params",
            BannerModel::$table.".img_url",
            BannerModel::$table.".sort",
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

        $item = BannerModel::get($id, ["status"]);

        $status = 1 - $item['status'];

        $result = BannerModel::update(["status" => $status],["id" => $id]);

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

        $result = BannerModel::add($data);
        
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

        return BannerModel::get($id);
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

        $result = BannerModel::update($data, ["id" => $id]);

        if($result){
            return [];
        }

        BaseException::SystemError();
    }
}