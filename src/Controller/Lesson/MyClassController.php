<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/24
 * Time: 14:20
 */

namespace Controller\Lesson;

use Controller\BaseController;
use FastD\Http\ServerRequest;
use Logic\Lesson\MyClassLogic;
use Logic\UserLogic;

class MyClassController extends BaseController
{
    /**
     * @name 获取课程章节（购买后）
     * @apiParam class_id|int|课程ID|true
     * @returnParam id|int|课程ID
     * @returnParam sold|int|售出数量
     * @returnParam img_url|string|图片地址
     * @returnParam price|float|价格
     * @returnParam title|string|标题
     * @returnParam status|int|状态
     * @returnParam tag|string|标签
     * @returnParam desc|string|描述
     * @returnParam learn_percent|int|学习百分比
     * @returnParam chapter[].id|int|章节ID
     * @returnParam chapter[].title|string|章节标题
     * @returnParam chapter[].chapter_no|int|第几章
     * @returnParam chapter[].desc|int|第几章
     * @returnParam chapter[].lesson[].id|int|课时ID
     * @returnParam chapter[].lesson[].lesson_no|int|第几课
     * @returnParam chapter[].lesson[].title|int|课程标题
     * @returnParam chapter[].lesson[].desc|string|课程描述
     * @returnParam chapter[].lesson[].img_url|string|课程图片
     * @returnParam chapter[].lesson[].resource_id|string|资源ID
     * @returnParam chapter[].lesson[].resource_type|int|资源类型，0-视频 1-文章
     * @returnParam chapter[].lesson[].resource.media_time|int|视频长度，单位：秒， resource_type=0时存在
     * @returnParam chapter[].lesson[].resource.media_url|int|视频地址， resource_type=0时存在
     * @returnParam chapter[].lesson[].resource.title|string|文章标题，resource_type=1时存在
     * @returnParam chapter[].lesson[].resource.img_url|string|文章图片地址，resource_type=1时存在
     * @returnParam chapter[].lesson[].resource.content|string|文章内容，resource_type=1时存在
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function getClassChapter(ServerRequest $request)
    {
        $class_id = $request->getParam("class_id");

        return $this->response(MyClassLogic::getInstance()->getClassChapter($class_id));
    }

    /**
     * @name 更新学习百分比
     * @apiParam class_id|int|课程ID|true
     * @apiParam percent|int|学习百分比|true
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function updateLearnPercent(ServerRequest $request)
    {
        $class_id = $request->getParam("class_id");
        $percent = $request->getParam("percent");
        $user_id = UserLogic::$user['id'];

        return $this->response(MyClassLogic::getInstance()->updateLearnPercent($user_id,$class_id,$percent));
    }
}