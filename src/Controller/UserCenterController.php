<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 17:45
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\UserCenterLogic;
use Logic\UserLogic;

class UserCenterController extends BaseController
{

    public function getInfo(ServerRequest $request)
    {
        return $this->response(UserLogic::getInstance()->getUserInfo());
    }

    public function updateUserInfo(ServerRequest $request)
    {
        $nickname = $request->getParam("nickname");
        $avatar = $request->getParam("avatar");
        $sex = $request->getParam("sex");

        return $this->response(UserCenterLogic::getInstance()->updateUserInfo($nickname, $avatar, $sex));
    }
}