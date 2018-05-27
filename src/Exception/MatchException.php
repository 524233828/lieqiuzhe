<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/2/9
 * Time: 19:22
 */

namespace Exception;

use Constant\ErrorCode;

class MatchException extends BaseException
{
    public static function matchNotExist()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::MATCH_NOT_EXIST),
            ErrorCode::MATCH_NOT_EXIST
        );
    }
}
