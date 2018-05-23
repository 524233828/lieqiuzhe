<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/20
 * Time: 16:26
 */

namespace Exception;


use Constant\ErrorCode;

class AnalystException extends BaseException
{

    public static function userNotAnalyst()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::USER_NOT_ANALYST),
            ErrorCode::USER_NOT_ANALYST
        );
    }

    public static function analystLevelTooLow()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::ANALYST_LEVEL_TOO_LOW),
            ErrorCode::ANALYST_LEVEL_TOO_LOW
        );
    }
}