<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/16
 * Time: 22:17
 */

/**
 * @namespace Model;
 * @class ClassModel;
 * 课程模块类
 * 由于课程的章节、课、简介及试听信息脱离课程后都没有意义
 * 因此统一归类到课程模块
 */
namespace Model;

use Exception\BaseException;

class ClassModel extends BaseModel
{
    const CLASS_TABLE = "db_class";
    const CHAPTER_TABLE = "db_class_chapter";
    const LESSON_TABLE = "db_class_chapter_lesson";
    const INTRODUCE_TABLE = "db_class_introduce";
    const TRY_TABLE = "db_class_try";

    //课程class
    /**
     * 获取课程列表
     * @param array $where
     * @return array
     */
    public static function listClass($where = [])
    {
        $db = database("lesson");

        $result = $db->select(self::CLASS_TABLE,"*",
            $where
        );

        return $result;
    }

    /**
     * 获取课程数量
     * @param array $where
     * @return bool|int
     */
    public static function countClass($where = [])
    {
        $db = database("lesson");

        return $db->count(self::CLASS_TABLE,"*",$where);
    }

    /**
     * 根据课程ID获取课程
     * @param $class_id
     * @return bool|mixed
     */
    public static function getClass($class_id)
    {
        $db = database("lesson");

        $result = $db->get(self::CLASS_TABLE,"*",["id"=>$class_id]);

        return $result;
    }

    public static function addClass($data)
    {
        $data['create_time'] = time();
        $result = database("lesson")->insert(self::CLASS_TABLE,$data);

        if(!$result){
            BaseException::SystemError();
        }
        return database("lesson")->id();
    }

    public static function updateClass($where = [],$data = [])
    {
        $data['update_time'] = time();
        $db = database("lesson");

        $result = $db->update(self::CLASS_TABLE,$data,
            $where
        );

        return $result;
    }

    public static function deleteClass($class_id)
    {
        $data['update_time'] = time();
        $data['status'] = 0;
        $where['id'] = $class_id;

        $db = database("lesson");

        $result = $db->update(self::CLASS_TABLE,$data,
            $where
        );

        return $result;
    }

    //简介
    /**
     * 根据课程ID获取简介列表
     * @param $class_id
     * @return array
     */
    public static function listClassIntroduce($class_id)
    {
        $db = database("lesson");

        $where = [
            "class_id" => $class_id,
            "ORDER" => ["sort" => "DESC", "id" =>"ASC"]
        ];

        $result = $db->select(self::INTRODUCE_TABLE,"*",
            $where
        );

        return $result;
    }

    /**
     * 获取课程简介数量
     * @param array $where
     * @return bool|int
     */
    public static function countClassIntroduce($class_id,$where = [])
    {
        $where['class_id'] = $class_id;
        $db = database("lesson");

        return $db->count(self::CLASS_TABLE,"*",$where);
    }

    public static function addClassIntroduce($data)
    {
        $result = database("lesson")->insert(self::INTRODUCE_TABLE,$data);

        if(!$result){
            BaseException::SystemError();
        }
        return database("lesson")->id();
    }

    public static function updateClassIntroduce($where = [],$data = [])
    {
        $data['update_time'] = time();
        $db = database("lesson");

        $result = $db->update(self::INTRODUCE_TABLE,$data,
            $where
        );

        return $result;
    }

    public static function deleteClassIntroduce($id)
    {
        $where['id'] = $id;

        $db = database("lesson");

        $result = $db->delete(self::INTRODUCE_TABLE,
            $where
        );

        return $result;
    }

    //试听
    /**
     * 获取课程试听列表
     * @param $class_id
     * @return array
     */
    public static function listClassTry($class_id)
    {
        $db = database("lesson");

        $where = [
            "class_id" => $class_id,
            "ORDER" => ["sort" => "DESC", "id" =>"ASC"]
        ];

        $result = $db->select(self::TRY_TABLE,"*",
            $where
        );

        return $result;
    }

    /**
     * 获取试听数量
     * @param array $where
     * @return bool|int
     */
    public static function countTry($where = [])
    {
        $db = database("lesson");

        return $db->count(self::TRY_TABLE,"*",$where);
    }

