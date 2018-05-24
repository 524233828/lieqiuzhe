<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/22
 * Time: 18:17
 */

namespace Exception;

use Constant\ErrorCode;

class UserException extends BaseException
{

    public static function UserNotLogin()
    {
        throw new self(ErrorCode::msg(ErrorCode::USER_NOT_LOGIN),ErrorCode::USER_NOT_LOGIN);
    }

    public static function UserNotFound()
    {
        throw new self(ErrorCode::msg(ErrorCode::USER_NOT_EXISTS),ErrorCode::USER_NOT_EXISTS);
    }

    public static function LoginFail()
    {
        throw new self(ErrorCode::msg(ErrorCode::LOGIN_FAIL),ErrorCode::LOGIN_FAIL);
    }

    public static function sendCodeFail()
    {
        throw new self(ErrorCode::msg(ErrorCode::SEND_CODE_FAIL),ErrorCode::SEND_CODE_FAIL);
    }

    public static function sendCodeTooMuch()
    {
        throw new self(ErrorCode::msg(ErrorCode::SEND_CODE_TOO_MUCH),ErrorCode::SEND_CODE_TOO_MUCH);
    }
}