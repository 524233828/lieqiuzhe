<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/4
 * Time: 23:07
 */

namespace Logic;


use Constant\ErrorCode;
use Exception\BaseException;
use Model\AnalystApplicationModel;

class AnalystApplicationLogic extends BaseLogic
{

    public function addAnalystApplication($data)
    {

        $data['user_id'] = UserLogic::$user['id'];
        if(UserLogic::$user['user_type'] == 1)
        {
            error(ErrorCode::ANALYST_ALREADY);
        }

        $count = AnalystApplicationModel::count(["user_id"=>$data['user_id'], "status"=>0]);

        if(!$count)
        {
            error(ErrorCode::ANALYST_IN_REVIEW);
        }

        $result = AnalystApplicationModel::add($data);

        if($result)
        {
            return [];
        }else{
            BaseException::SystemError();
        }
    }
}