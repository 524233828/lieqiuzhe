<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/8/31
 * Time: 19:24
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\ValidCodeLogic;

class ValidCodeController extends BaseController
{

    public function sendCode(ServerRequest $request)
    {
        validator($request,["phone" => "required|mobile"]);

        $phone = $request->getParam("phone");

        return $this->response(ValidCodeLogic::getInstance()->sendCode($phone));
    }

    public function checkCode(ServerRequest $request)
    {
        validator($request,["phone" => "required|mobile", "code" => "required"]);

        $phone = $request->getParam("phone");
        $code = $request->getParam("code");

        return $this->response(ValidCodeLogic::getInstance()->checkCode($phone, $code));
    }
}