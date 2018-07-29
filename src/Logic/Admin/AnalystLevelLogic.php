<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/5
 * Time: 23:04
 */

namespace Logic\Admin;


use Exception\BaseException;
use Model\AnalystLevelModel;
use Service\Pager;

class AnalystLevelLogic extends AdminBaseLogic
{

    protected $list_filter = [
        "level",
        "month",
        "price",
        "intro",
        "recommend_num",
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
                $where[AnalystLevelModel::$table.".".$v] = $params[$v];
            }
        }

        //计算符合筛选参数的行数
        $count = AnalystLevelModel::count($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = AnalystLevelModel::fetch([
            "id",
            "level",
            "month",
            "price",
            "intro",
            "recommend_num",
        ],$where);

        return ["list"=>$list, "meta" => $pager->getPager($count)];

    }

    public function deleteAction($params)
    {

    }

    public function addAction($params)
    {
    }

    public function getAction($params)
    {
        $id = $params['id'];

        if(empty($id)){
            BaseException::SystemError();
        }

        return AnalystLevelModel::get($id);
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

        $result = AnalystLevelModel::update($data, ["id" => $id]);

        if($result){
            return [];
        }

        BaseException::SystemError();
    }


}