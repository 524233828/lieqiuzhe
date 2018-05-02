<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 15:01
 */

namespace Controller;

use FastD\Http\ServerRequest;
use Logic\LoginLogic;

class LoginController extends BaseController
{


    public function get(ServerRequest $request)
    {
        $login_type = $request->getParam("login_type");

        $params["code"] = $request->getParam("code","");

        return $this->response(LoginLogic::getInstance()->login($login_type, $params));
    }
}