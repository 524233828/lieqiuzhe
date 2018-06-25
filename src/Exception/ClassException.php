<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/18
 * Time: 14:36
 */

namespace Exception;

use Constant\ErrorCode;

class ClassException extends BaseException
{
    public static function ClassNotFound()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::CLASS_NOT_FOUND),
            ErrorCode::CLASS_NOT_FOUND
        );
    }

    public static function NoChapterInClass()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::CLASS_NO_CHAPTER),
            ErrorCode::CLASS_NO_CHAPTER
        );
    }

    public static function ChapterDuplicate()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::CHAPTER_DUPLICATE),
            ErrorCode::CHAPTER_DUPLICATE
        );
    }

    public static function LessonDuplicate()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::LESSON_DUPLICATE),
            ErrorCode::LESSON_DUPLICATE
        );
    }

    public static function NoLessonInChapter()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::CHAPTER_NO_LESSON),
            ErrorCode::CHAPTER_NO_LESSON
        );
    }

    public static function NoTryInClass()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::CLASS_NO_TRY),
            ErrorCode::CLASS_NO_TRY
        );
    }

    public static function ClassExpire()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::CLASS_EXPIRE),
            ErrorCode::CLASS_EXPIRE
        );
    }

    public static function ClassHasBought()
    {
        throw new self(
            ErrorCode::msg(ErrorCode::CLASS_HAS_BOUGHT),
            ErrorCode::CLASS_HAS_BOUGHT
        );
    }
}