    /**
     * 根据课程ID获取课程
     * @param $id
     * @return bool|mixed
     */
    public static function getTry($id)
    {
        $db = database("lesson");

        $result = $db->get(self::TRY_TABLE,"*",["id"=>$id]);

        return $result;
    }

    public static function addTry($data)
    {
        $data['create_time'] = time();
        $result = database("lesson")->insert(self::TRY_TABLE,$data);

        if(!$result){
            BaseException::SystemError();
        }
        return database("lesson")->id();

    }

    public static function updateTry($where = [],$data = [])
    {
        $data['update_time'] = time();
        $db = database("lesson");

        $result = $db->update(self::TRY_TABLE,$data,
            $where
        );

        return $result;
    }

    public static function deleteTry($id)
    {
        $where['id'] = $id;

        $db = database("lesson");

        $result = $db->delete(self::TRY_TABLE, $where);

        return $result;
    }

    //章节
    /**
     * 获取课程章节列表
     * @param $class_id
     * @return array
     */
    public static function listClassChapter($class_id)
    {
        $db = database("lesson");

        $where = [
            "class_id" => $class_id,
            "ORDER" => ["chapter_no" => "ASC", "id" => "ASC"]
        ];

        $result = $db->select(self::CHAPTER_TABLE,"*",
            $where
        );

        return $result;
    }

    /**
     * 获取试听数量
     * @param array $where
     * @return bool|int
     */
    public static function countChapter($where = [])
    {
        $db = database("lesson");

        return $db->count(self::CHAPTER_TABLE,"*",$where);
    }

    public static function updateChapter($where = [],$data = [])
    {
        $data['update_time'] = time();
        $db = database("lesson");

        $result = $db->update(self::CHAPTER_TABLE,$data,
            $where
        );

        return $result;
    }

    public static function deleteChapter($id)
    {
        $where['id'] = $id;

        $db = database("lesson");

        $result = $db->delete(self::CHAPTER_TABLE, $where);

        return $result;
    }

    public static function addChapter($data)
    {
        $data['create_time'] = time();
        $result = database("lesson")->insert(self::CHAPTER_TABLE,$data);

        if(!$result){
            BaseException::SystemError();
        }
        return database("lesson")->id();
    }

    public static function getChapter($id)
    {
        return database("lesson")->get(self::CHAPTER_TABLE,"*",["id" => $id]);
    }

    public static function getMaxChapter($class_id)
    {
        return database("lesson")->max(self::CHAPTER_TABLE, ["chapter_no"], ["class_id" => $class_id]);
    }

    //课时
    /**
     * 获取章节的课时列表
     * @param array $where
     * @return array
     */
    public static function listChapterLesson($where = [])
    {
        $db = database("lesson");

        $result = $db->select(self::LESSON_TABLE,"*",
            $where
        );

        return $result;
    }

    public static function addLesson($data)
    {
        $data['create_time'] = time();
        $result = database("lesson")->insert(self::LESSON_TABLE,$data);

        if(!$result){
            BaseException::SystemError();
        }
        return database("lesson")->id();
    }

    public static function deleteLesson($id)
    {
        $where['id'] = $id;

        $db = database("lesson");

        $result = $db->delete(self::LESSON_TABLE, $where);

        return $result;
    }

    public static function countLesson($where = [])
    {
        $db = database("lesson");

        return $db->count(self::LESSON_TABLE,"*",$where);
    }

    public static function getMaxLesson($chapter_id)
    {
        $db = database("lesson");

        return $db->max(self::LESSON_TABLE, ["lesson_no"], ["chapter_id" => $chapter_id]);
    }

    public static function getLesson($id, $column = "*", $where = [])
    {
        if(!isset($where['id'])){
            $where['id'] = $id;
        }
        return database("lesson")->get(self::LESSON_TABLE, $column, $where);
    }

    public static function updateLesson($where = [], $data = [])
    {
        $data['update_time'] = time();
        $db = database("lesson");

        $result = $db->update(self::LESSON_TABLE,$data,
            $where
        );

        return $result;
    }
}