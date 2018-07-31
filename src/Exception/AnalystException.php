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

    public static function userCanNotFollowSelf()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::ANALYST_IS_USERSELF),
            ErrorCode::ANALYST_IS_USERSELF
        );
    }

    public static function failFollow()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::FOLLOW_ANALYST_FAIL),
            ErrorCode::FOLLOW_ANALYST_FAIL
        );
    }

    public static function failUnfollow()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::UNFOLLOW_ANALYST_FAIL),
            ErrorCode::UNFOLLOW_ANALYST_FAIL
        );
    }

    public static function alreadyFollow()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::FOLLOW_ANALYST_ALREADY),
            ErrorCode::FOLLOW_ANALYST_ALREADY
        );
    }

    public static function alreadyUnfollow()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::UNFOLLOW_ANALYST_ALREADY),
            ErrorCode::UNFOLLOW_ANALYST_ALREADY
        );
    }



    public static function analystNotExist()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::ANALYST_NOT_EXITST),
            ErrorCode::ANALYST_NOT_EXITST
        );
    }

    public static function analystLimitPush()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::ANALYST_LIMIT_PUSH),
            ErrorCode::ANALYST_LIMIT_PUSH
        );
    }

}