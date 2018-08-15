<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/15
 * Time: 10:47
 */

namespace Logic\Admin;


use Model\UserLevelOrderModel;
use Model\UserModel;
use Service\Pager;

class UserLogic extends AdminBaseLogic
{

    protected $list_filter = [
        "user_type",
        "status",
        "nickname",
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
            if(isset($params[$v]) && !empty($params[$v]))
            {
                $where[$v] = $params[$v];
            }
        }

        //只要app的用户
        $where["openid_type"] = [
            \Logic\LoginLogic::WECHAT_LOGIN,
            \Logic\LoginLogic::QQ_LOGIN,
            \Logic\LoginLogic::WEIBO_LOGIN,
            \Logic\LoginLogic::PHONE_LOGIN,
        ];

        //计算符合筛选参数的行数
        $count = UserModel::count($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = UserModel::fetch([
            "id",
            "nickname",
            "avatar",
            "user_type",
            "phone",
            "status",
            "ticket",
            "create_time",
        ],$where);

        $ids = [];
        $user_list = [];
        foreach ($list as $v)
        {
            $user_list[$v['id']] = $v;
            $user_list[$v['id']]['level'] = 0;
            $ids[] = $v['id'];
        }

        $level_list = [];
        if(!empty($ids)){
            $level_list = UserLevelOrderModel::fetchUserCurrentLevel($ids);
        }

        

        foreach ($level_list as $value){

            $user_list[$value['uid']]['level'] = $value['level'];
        }

        $list = array_values($user_list);

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