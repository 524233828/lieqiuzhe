<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/17
 * Time: 11:36
 */

namespace Controller\Admin;


use Controller\BaseController;
use FastD\Http\ServerRequest;
use Logic\Admin\LoginLogic;

class LoginController extends BaseController
{

    public function login(ServerRequest $request)
    {
        $admin = $request->getParam("admin");
        $password = $request->getParam("password");

        return $this->response(LoginLogic::getInstance()->adminLogin($admin,$password));
    }
}