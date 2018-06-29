<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/6/26
 * Time: 10:48
 */

namespace Exception;


use Constant\ErrorCode;

class CoinException extends BaseException
{

    public static function coinNotEnough()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::COIN_NOT_ENOUGH),
            ErrorCode::COIN_NOT_ENOUGH
        );
    }

}