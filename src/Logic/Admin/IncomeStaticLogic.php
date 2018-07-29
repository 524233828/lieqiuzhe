<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/29
 * Time: 16:42
 */

namespace Logic\Admin;

use Logic\BaseLogic;
use Model\OrderModel;

class IncomeStaticLogic extends BaseLogic
{

    private $format = [
        "year" => "%Y",
        "month" => "%Y-%m",
        "day" => "%Y-%m-%d",
        "hour" => "%Y-%m-%d %H",
        "minute" => "%Y-%m-%d %H-%i",
    ];

    public function incomeStatic($start_date = null, $end_date = null, $format = "month")
    {
        if(empty($end_date))
        {
            $end_date = date("Ymd", time());
        }

        if(empty($start_date))
        {
            $start_date = date("Ymd", time()-31536000);
        }

        $start_time = strtotime($start_date);
        $end_time = strtotime($end_date . "+1 day");
        
        if(isset($this->format[$format]))
        {
            $format = $this->format[$format];
        }else{
            $format = $this->format['month'];
        }

        $result = [];

        $result['sum'] = OrderModel::incomeStaticSum();

        $result['list'] = OrderModel::dailyIncome($start_time, $end_time, $format);

        return $result;
    }
}