<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/20
 * Time: 17:03
 */

namespace Exception;


use Constant\ErrorCode;

class RecommendException extends BaseException
{
    public static function recommendFail()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::RECOMMEND_FAIL),
            ErrorCode::RECOMMEND_FAIL
        );
    }

    public static function recommendEmpty()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::RECOMMEND_EMPTY),
            ErrorCode::RECOMMEND_EMPTY
        );
    }
}