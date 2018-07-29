<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/26
 * Time: 16:58
 */

namespace Model;


class OrderModel extends BaseModel
{

    public static $table = "order";

    public static function getOrderId()
    {
        return microtime(true)*10000;
    }

    public static function getOrderByOrderId($order_id, $columns = "*")
    {
        return database()->get(self::$table, $columns, ["order_id" => $order_id]);
    }

    public static function incomeStaticSum()
    {
        return database()->sum(self::$table,"settlement_total_fee",["status[>]"=>0]);
    }

    public static function dailyIncome($start_time,$end_time, $format = "%Y-%m")
    {
        if($start_time>$end_time)
        {
            return [];
        }

        $table = self::$table;

        $sql = <<<SQL
SELECT 
  FROM_UNIXTIME(pay_time,'{$format}') as pay_date,
  sum(settlement_total_fee) as income
FROM {$table} 
WHERE 
  `status`>0 
AND
  pay_time>=$start_time
AND
  pay_time<$end_time
GROUP BY pay_date
SQL;
        return database()->query($sql)->fetchAll();

    }
}