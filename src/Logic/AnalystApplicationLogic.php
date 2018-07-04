<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/4
 * Time: 23:07
 */

namespace Logic;


use Exception\BaseException;
use Model\AnalystApplicationModel;

class AnalystApplicationLogic extends BaseLogic
{

    public function addAnalystApplication($data)
    {
        $result = AnalystApplicationModel::add($data);

        if($result)
        {
            return [];
        }else{
            BaseException::SystemError();
        }
    }
}