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
}