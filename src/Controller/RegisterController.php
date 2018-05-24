<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/24
 * Time: 14:47
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\RegisterLogic;

class RegisterController extends BaseController
{

    public function sendCode(ServerRequest $request)
    {

        validator($request, [
            "phone" => "required|mobile"
        ]);
        $phone = $request->getParam("phone");

        return $this->response(RegisterLogic::getInstance()->sendCode($phone));
    }

    public function validCode(ServerRequest $request)
    {
        validator($request, [
            "phone" => "required|mobile",
            "code" => "required"
        ]);
        $phone = $request->getParam("phone");
        $code = $request->getParam("code");

        return $this->response(RegisterLogic::getInstance()->validCode($phone, $code));
    }

    public function addInfo(ServerRequest $request)
    {
        validator($request, [
            "nickname" => "required",
            "password" => "required",
            "confirm" => "required",
        ]);

        $nickname = $request->getParam("nickname");
        $password = $request->getParam("password");
        $confirm = $request->getParam("confirm");

        return $this->response(RegisterLogic::getInstance()->addInfo($nickname, $password, $confirm));
    }
}