<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/9
 * Time: 10:20
 */

namespace Logic\Admin;


use Constant\ErrorCode;
use Exception\BaseException;
use Model\AnalystApplicationModel;
use Model\AnalystInfoModel;
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
            AnalystApplicationModel::$table.".ball_year",
            AnalystApplicationModel::$table.".league",
            AnalystApplicationModel::$table.".skill",
            AnalystApplicationModel::$table.".good_at",
            AnalystApplicationModel::$table.".intro",
            AnalystApplicationModel::$table.".status",
            UserModel::$table.".avatar",
        ],$where);

        return ["list"=>$list, "meta" => $pager->getPager($count)];
    }

    //审核不通过
    public function deleteAction($params)
    {
        //软删除，只更新表里的状态
        $id = $params['id'];

        if(empty($id))
        {
            BaseException::SystemError();
        }

        $item = AnalystApplicationModel::get($id, ["status"]);
        //已审核过了
        if($item['status']!=0)
        {
            error(ErrorCode::APPLICATION_CHECKED);
        }

        $result = AnalystApplicationModel::update(["status" => 2],["id" => $id]);

        if($result)
        {
            return [];
        }else{
            BaseException::SystemError();
        }
    }

    //通过审核
    public function applicationPass($params)
    {
        if(!$params['id']){
            BaseException::SystemError();
        }

        //取出申请信息
        $application = AnalystApplicationModel::get($params['id']);

        if($application['status']!=0){
            error(ErrorCode::APPLICATION_CHECKED);
        }

        //创建分析师
        $analyst_info_data = [

            "id" =>$application['user_id'],
            "user_id" => $application['user_id'],
            "create_time" => time(),
            "status" => 1,
            "tag" => $application['tag'],
            "intro" => $application['intro'],
        ];

        //开启事务
        database()->pdo->beginTransaction();

        $analyst_result = true;
        if(!AnalystInfoModel::get($application['user_id'])){
            //添加分析师
            $analyst_result = AnalystInfoModel::add($analyst_info_data);
        }

        //更改申请表状态
        $application_result = AnalystApplicationModel::update(["status" => 1], ["id"=>$params['id']]);

        //更改用户类型
        $user_result = UserModel::update(["user_type" => 1], ["id"=>$application['user_id']]);

        if($analyst_result && $application_result && $user_result){
            database()->pdo->commit();
            return [];
        }

        database()->pdo->rollBack();

        BaseException::SystemError();

    }
}