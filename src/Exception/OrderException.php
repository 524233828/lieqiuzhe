<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/26
 * Time: 21:23
 */

namespace Exception;


use Constant\ErrorCode;

class OrderException extends BaseException
{

    public static function createOrderFail()
    {
        throw new self(ErrorCode::msg(ErrorCode::CREATE_ORDER_FAIL),ErrorCode::CREATE_ORDER_FAIL);
    }
}