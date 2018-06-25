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
use Logic\Lesson\MyClassListLogic;

class MyClassListController extends BaseController
{
    /**
     * @name 获取用户购买的课程列表
     * @returnParam [].id|int|课程ID
     * @returnParam [].img_url|string|课程图片
     * @returnParam [].title|string|课程标题
     * @returnParam [].desc|string|课程描述
     * @returnParam [].tag|string|课程标签
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function listUserClass(ServerRequest $request)
    {
        $user_id = $_SESSION['uid'];

        return $this->response(MyClassListLogic::getInstance()->listUserClass($user_id));
    }
}