<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/3
 * Time: 10:29
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\ModifyUserLogic;

class ModifyUserController extends BaseController
{

    public function normal(ServerRequest $request)
    {
        $data['nickname'] = $request->getParam("nickname");
        $data['avatar'] = $request->getParam("avatar");
        $data['sex'] = $request->getParam("sex");

        $this->response(ModifyUserLogic::getInstance()->normal());
    }
}