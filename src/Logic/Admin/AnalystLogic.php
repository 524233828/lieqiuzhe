<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/12
 * Time: 17:02
 */

namespace Logic\Admin;


use Exception\AnalystException;
use Exception\BaseException;
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
            AnalystInfoModel::$table.".tag",
            AnalystInfoModel::$table.".intro",
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
        $id = $params['id'];

        if(empty($id))
        {
            BaseException::SystemError();
        }

        //查分析师表
        $analyst_info = AnalystInfoModel::get($id);

        $update = false;
        if(!empty($analyst_info)){//已有分析师信息
            $update = true;
        }

        //组装更改字段
        $add_filter = [
            UserModel::$table => ["avatar", "nickname"],
            AnalystInfoModel::$table => ["ticket", "tag", "intro"]
        ];

        $data = [];
        foreach ($add_filter as $table => $field)
        {
            if(isset($params[$field]) && !empty($params[$field])){
                $data[$table][$field] = $params[$field];
            }
        }

        //用户类型改为分析师
        $data[UserModel::$table]["user_type"] = 1;

        $where = ["id" => $id];

        $user_result = true;
        $analyst_info_result = true;

        //写表操作
        database()->pdo->beginTransaction();
        if(!empty($data[UserModel::$table])){
            $user_result = UserModel::update($data[UserModel::$table], $where);
        }

        if(!empty($data[AnalystInfoModel::$table])){
            if($update){//已有分析师信息，只更新不插入
                $analyst_info_result = AnalystInfoModel::update($data[AnalystInfoModel::$table], $where);
            }else{//没有则插入
                $analyst_info_result = AnalystInfoModel::add($data[AnalystInfoModel::$table]);
            }
        }

        if($user_result && $analyst_info_result){
            database()->pdo->commit();

            return [];
        }else{
            database()->pdo->rollBack();

            BaseException::SystemError();
        }
    }

    public function updateAction($params)
    {

        $id = $params['id'];

        if(empty($id))
        {
            BaseException::SystemError();
        }

        //查分析师表
        $analyst_info = AnalystInfoModel::get($id);

        if(empty($analyst_info)){//分析师不存在
            AnalystException::userNotAnalyst();
        }

        //筛选字段
        $add_filter = [
            UserModel::$table => ["avatar", "nickname"],
            AnalystInfoModel::$table => ["ticket", "tag", "intro"]
        ];

        $data = [];
        foreach ($add_filter as $table => $field)
        {
            if(isset($params[$field]) && !empty($params[$field])){
                $data[$table][$field] = $params[$field];
            }
        }


        $where = ["id" => $id];

        //写表操作
        $user_result = true;
        $analyst_info_result = true;

        database()->pdo->beginTransaction();
        if(!empty($data[UserModel::$table])){
            $user_result = UserModel::update($data[UserModel::$table], $where);
        }

        if(!empty($data[AnalystInfoModel::$table])){
            $analyst_info_result = AnalystInfoModel::update($data[AnalystInfoModel::$table], $where);
        }

        if($user_result && $analyst_info_result){
            database()->pdo->commit();

            return [];
        }else{
            database()->pdo->rollBack();

            BaseException::SystemError();
        }

    }
}