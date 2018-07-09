<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/18
 * Time: 13:31
 */

namespace Logic\Lesson;

use EasyWeChat\User\User;
use Exception\CoinException;
use Logic\BaseLogic;
use Exception\ClassException;
use Logic\UserLogic;
use Model\Lesson\ArticleModel;
use Model\Lesson\BuyModel;
use Model\Lesson\ClassModel;
use Model\Lesson\MediaModel;
use Model\OrderModel;
use Model\UserBillModel;
use Model\UserClassModel;

class ClassLogic extends BaseLogic
{
    /**
     * 获取课程特色
     * @param $class_id
     * @return bool|mixed
     */
    public function getClass($class_id)
    {
        $class = ClassModel::getClass($class_id);

        if(empty($class)){
            ClassException::ClassNotFound();
        }

        $class['introduce'] = ClassModel::listClassIntroduce($class_id);

        return $class;
    }

    /**
     * 获取课程试听
     * @param $class_id
     * @return array
     */
    public function getClassTry($class_id)
    {
        $class = ClassModel::getClass($class_id);

        if(empty($class)){
            return [];
//            ClassException::ClassNotFound();
        }

        $try = ClassModel::listClassTry($class_id);

        if(count($try)<1){
            return [];
//            ClassException::NoTryInClass();
        }

        $resource_id = [];
        foreach ($try as $v)
        {
            $resource_id[] = $v['resource_id'];
        }

        $resource_id = array_unique($resource_id);

        $article = ArticleModel::listArticle(["id"=>$resource_id]);
        $article_index = [];
        foreach ($article as $k=>$v)
        {
            $article_index[$v['id']] = $v;
        }

        $media = MediaModel::listMedia(["id"=>$resource_id]);
        $media_index = [];
        foreach ($media as $k=>$v)
        {
            $media_index[$v['id']] = $v;
        }

        foreach ($try as $k=>$v)
        {
            if($v['resource_type']==0)
            {
                $try[$k]['resource'] = $media_index[$v['resource_id']];
            }else{
                $try[$k]['resource'] = $article_index[$v['resource_id']];
            }
        }
        return $try;
    }

    /**
     * 获取课程章节
     * @param $class_id
     * @return array
     */
    public function getClassChapter($class_id)
    {
        $class = ClassModel::getClass($class_id);

        if(empty($class)){
//            ClassException::ClassNotFound();
            return [];
        }

        $chapter = ClassModel::listClassChapter($class_id);

        $chapter_ids = [];
        foreach ($chapter as $v)
        {
            $chapter_ids[] = $v['id'];
        }

        if(count($chapter_ids)<1)
        {
//            ClassException::NoChapterInClass();
            return [];
        }
        $lesson = ClassModel::listChapterLesson([
            "chapter_id"=>$chapter_ids,
            "ORDER" => ["lesson_no" => "ASC", "id" => "ASC"],
        ]);

        if(count($lesson)<1)
        {
//            ClassException::NoLessonInChapter();
            return [];
        }

        $lesson_index = [];
        $resource_id = [];
        foreach ($lesson as $k => $v)
        {

            $resource_id[] = $v['resource_id'];
        }

        $article = ArticleModel::listArticle(["id"=>$resource_id]);
        $article_index = [];
        foreach ($article as $k=>$v)
        {
            $article_index[$v['id']] = $v;
        }

        $media = MediaModel::listMedia(["id"=>$resource_id]);
        $media_index = [];
        foreach ($media as $k=>$v)
        {
            $media_index[$v['id']] = $v;
        }

        foreach ($lesson as $k=>$v)
        {
            if($v['resource_type']==0)
            {
                $v['resource'] = $media_index[$v['resource_id']];
            }else{
                $v['resource'] = $article_index[$v['resource_id']];
            }
            $lesson_index[$v['chapter_id']][] = $v;

        }

        foreach ($chapter as $k => $v)
        {
            $chapter[$k]["lesson"]=isset($lesson_index[$v['id']])?$lesson_index[$v['id']]:[];
        }

        return $chapter;
    }

    public function buyClass($class_id,$channel = 1 ,$paysource = 0)
    {
        $class = ClassModel::getClass($class_id);

        if(!$class)
        {
            ClassException::ClassNotFound();
        }

        $class_list = UserClassModel::getUserClass(UserLogic::$user['id'],$class_id);
        if($class_list)
        {
            ClassException::ClassHasBought();
        }

        //获取当前金币余额
        $bill = UserBillModel::getCurrentBill(UserLogic::$user['id']);

        if($class['price'] > $bill){
            CoinException::coinNotEnough();
        }

        //生成金币消耗订单
        $order_id = OrderModel::getOrderId();
        $data = [
            "user_id" => UserLogic::$user['id'],
            "explain" => "用户购买课程减扣",
            "order_id" => $order_id,
            "status" => 1,
            "current_bill" => $bill - $class['price'],
            "inc_bill" => 0 - $class['price'],
            "last_bill" => $bill,
            "create_at" => time()
        ];


        //数据库记录订单
        //开启事务
        database()->pdo->beginTransaction();



        $user_class_data = [
            "class_id"=>$class_id,
            "user_id"=>UserLogic::$user['id'],
            "order_id"=>$order_id,
            "create_time"=>time(),
            "status"=>1,
        ];

        $result = UserBillModel::add($data);
        $buy_id = UserClassModel::addUserClass($user_class_data);
        if($order_id && $buy_id)
        {
            database()->pdo->commit();

        }else{
            database()->pdo->rollBack();
            return false;
        }
    }

}