<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/24
 * Time: 14:03
 */

namespace Controller\Lesson;

use Controller\BaseController;
use FastD\Http\ServerRequest;
use Logic\Lesson\MeLogic;

class MeController extends BaseController
{
    /**
     * @name 获取个人信息
     * @returnParam nickname|string|用户昵称
     * @returnParam headimgurl|string|用户头像地址，
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function getUser(ServerRequest $request)
    {
        $uid = $_SESSION['uid'];
        return $this->response(MeLogic::getInstance()->getUser($uid),true);
    }
}