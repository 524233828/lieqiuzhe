<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/29
 * Time: 20:44
 */

namespace Logic\Admin;


use Exception\BaseException;
use Model\IconsModel;
use Service\Pager;

class IconLogic extends AdminBaseLogic
{

    public function listAction($params)
    {
        //列表分页参数
        $page = isset($params['page'])?$params['page']:1;
        $size = isset($params['size'])?$params['size']:20;

        $pager = new Pager($page, $size);

        $where = null;

        //计算符合筛选参数的行数
        $count = IconsModel::count($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = IconsModel::fetch([
            "id",
            "level",
            "type",
            "icon",
            "num",
            "intro"
        ],$where);

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
        $id = $params['id'];

        if(empty($id)){
            BaseException::SystemError();
        }

        $data = [];

        if(isset($params['num'])){
            $data['num'] = $params['num'];
        }

        if(isset($params['intro'])){
            $data['intro'] = $params['intro'];
        }

        $result = IconsModel::update($data, ["id" => $id]);

        if($result !== false){
            return [];
        }

        BaseException::SystemError();
    }
}