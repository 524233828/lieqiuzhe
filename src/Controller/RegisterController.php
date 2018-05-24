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
}