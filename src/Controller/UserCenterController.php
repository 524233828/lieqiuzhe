<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 17:45
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\UserLogic;

class UserCenterController extends BaseController
{

    public function getInfo(ServerRequest $request)
    {
        return $this->response(UserLogic::getInstance()->getUserInfo());
    }
}